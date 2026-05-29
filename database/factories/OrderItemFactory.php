<?php

namespace Database\Factories;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitPrice = $this->faker->randomFloat(2, 8000, 60000);
        $quantity = $this->faker->numberBetween(1, 5);

        return [
            'order_id' => \App\Models\Order::factory(),
            'product_id' => \App\Models\Product::factory(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total' => $unitPrice * $quantity,
        ];
    }
}
