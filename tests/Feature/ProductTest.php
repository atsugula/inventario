<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->admin, 'sanctum');
    }

    public function test_admin_can_create_product()
    {
        $category = Category::factory()->create();

        $response = $this->postJson('/api/products', [
            'name' => 'Producto Test',
            'description' => 'Descripción',
            'price' => 100,
            'stock' => 10,
            'category_id' => $category->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'product']);
    }

    public function test_admin_can_update_product()
    {
        $product = Product::factory()->create();

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Nuevo nombre',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Producto actualizado correctamente']);
    }

    public function test_admin_can_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Producto eliminado correctamente']);
    }

    public function test_user_can_view_product_list()
    {
        $this->getJson('/api/products')
            ->assertStatus(200);
    }
}
