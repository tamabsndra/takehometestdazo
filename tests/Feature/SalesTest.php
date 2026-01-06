<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesTest extends TestCase
{
    use RefreshDatabase;

    protected $cashier;
    protected $store;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->store = Store::factory()->create();
        $this->cashier = User::factory()->create([
            'role' => 'cashier',
            'store_id' => $this->store->id,
        ]);
        $this->token = auth('api')->login($this->cashier);
    }

    public function test_cashier_can_create_sale(): void
    {
        $products = Product::factory()->count(3)->create([
            'store_id' => $this->store->id,
            'price' => 100000,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/sales', [
                'payment_method' => 'cash',
                'items' => [
                    ['product_id' => $products[0]->id, 'quantity' => 2],
                    ['product_id' => $products[1]->id, 'quantity' => 1],
                ],
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Sale created successfully',
            ]);

        $this->assertDatabaseHas('sales', [
            'store_id' => $this->store->id,
            'cashier_id' => $this->cashier->id,
            'payment_method' => 'cash',
            'total_amount' => 300000, // 2*100000 + 1*100000
        ]);
    }

    public function test_sale_validates_payment_method(): void
    {
        $product = Product::factory()->create(['store_id' => $this->store->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/sales', [
                'payment_method' => 'invalid',
                'items' => [
                    ['product_id' => $product->id, 'quantity' => 1],
                ],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['payment_method']);
    }

    public function test_sale_requires_at_least_one_item(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/sales', [
                'payment_method' => 'cash',
                'items' => [],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items']);
    }

    public function test_sale_calculates_total_correctly(): void
    {
        $product1 = Product::factory()->create([
            'store_id' => $this->store->id,
            'price' => 150000,
        ]);
        $product2 = Product::factory()->create([
            'store_id' => $this->store->id,
            'price' => 250000,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/sales', [
                'payment_method' => 'card',
                'items' => [
                    ['product_id' => $product1->id, 'quantity' => 3], // 450000
                    ['product_id' => $product2->id, 'quantity' => 2], // 500000
                ],
            ]);

        $response->assertStatus(201);
        
        $sale = Sale::latest()->first();
        $this->assertEquals(950000, $sale->total_amount);
    }

    public function test_cashier_can_view_sales_from_own_store(): void
    {
        Sale::factory()->count(5)->create([
            'store_id' => $this->store->id,
            'cashier_id' => $this->cashier->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/sales');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_cashier_cannot_view_sales_from_other_store(): void
    {
        $otherStore = Store::factory()->create();
        Sale::factory()->count(3)->create(['store_id' => $otherStore->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/sales');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_sales_report_returns_statistics(): void
    {
        Sale::factory()->count(5)->create([
            'store_id' => $this->store->id,
            'total_amount' => 100000,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/sales/report/statistics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'period',
                'summary' => ['total_sales', 'total_revenue', 'average_sale'],
                'sales_by_payment_method',
                'top_products',
            ]);
    }

    public function test_sale_filters_by_date_range(): void
    {
        Sale::factory()->create([
            'store_id' => $this->store->id,
            'transaction_date' => '2026-01-01',
        ]);
        Sale::factory()->create([
            'store_id' => $this->store->id,
            'transaction_date' => '2026-01-15',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/sales?start_date=2026-01-10&end_date=2026-01-20');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}
