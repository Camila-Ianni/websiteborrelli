<?php

namespace Tests\Feature;

use App\Models\Comic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_page_can_be_rendered(): void
    {
        $response = $this->get('/cart');

        $response->assertStatus(200);
    }

    public function test_comic_can_be_added_to_cart(): void
    {
        $comic = Comic::factory()->create([
            'stock' => 10,
            'is_active' => true,
        ]);

        $response = $this->post('/cart/add/' . $comic->id);

        $response->assertRedirect();
        $this->assertEquals(1, session('cart')[$comic->id]['quantity']);
    }

    public function test_cart_quantity_can_be_updated(): void
    {
        $comic = Comic::factory()->create([
            'stock' => 10,
            'is_active' => true,
        ]);

        // Add to cart first
        $this->post('/cart/add/' . $comic->id);

        // Update quantity
        $response = $this->patch('/cart/update/' . $comic->id, [
            'quantity' => 3,
        ]);

        $response->assertRedirect();
        $this->assertEquals(3, session('cart')[$comic->id]['quantity']);
    }

    public function test_comic_can_be_removed_from_cart(): void
    {
        $comic = Comic::factory()->create([
            'stock' => 10,
            'is_active' => true,
        ]);

        // Add to cart first
        $this->post('/cart/add/' . $comic->id);

        // Remove from cart
        $response = $this->delete('/cart/remove/' . $comic->id);

        $response->assertRedirect();
        $this->assertArrayNotHasKey($comic->id, session('cart', []));
    }

    public function test_cart_can_be_cleared(): void
    {
        $comic1 = Comic::factory()->create(['stock' => 10, 'is_active' => true]);
        $comic2 = Comic::factory()->create(['stock' => 10, 'is_active' => true]);

        $this->post('/cart/add/' . $comic1->id);
        $this->post('/cart/add/' . $comic2->id);

        $response = $this->delete('/cart/clear');

        $response->assertRedirect();
        $this->assertEmpty(session('cart', []));
    }

    public function test_cannot_add_out_of_stock_comic(): void
    {
        $comic = Comic::factory()->create([
            'stock' => 0,
            'is_active' => true,
        ]);

        $response = $this->post('/cart/add/' . $comic->id);

        $response->assertRedirect();
    }

    public function test_cart_shows_correct_total(): void
    {
        $comic = Comic::factory()->create([
            'price' => 25.00,
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->post('/cart/add/' . $comic->id);
        $this->patch('/cart/update/' . $comic->id, ['quantity' => 2]);

        $response = $this->get('/cart');

        $response->assertStatus(200);
        // Total should be 50.00
    }
}
