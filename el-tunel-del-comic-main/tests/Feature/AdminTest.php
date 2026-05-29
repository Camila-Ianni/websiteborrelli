<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Comic;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin@test.com',
        ]);
    }

    public function test_admin_can_access_comics_management(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/comics');

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/admin/comics');

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_admin_panel(): void
    {
        $response = $this->get('/admin/comics');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_create_comic(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/comics', [
            'title' => 'New Comic',
            'description' => 'A new comic book',
            'price' => 29.99,
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('comics', ['title' => 'New Comic']);
    }

    public function test_admin_can_update_comic(): void
    {
        $comic = Comic::factory()->create(['title' => 'Old Title']);

        $response = $this->actingAs($this->admin)->put('/admin/comics/' . $comic->id, [
            'title' => 'Updated Title',
            'description' => $comic->description,
            'price' => $comic->price,
            'stock' => $comic->stock,
            'is_active' => true,
        ]);

        $this->assertEquals('Updated Title', $comic->fresh()->title);
    }

    public function test_admin_can_delete_comic(): void
    {
        $comic = Comic::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/admin/comics/' . $comic->id);

        $this->assertDatabaseMissing('comics', ['id' => $comic->id]);
    }

    public function test_admin_can_perform_bulk_activate(): void
    {
        $comics = Comic::factory()->count(3)->create(['is_active' => false]);

        $response = $this->actingAs($this->admin)->post('/admin/comics/bulk-action', [
            'action' => 'activate',
            'comic_ids' => $comics->pluck('id')->toArray(),
        ]);

        foreach ($comics as $comic) {
            $this->assertTrue($comic->fresh()->is_active);
        }
    }

    public function test_admin_can_perform_bulk_deactivate(): void
    {
        $comics = Comic::factory()->count(3)->create(['is_active' => true]);

        $response = $this->actingAs($this->admin)->post('/admin/comics/bulk-action', [
            'action' => 'deactivate',
            'comic_ids' => $comics->pluck('id')->toArray(),
        ]);

        foreach ($comics as $comic) {
            $this->assertFalse($comic->fresh()->is_active);
        }
    }

    public function test_admin_can_view_orders(): void
    {
        Order::create([
            'customer_name' => 'Test Customer',
            'customer_dni' => '12345678',
            'customer_email' => 'customer@test.com',
            'order_number' => Order::generateOrderNumber(),
            'total' => 100.00,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/orders');

        $response->assertStatus(200);
        $response->assertSee('Test Customer');
    }

    public function test_admin_can_update_order_status(): void
    {
        $order = Order::create([
            'customer_name' => 'Status Test',
            'customer_dni' => '99999999',
            'customer_email' => 'status@test.com',
            'order_number' => Order::generateOrderNumber(),
            'total' => 50.00,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->patch('/admin/orders/' . $order->id . '/status', [
            'status' => 'paid',
        ]);

        $this->assertEquals('paid', $order->fresh()->status);
    }
}
