<?php

namespace App\Http\Controllers\Web;

use App\Models\Sale;
use App\Http\Controllers\API\SaleController as APISaleController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SaleController extends Controller
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
        
        $apiController = new APISaleController();
        $response = $apiController->index($request);
        
        // Decode JSON response
        $data = json_decode($response->getContent(), true);
        
        // Convert to Laravel pagination for Blade compatibility
        $sales = new \Illuminate\Pagination\LengthAwarePaginator(
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
        
        return view('sales.index', compact('sales', 'stores'));
    }

    public function show(Sale $sale)
    {
        $this->setApiUser();
        
        $apiController = new APISaleController();
        $response = $apiController->show($sale);
        
        // Check for permission errors
        if ($response->getStatusCode() === 403) {
            abort(403, 'Unauthorized');
        }
        
        // Decode and convert to object for view
        $saleData = json_decode($response->getContent(), true);
        $sale = (object) $saleData;
        
        return view('sales.show', compact('sale'));
    }
}
