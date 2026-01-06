<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\UserController as APIUserController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
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
        
        $apiController = new APIUserController();
        $response = $apiController->index($request);
        
        // Decode JSON response
        $data = json_decode($response->getContent(), true);
        
        // Convert to Laravel pagination for Blade compatibility
        $users = new \Illuminate\Pagination\LengthAwarePaginator(
            collect($data['data'])->map(function($item) {
                return (object) $item;
            }),
            $data['total'],
            $data['per_page'],
            $data['current_page'],
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->setApiUser();
        
        // Remove password from request if not provided (optional field for update)
        if (empty($request->password)) {
            $request->request->remove('password');
        }
        
        $apiController = new APIUserController();
        $response = $apiController->update($request, $user->id);
        
        // API handles permission check
        if ($response->getStatusCode() === 403) {
            abort(403, 'Forbidden');
        }
        
        if ($response->getStatusCode() === 200) {
            return redirect()->route('users.index')
                ->with('success', 'User updated successfully!');
        }
        
        // Handle validation errors
        $errors = json_decode($response->getContent(), true);
        return redirect()->back()
            ->withInput()
            ->withErrors($errors['errors'] ?? ['error' => 'Failed to update user']);
    }

    public function destroy(User $user)
    {
        $this->setApiUser();
        
        $apiController = new APIUserController();
        $response = $apiController->destroy($user);
        
        // API handles permission check
        if ($response->getStatusCode() === 403) {
            abort(403, 'Forbidden');
        }
        
        if ($response->getStatusCode() === 400) {
            $error = json_decode($response->getContent(), true);
            return redirect()->route('users.index')
                ->withErrors(['error' => $error['error'] ?? 'Cannot delete user']);
        }
        
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
