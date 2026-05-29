<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => 'BOR-' . Str::upper(Str::random(8)),
            'customer_name' => $this->faker->name(),
            'customer_dni' => $this->faker->numerify('########'),
            'customer_email' => $this->faker->unique()->safeEmail(),
            'customer_phone' => $this->faker->phoneNumber(),
            'total' => $this->faker->randomFloat(2, 10000, 200000),
            'status' => $this->faker->randomElement(['pending', 'paid', 'shipped', 'delivered']),
            'payment_method' => $this->faker->randomElement(['mercadopago', 'paypal', 'transfer']),
            'payment_gateway_id' => $this->faker->optional()->uuid(),
        ];
    }
}
