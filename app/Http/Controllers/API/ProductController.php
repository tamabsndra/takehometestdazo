<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of products with pagination and search
     */
    public function index(Request $request)
    {
        $user = auth('api')->user();
        $query = Product::with('store');

        // Filter by store for non-super admin
        if (!$user->isSuperAdmin()) {
            $query->where('store_id', $user->store_id);
        } elseif ($request->has('store_id')) {
            $query->where('store_id', $request->store_id);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('description', 'ILIKE', "%{$search}%")
                  ->orWhere('sku', 'ILIKE', "%{$search}%");
            });
        }

        // Price range filter
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $user = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'store_id' => $user->isSuperAdmin() ? 'required|exists:stores,id' : 'sometimes',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        
        // Auto-set store_id for non-super admin
        if (!$user->isSuperAdmin()) {
            $data['store_id'] = $user->store_id;
        }

        $product = Product::create($data);
        $product->load('store');

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $user = auth('api')->user();

        // Check permission
        if (!$user->isSuperAdmin() && $product->store_id !== $user->store_id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $product->load('store');

        return response()->json($product);
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $user = auth('api')->user();

        // Check permission
        if (!$user->isSuperAdmin() && $product->store_id !== $user->store_id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'sku' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product->update($request->all());
        $product->load('store');

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
        ]);
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        $user = auth('api')->user();

        // Check permission
        if (!$user->isSuperAdmin() && $product->store_id !== $user->store_id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $productName = $product->name;
        $product->delete();

        return response()->json([
            'message' => "Product '{$productName}' deleted successfully",
        ]);
    }
}
