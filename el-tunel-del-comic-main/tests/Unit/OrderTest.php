<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Comic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_can_be_created(): void
    {
        $order = Order::create([
            'customer_name' => 'John Doe',
            'customer_dni' => '12345678',
            'customer_email' => 'john@example.com',
            'order_number' => Order::generateOrderNumber(),
            'total' => 100.00,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
        ]);
    }

    public function test_order_number_is_generated(): void
    {
        $orderNumber = Order::generateOrderNumber();

        $this->assertStringStartsWith('ORD-', $orderNumber);
    }

    public function test_order_has_items(): void
    {
        $order = Order::create([
            'customer_name' => 'Jane Doe',
            'customer_dni' => '87654321',
            'customer_email' => 'jane@example.com',
            'order_number' => Order::generateOrderNumber(),
            'total' => 50.00,
            'status' => 'pending',
        ]);

        $comic = Comic::factory()->create(['price' => 25.00]);

        OrderItem::create([
            'order_id' => $order->id,
            'comic_id' => $comic->id,
            'quantity' => 2,
            'price' => 25.00,
            'subtotal' => 50.00,
        ]);

        $this->assertCount(1, $order->items);
    }

    public function test_order_status_can_change(): void
    {
        $order = Order::create([
            'customer_name' => 'Test User',
            'customer_dni' => '11111111',
            'customer_email' => 'test@example.com',
            'order_number' => Order::generateOrderNumber(),
            'total' => 75.00,
            'status' => 'pending',
        ]);

        $order->update(['status' => 'paid']);

        $this->assertEquals('paid', $order->fresh()->status);
    }

    public function test_order_is_paid_method(): void
    {
        $order = Order::create([
            'customer_name' => 'Test',
            'customer_dni' => '22222222',
            'customer_email' => 'paid@example.com',
            'order_number' => Order::generateOrderNumber(),
            'total' => 30.00,
            'status' => 'paid',
            'payment_status' => 'approved',
        ]);

        $this->assertTrue($order->isPaid());
    }

    public function test_order_total_is_decimal(): void
    {
        $order = Order::create([
            'customer_name' => 'Decimal Test',
            'customer_dni' => '33333333',
            'customer_email' => 'decimal@example.com',
            'order_number' => Order::generateOrderNumber(),
            'total' => 99.99,
            'status' => 'pending',
        ]);

        $this->assertEquals(99.99, $order->total);
    }

    public function test_order_can_have_payment_fields(): void
    {
        $order = Order::create([
            'customer_name' => 'MP Test',
            'customer_dni' => '44444444',
            'customer_email' => 'mp@example.com',
            'order_number' => Order::generateOrderNumber(),
            'total' => 150.00,
            'status' => 'pending',
            'payment_method' => 'mercadopago',
            'payment_id' => 'MP123456',
            'payment_status' => 'approved',
        ]);

        $this->assertEquals('mercadopago', $order->payment_method);
        $this->assertEquals('MP123456', $order->payment_id);
    }
}
