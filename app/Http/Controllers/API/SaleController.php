<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    /**
     * Display a listing of sales with pagination and filters
     */
    public function index(Request $request)
    {
        $user = auth('api')->user();
        $query = Sale::with(['store', 'cashier', 'items.product']);

        // Filter by store for non-super admin
        if (!$user->isSuperAdmin()) {
            $query->where('store_id', $user->store_id);
        } elseif ($request->has('store_id')) {
            $query->where('store_id', $request->store_id);
        }

        // Date range filter
        if ($request->has('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        // Payment method filter
        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'transaction_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $sales = $query->paginate($perPage);

        return response()->json($sales);
    }

    /**
     * Store a newly created sale (POS checkout)
     */
    public function store(Request $request)
    {
        $user = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'store_id' => $user->isSuperAdmin() ? 'required|exists:stores,id' : 'sometimes',
            'payment_method' => 'required|string|in:cash,card,transfer',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Create sale
            $sale = Sale::create([
                'store_id' => $user->isSuperAdmin() ? $request->store_id : $user->store_id,
                'cashier_id' => $user->id,
                'total_amount' => 0, // Will be calculated
                'payment_method' => $request->payment_method,
                'transaction_date' => now(),
            ]);

            $totalAmount = 0;

            // Create sale items
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Check if product belongs to the same store
                if ($sale->store_id != $product->store_id) {
                    throw new \Exception("Product '{$product->name}' does not belong to this store");
                }

                $quantity = $item['quantity'];
                $unitPrice = $product->price;
                $subtotal = $quantity * $unitPrice;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                $totalAmount += $subtotal;
            }

            // Update sale total
            $sale->update(['total_amount' => $totalAmount]);

            DB::commit();

            // Load relationships for response
            $sale->load(['store', 'cashier', 'items.product']);

            return response()->json([
                'message' => 'Sale created successfully',
                'sale' => $sale,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to create sale',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified sale
     */
    public function show(Sale $sale)
    {
        $user = auth('api')->user();

        // Check permission
        if (!$user->isSuperAdmin() && $sale->store_id !== $user->store_id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $sale->load(['store', 'cashier', 'items.product']);

        return response()->json($sale);
    }

    /**
     * Get sales report/statistics
     */
    public function report(Request $request)
    {
        $user = auth('api')->user();
        $query = Sale::query();

        // Filter by store for non-super admin
        if (!$user->isSuperAdmin()) {
            $query->where('store_id', $user->store_id);
        } elseif ($request->has('store_id')) {
            $query->where('store_id', $request->store_id);
        }

        // Date range filter
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        
        $query->whereBetween('transaction_date', [$startDate, $endDate]);

        // Calculate statistics
        $totalSales = $query->count();
        $totalRevenue = $query->sum('total_amount');
        $averageSale = $totalSales > 0 ? $totalRevenue / $totalSales : 0;

        // Sales by payment method
        $salesByPayment = Sale::query()
            ->when(!$user->isSuperAdmin(), fn($q) => $q->where('store_id', $user->store_id))
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Top selling products
        $topProducts = SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->when(!$user->isSuperAdmin(), fn($q) => $q->where('sales.store_id', $user->store_id))
            ->whereBetween('sales.transaction_date', [$startDate, $endDate])
            ->select(
                'products.id',
                'products.name',
                DB::raw('sum(sale_items.quantity) as total_quantity'),
                DB::raw('sum(sale_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        return response()->json([
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => [
                'total_sales' => $totalSales,
                'total_revenue' => $totalRevenue,
                'average_sale' => round($averageSale, 2),
            ],
            'sales_by_payment_method' => $salesByPayment,
            'top_products' => $topProducts,
        ]);
    }
}
