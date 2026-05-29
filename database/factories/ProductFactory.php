<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->words(3, true);
        $price = $this->faker->numberBetween(12000, 85000);

        return [
            'brand_id' => \App\Models\Brand::factory(),
            'title' => ucfirst($title),
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraphs(2, true),
            'price' => $price,
            'original_price' => $price + $this->faker->numberBetween(2000, 8000),
            'stock' => $this->faker->numberBetween(5, 120),
            'image_url' => $this->faker->imageUrl(600, 600, 'sports', true),
            'is_active' => $this->faker->boolean(90),
            'is_featured' => $this->faker->boolean(30),
        ];
    }
}
