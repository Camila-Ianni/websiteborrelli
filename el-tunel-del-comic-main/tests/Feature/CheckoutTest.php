<?php

namespace Tests\Feature;

use App\Models\Comic;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_page_requires_cart_items(): void
    {
        $response = $this->get('/checkout');

        $response->assertRedirect('/cart');
    }

    public function test_checkout_page_shows_with_cart_items(): void
    {
        $comic = Comic::factory()->create([
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->post('/cart/add/' . $comic->id);

        $response = $this->get('/checkout');

        $response->assertStatus(200);
        $response->assertSee($comic->title);
    }

    public function test_checkout_process_creates_order(): void
    {
        $comic = Comic::factory()->create([
            'price' => 50.00,
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->post('/cart/add/' . $comic->id);

        $response = $this->post('/checkout', [
            'customer_name' => 'John Doe',
            'customer_dni' => '12345678',
            'customer_email' => 'john@example.com',
            'payment_method' => 'transfer',
        ]);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
        ]);
    }

    public function test_checkout_decrements_stock(): void
    {
        $comic = Comic::factory()->create([
            'price' => 30.00,
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->post('/cart/add/' . $comic->id);
        $this->patch('/cart/update/' . $comic->id, ['quantity' => 3]);

        $this->post('/checkout', [
            'customer_name' => 'Jane Doe',
            'customer_dni' => '87654321',
            'customer_email' => 'jane@example.com',
            'payment_method' => 'transfer',
        ]);

        $this->assertEquals(7, $comic->fresh()->stock);
    }

    public function test_checkout_clears_cart(): void
    {
        $comic = Comic::factory()->create([
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->post('/cart/add/' . $comic->id);

        $this->post('/checkout', [
            'customer_name' => 'Test User',
            'customer_dni' => '11111111',
            'customer_email' => 'test@example.com',
            'payment_method' => 'transfer',
        ]);

        $this->assertEmpty(session('cart', []));
    }

    public function test_checkout_validates_required_fields(): void
    {
        $comic = Comic::factory()->create([
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->post('/cart/add/' . $comic->id);

        $response = $this->post('/checkout', [
            'customer_name' => '',
            'customer_dni' => '',
            'customer_email' => '',
            'payment_method' => 'transfer',
        ]);

        $response->assertSessionHasErrors(['customer_name', 'customer_dni', 'customer_email']);
    }

    public function test_checkout_success_page_shows_order(): void
    {
        $order = Order::create([
            'customer_name' => 'Success Test',
            'customer_dni' => '99999999',
            'customer_email' => 'success@test.com',
            'order_number' => Order::generateOrderNumber(),
            'total' => 100.00,
            'status' => 'pending',
        ]);

        $response = $this->get('/checkout/success/' . $order->id);

        $response->assertStatus(200);
        $response->assertSee($order->order_number);
    }

    public function test_checkout_failure_page_shows_order(): void
    {
        $order = Order::create([
            'customer_name' => 'Failure Test',
            'customer_dni' => '88888888',
            'customer_email' => 'failure@test.com',
            'order_number' => Order::generateOrderNumber(),
            'total' => 50.00,
            'status' => 'pending',
        ]);

        $response = $this->get('/checkout/failure/' . $order->id);

        $response->assertStatus(200);
    }
}
