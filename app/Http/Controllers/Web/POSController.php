<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\ProductController as APIProductController;
use App\Http\Controllers\API\SaleController as APISaleController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;

class POSController extends Controller
{
    /**
     * Set current session user to API guard for API controller calls
     */
    protected function setApiUser()
    {
        auth('api')->setUser(auth()->user());
    }

    public function index(Request $request)
    {
        $this->setApiUser();
        $user = auth()->user();
        
        // Get products from API
        $apiController = new APIProductController();
        
        // Create request with no pagination (get all products for POS)
        $productRequest = new Request(['per_page' => 1000]);
        $response = $apiController->index($productRequest);
        
        // Decode JSON response
        $data = json_decode($response->getContent(), true);
        $products = collect($data['data'])->map(function($item) {
            return (object) $item;
        });
        
        // Get today's sales count for receipt numbering
        $todaySales = Sale::where('store_id', $user->store_id)
            ->whereDate('transaction_date', today())
            ->count();
        
        return view('pos.index', compact('products', 'todaySales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,qris,debit,credit',
        ]);

        $this->setApiUser();
        
        // Map web payment methods to API payment methods
        $paymentMethodMap = [
            'cash' => 'cash',
            'qris' => 'transfer',
            'debit' => 'card',
            'credit' => 'card',
        ];
        
        // Prepare request data for API
        $apiData = [
            'items' => $request->items,
            'payment_method' => $paymentMethodMap[$request->payment_method],
        ];
        
        // Create API request
        $apiRequest = new Request($apiData);
        
        // Call API controller
        $apiController = new APISaleController();
        $response = $apiController->store($apiRequest);
        
        if ($response->getStatusCode() === 201) {
            $data = json_decode($response->getContent(), true);
            return response()->json([
                'success' => true,
                'message' => 'Transaction successful',
                'sale_id' => $data['sale']['id'],
            ]);
        }
        
        // Handle errors
        $error = json_decode($response->getContent(), true);
        return response()->json([
            'success' => false,
            'message' => $error['message'] ?? 'Transaction failed',
        ], $response->getStatusCode());
    }
}
