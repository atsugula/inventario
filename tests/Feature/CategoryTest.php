<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->admin, 'sanctum');
    }

    public function test_admin_can_create_category()
    {
        $response = $this->postJson('/api/categories', [
            'name' => 'Categoría Test',
            'description' => 'Descripción de prueba'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'category']);
    }

    public function test_admin_can_update_category()
    {
        $category = Category::factory()->create();

        $response = $this->putJson("/api/categories/{$category->id}", [
            'name' => 'Categoría actualizada'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Categoría actualizada correctamente']);
    }

    public function test_admin_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Categoría eliminada correctamente']);
    }
}
