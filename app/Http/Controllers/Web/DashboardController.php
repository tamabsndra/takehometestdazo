<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\StoreController as APIStoreController;
use App\Http\Controllers\API\ProductController as APIProductController;
use App\Http\Controllers\API\SaleController as APISaleController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Set current session user to API guard for API controller calls
     */
    protected function setApiUser()
    {
        auth('api')->setUser(auth()->user());
    }

    public function index()
    {
        $user = auth()->user();
        
        // Statistics based on role
        if ($user->isSuperAdmin()) {
            $this->setApiUser();
            
            // Basic stats (kept as direct queries for simplicity)
            $stats = [
                'total_stores' => Store::count(),
                'total_users' => User::count(),
                'total_products' => Product::count(),
                'total_sales' => Sale::count(),
                'total_revenue' => Sale::sum('total_amount'),
            ];
            
            // Recent stores from API
            $apiStoreController = new APIStoreController();
            $storeRequest = new Request(['per_page' => 5, 'sort_by' => 'created_at', 'sort_order' => 'desc']);
            $storeResponse = $apiStoreController->index($storeRequest);
            $storeData = json_decode($storeResponse->getContent(), true);
            $recentStores = collect($storeData['data'])->map(function($item) {
                return (object) $item;
            });
            
            // Sales by store (custom query, not in API)
            $salesByStore = Sale::select('store_id', DB::raw('count(*) as total_sales'), DB::raw('sum(total_amount) as revenue'))
                ->with('store')
                ->groupBy('store_id')
                ->orderByDesc('revenue')
                ->limit(5)
                ->get();
            
            return view('dashboard', compact('stats', 'recentStores', 'salesByStore'));
            
        } else {
            $this->setApiUser();
            
            // For Admin and Cashier (store-specific)
            $storeId = $user->store_id;
            
            // Basic stats (direct queries)
            $stats = [
                'store_name' => $user->store->name,
                'total_products' => Product::where('store_id', $storeId)->count(),
                'total_sales_today' => Sale::where('store_id', $storeId)
                    ->whereDate('transaction_date', today())
                    ->count(),
                'revenue_today' => Sale::where('store_id', $storeId)
                    ->whereDate('transaction_date', today())
                    ->sum('total_amount'),
                'revenue_month' => Sale::where('store_id', $storeId)
                    ->whereMonth('transaction_date', now()->month)
                    ->sum('total_amount'),
            ];
            
            // Recent sales from API
            $apiSaleController = new APISaleController();
            $saleRequest = new Request(['per_page' => 5, 'sort_by' => 'transaction_date', 'sort_order' => 'desc']);
            $saleResponse = $apiSaleController->index($saleRequest);
            $saleData = json_decode($saleResponse->getContent(), true);
            $recentSales = collect($saleData['data'])->map(function($item) {
                return (object) $item;
            });
            
            // Top products (custom query - could use API report endpoint but this is simpler)
            $topProducts = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->where('sales.store_id', $storeId)
                ->select('products.name', DB::raw('sum(sale_items.quantity) as total_sold'), DB::raw('sum(sale_items.subtotal) as revenue'))
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('revenue')
                ->limit(5)
                ->get();
            
            return view('dashboard', compact('stats', 'recentSales', 'topProducts'));
        }
    }
}
