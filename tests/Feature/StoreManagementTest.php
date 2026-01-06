<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'SuperAdminSeeder']);
        $this->superAdmin = User::where('email', 'admin@dazo.com')->first();
        $this->token = auth('api')->login($this->superAdmin);
    }

    public function test_super_admin_can_list_stores(): void
    {
        Store::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/stores');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'level', 'address', 'phone'],
                ],
            ]);
    }

    public function test_super_admin_can_create_store(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/stores', [
                'name' => 'Test Store',
                'level' => 'pusat',
                'address' => 'Test Address',
                'phone' => '021-1234567',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Store created successfully',
            ]);

        $this->assertDatabaseHas('stores', [
            'name' => 'Test Store',
            'level' => 'pusat',
        ]);

        // Verify auto-created users
        $store = Store::where('name', 'Test Store')->first();
        $this->assertDatabaseHas('users', [
            'store_id' => $store->id,
            'role' => 'admin',
        ]);
        $this->assertDatabaseHas('users', [
            'store_id' => $store->id,
            'role' => 'cashier',
        ]);
    }

    public function test_store_creation_validates_required_fields(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/stores', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'level']);
    }

    public function test_super_admin_can_update_store(): void
    {
        $store = Store::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/stores/' . $store->id, [
                'name' => 'Updated Store Name',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Store updated successfully',
            ]);

        $this->assertDatabaseHas('stores', [
            'id' => $store->id,
            'name' => 'Updated Store Name',
        ]);
    }

    public function test_super_admin_can_delete_store(): void
    {
        $store = Store::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/stores/' . $store->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('stores', ['id' => $store->id]);
    }

    public function test_non_super_admin_cannot_manage_stores(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $adminToken = auth('api')->login($admin);

        $response = $this->withHeader('Authorization', 'Bearer ' . $adminToken)
            ->getJson('/api/stores');

        $response->assertStatus(403);
    }
}
