<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_reservation_success()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 1000]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/v1/products/{$product->id}/reserve", [
                'quantity' => 1
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
