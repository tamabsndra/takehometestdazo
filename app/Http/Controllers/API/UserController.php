<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of users with pagination and search
     */
    public function index(Request $request)
    {
        $user = auth('api')->user();
        $query = User::with('store');

        // Super admin can see all users
        if (!$user->isSuperAdmin()) {
            // Admin can only see cashiers from their store
            $query->where('store_id', $user->store_id)
                  ->where('role', 'cashier');
        } else {
            // Filter by role if provided
            if ($request->has('role')) {
                $query->where('role', $request->role);
            }
            
            // Filter by store if provided
            if ($request->has('store_id')) {
                $query->where('store_id', $request->store_id);
            }
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('email', 'ILIKE', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $users = $query->paginate($perPage);

        return response()->json($users);
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $currentUser = auth('api')->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ];

        // Super admin can create any role and assign to any store
        if ($currentUser->isSuperAdmin()) {
            $rules['role'] = 'required|in:super_admin,admin,cashier';
            $rules['store_id'] = 'nullable|exists:stores,id';
        } else {
            // Admin can only create cashiers
            $rules['role'] = 'sometimes|in:cashier';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        
        // Auto-set role and store_id for non-super admin
        if (!$currentUser->isSuperAdmin()) {
            $data['role'] = 'cashier';
            $data['store_id'] = $currentUser->store_id;
        }

        $newUser = User::create($data);
        $newUser->load('store');

        return response()->json([
            'message' => 'User created successfully',
            'user' => $newUser,
        ], 201);
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $currentUser = auth('api')->user();

        // Check permission
        if (!$currentUser->isSuperAdmin()) {
            // Admin can only view cashiers from their store
            if ($user->store_id !== $currentUser->store_id || $user->role !== 'cashier') {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }

        $user->load('store');

        // Add statistics for the user
        if ($user->isCashier()) {
            $user->statistics = [
                'total_sales' => $user->sales()->count(),
                'total_revenue' => $user->sales()->sum('total_amount'),
            ];
        }

        return response()->json($user);
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $currentUser = auth('api')->user();

        // Check permission
        if (!$currentUser->isSuperAdmin()) {
            // Admin can only update cashiers from their store
            if ($user->store_id !== $currentUser->store_id || $user->role !== 'cashier') {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }

        $rules = [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:6',
        ];

        // Only super admin can change role and store
        if ($currentUser->isSuperAdmin()) {
            $rules['role'] = 'sometimes|required|in:super_admin,admin,cashier';
            $rules['store_id'] = 'nullable|exists:stores,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except(['password']);
        
        // Hash password if provided
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->load('store');

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
        ]);
    }

    /**
     * Update current user profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except(['password', 'role', 'store_id']);
        
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->load('store');

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        $currentUser = auth('api')->user();

        // Check permission
        if (!$currentUser->isSuperAdmin()) {
            // Admin can only delete cashiers from their store
            if ($user->store_id !== $currentUser->store_id || $user->role !== 'cashier') {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }

        // Prevent deleting self
        if ($user->id === $currentUser->id) {
            return response()->json(['error' => 'Cannot delete your own account'], 400);
        }

        $userName = $user->name;
        $user->delete();

        return response()->json([
            'message' => "User '{$userName}' deleted successfully",
        ]);
    }
}
