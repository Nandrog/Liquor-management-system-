<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_can_be_placed()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->post('/orders', [
            'user_id' => $user->id,
            'products' => [$product->id => 1],
            'quantities' => [$product->id => 2],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['user_id' => $user->id]);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'quantity' => 2]);
    }
}
