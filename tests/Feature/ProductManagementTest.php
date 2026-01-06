<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $store;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->store = Store::factory()->create();
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'store_id' => $this->store->id,
        ]);
        $this->token = auth('api')->login($this->admin);
    }

    public function test_admin_can_list_products_from_own_store(): void
    {
        Product::factory()->count(5)->create(['store_id' => $this->store->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_create_product(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/products', [
                'name' => 'Test Product',
                'description' => 'Test Description',
                'price' => 100000,
                'sku' => 'TEST-001',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Product created successfully',
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'store_id' => $this->store->id,
        ]);
    }

    public function test_product_creation_validates_price(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/products', [
                'name' => 'Test Product',
                'price' => -100,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['price']);
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::factory()->create(['store_id' => $this->store->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/products/' . $product->id, [
                'name' => 'Updated Product',
                'price' => 200000,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
        ]);
    }

    public function test_admin_cannot_update_product_from_other_store(): void
    {
        $otherStore = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $otherStore->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/products/' . $product->id, [
                'name' => 'Updated Product',
            ]);

        $response->assertStatus(403);
    }

    public function test_cashier_can_view_products_but_not_create(): void
    {
        $cashier = User::factory()->create([
            'role' => 'cashier',
            'store_id' => $this->store->id,
        ]);
        $cashierToken = auth('api')->login($cashier);

        Product::factory()->count(3)->create(['store_id' => $this->store->id]);

        // Can view
        $response = $this->withHeader('Authorization', 'Bearer ' . $cashierToken)
            ->getJson('/api/products');
        $response->assertStatus(200);

        // Cannot create
        $response = $this->withHeader('Authorization', 'Bearer ' . $cashierToken)
            ->postJson('/api/products', [
                'name' => 'Test',
                'price' => 100000,
            ]);
        $response->assertStatus(403);
    }

    public function test_product_search_works(): void
    {
        Product::factory()->create([
            'store_id' => $this->store->id,
            'name' => 'Laptop Asus',
        ]);
        Product::factory()->create([
            'store_id' => $this->store->id,
            'name' => 'Mouse Logitech',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/products?search=laptop');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}
