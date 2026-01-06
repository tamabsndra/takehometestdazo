<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\ProductController as APIProductController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
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
        
        $apiController = new APIProductController();
        $response = $apiController->index($request);
        
        // Decode JSON response
        $data = json_decode($response->getContent(), true);
        
        // Convert to Laravel pagination for Blade compatibility
        $products = new \Illuminate\Pagination\LengthAwarePaginator(
            collect($data['data'])->map(fn($item) => (object) $item),
            $data['total'],
            $data['per_page'],
            $data['current_page'],
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Get stores list for super admin filter
        $stores = null;
        if (auth()->user()->isSuperAdmin()) {
            $stores = \App\Models\Store::orderBy('name')->get();
        }
        
        return view('products.index', compact('products', 'stores'));
    }

    public function create()
    {
        $stores = null;
        
        // If super admin, get all stores for selection
        if (auth()->user()->isSuperAdmin()) {
            $stores = \App\Models\Store::orderBy('name')->get();
        }
        
        return view('products.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $this->setApiUser();
        
        // Add store_id to request for non-super admin users
        if (!auth()->user()->isSuperAdmin()) {
            $request->merge([
                'store_id' => auth()->user()->store_id
            ]);
        }
        
        $apiController = new APIProductController();
        $response = $apiController->store($request);
        
        if ($response->getStatusCode() === 201) {
            return redirect()->route('products.index')
                ->with('success', 'Product created successfully!');
        }
        
        // Handle validation errors
        $errors = json_decode($response->getContent(), true);
        return redirect()->back()
            ->withInput()
            ->withErrors($errors['errors'] ?? ['error' => 'Failed to create product']);
    }

    public function edit(Product $product)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && $product->store_id !== auth()->user()->store_id) {
            abort(403);
        }

        $stores = null;
        
        // If super admin, get all stores for selection
        if (auth()->user()->isSuperAdmin()) {
            $stores = \App\Models\Store::orderBy('name')->get();
        }

        return view('products.edit', compact('product', 'stores'));
    }

    public function update(Request $request, Product $product)
    {
        $this->setApiUser();
        
        $apiController = new APIProductController();
        $response = $apiController->update($request, $product);
        
        // API handles permission check
        if ($response->getStatusCode() === 403) {
            abort(403);
        }
        
        if ($response->getStatusCode() === 200) {
            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully!');
        }
        
        // Handle validation errors
        $errors = json_decode($response->getContent(), true);
        return redirect()->back()
            ->withInput()
            ->withErrors($errors['errors'] ?? ['error' => 'Failed to update product']);
    }

    public function destroy(Product $product)
    {
        $this->setApiUser();
        
        $apiController = new APIProductController();
        $response = $apiController->destroy($product);
        
        // API handles permission check
        if ($response->getStatusCode() === 403) {
            abort(403);
        }
        
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
