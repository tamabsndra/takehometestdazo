<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\StoreController as APIStoreController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
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
        
        $apiController = new APIStoreController();
        $response = $apiController->index($request);
        
        // Decode JSON response
        $data = json_decode($response->getContent(), true);
        
        // Convert to Laravel pagination for Blade compatibility
        $stores = new \Illuminate\Pagination\LengthAwarePaginator(
            $data['data'],
            $data['total'],
            $data['per_page'],
            $data['current_page'],
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        return view('stores.index', compact('stores'));
    }

    public function create()
    {
        return view('stores.create');
    }

    public function store(Request $request)
    {
        $this->setApiUser();
        
        $apiController = new APIStoreController();
        $response = $apiController->store($request);
        
        // Check if API call was successful
        if ($response->getStatusCode() === 201) {
            $data = json_decode($response->getContent(), true);
            $store = \App\Models\Store::find($data['store']['id']);
            
            // Web-specific: Auto-generate Admin and Cashier users
            // Admin
            \App\Models\User::create([
                'name' => 'Admin ' . $store->name,
                'email' => 'admin.' . \Illuminate\Support\Str::slug($store->name) . '@dazo.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'store_id' => $store->id,
            ]);

            // Cashier
            \App\Models\User::create([
                'name' => 'Cashier ' . $store->name,
                'email' => 'cashier.' . \Illuminate\Support\Str::slug($store->name) . '@dazo.com',
                'password' => bcrypt('password'),
                'role' => 'cashier',
                'store_id' => $store->id,
            ]);
            
            return redirect()->route('stores.index')
                ->with('success', 'Store created successfully (Admin & Cashier users generated)');
        }
        
        // Handle validation errors
        $errors = json_decode($response->getContent(), true);
        return redirect()->back()
            ->withInput()
            ->withErrors($errors['errors'] ?? ['error' => 'Failed to create store']);
    }

    public function edit(\App\Models\Store $store)
    {
        return view('stores.edit', compact('store'));
    }

    public function update(Request $request, \App\Models\Store $store)
    {
        $this->setApiUser();
        
        $apiController = new APIStoreController();
        $response = $apiController->update($request, $store);
        
        if ($response->getStatusCode() === 200) {
            return redirect()->route('stores.index')
                ->with('success', 'Store updated successfully');
        }
        
        // Handle validation errors
        $errors = json_decode($response->getContent(), true);
        return redirect()->back()
            ->withInput()
            ->withErrors($errors['errors'] ?? ['error' => 'Failed to update store']);
    }

    public function destroy(\App\Models\Store $store)
    {
        $this->setApiUser();
        
        $apiController = new APIStoreController();
        $response = $apiController->destroy($store);
        
        return redirect()->route('stores.index')
            ->with('success', 'Store deleted successfully');
    }
}
