<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            return;
        }

        Order::factory()
            ->count(8)
            ->create()
            ->each(function (Order $order) use ($products) {
                $items = $products->random(rand(1, 3));
                $total = 0;

                foreach ($items as $product) {
                    $quantity = rand(1, 3);
                    $lineTotal = $product->price * $quantity;
                    $total += $lineTotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $product->price,
                        'total' => $lineTotal,
                    ]);
                }

                $order->update(['total' => $total]);
            });
    }
}
