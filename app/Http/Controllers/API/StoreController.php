<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    /**
     * Display a listing of stores with pagination and search
     */
    public function index(Request $request)
    {
        $query = Store::with(['parent', 'users']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('address', 'ILIKE', "%{$search}%")
                  ->orWhere('phone', 'ILIKE', "%{$search}%");
            });
        }

        // Filter by level
        if ($request->has('level')) {
            $query->where('level', $request->level);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $stores = $query->paginate($perPage);

        return response()->json($stores);
    }

    /**
     * Store a newly created store
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'level' => 'required|in:pusat,cabang,retail',
            'parent_id' => 'nullable|exists:stores,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $store = Store::create($request->all());
        
        // Load relationships
        $store->load(['parent', 'users']);

        return response()->json([
            'message' => 'Store created successfully',
            'store' => $store,
        ], 201);
    }

    /**
     * Display the specified store
     */
    public function show(Store $store)
    {
        $store->load(['parent', 'children', 'users', 'products', 'sales']);
        
        // Add statistics
        $store->statistics = [
            'total_products' => $store->products()->count(),
            'total_sales' => $store->sales()->count(),
            'total_revenue' => $store->sales()->sum('total_amount'),
            'total_users' => $store->users()->count(),
        ];

        return response()->json($store);
    }

    /**
     * Update the specified store
     */
    public function update(Request $request, Store $store)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'level' => 'sometimes|required|in:pusat,cabang,retail',
            'parent_id' => 'nullable|exists:stores,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $store->update($request->all());
        $store->load(['parent', 'users']);

        return response()->json([
            'message' => 'Store updated successfully',
            'store' => $store,
        ]);
    }

    /**
     * Remove the specified store
     */
    public function destroy(Store $store)
    {
        $storeName = $store->name;
        $store->delete();

        return response()->json([
            'message' => "Store '{$storeName}' deleted successfully",
        ]);
    }
}
