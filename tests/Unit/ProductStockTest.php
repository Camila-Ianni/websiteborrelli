<?php

namespace Tests\Unit;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class ProductStockTest extends TestCase
{
    use RefreshDatabase;

    public function test_decrement_stock_reduces_quantity(): void
    {
        $product = Product::factory()->create(['stock' => 5]);

        $product->decrementStock(2);

        $this->assertSame(3, $product->fresh()->stock);
    }

    public function test_decrement_stock_throws_when_insufficient(): void
    {
        $product = Product::factory()->create(['stock' => 1]);

        $this->expectException(InvalidArgumentException::class);
        $product->decrementStock(3);
    }
}
