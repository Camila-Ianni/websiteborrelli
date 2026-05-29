<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_product_to_cart(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->post(route('cart.add', $product), ['quantity' => 2]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('cart');

        $cart = session('cart');
        $this->assertSame(2, $cart['items'][$product->id]['quantity']);
    }

    public function test_prevents_adding_more_than_stock(): void
    {
        $product = Product::factory()->create(['stock' => 1]);

        $response = $this->post(route('cart.add', $product), ['quantity' => 3]);

        $response->assertSessionHasErrors('quantity');
    }
}
