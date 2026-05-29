<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = Brand::all();
        $categories = Category::all()->keyBy('slug');

        $featured = [
            [
                'title' => 'Creatine Power+ 300g',
                'slug' => 'creatine-power-300g',
                'description' => 'Creatina micronizada para fuerza, potencia y recuperación sostenida.',
                'price' => 24999,
                'original_price' => 29999,
                'stock' => 80,
                'image_url' => 'https://images.unsplash.com/photo-1599058917212-d750089bc07c?auto=format&fit=crop&w=600&q=80',
                'is_active' => true,
                'is_featured' => true,
                'categories' => ['creatina', 'pre-entreno'],
            ],
            [
                'title' => 'Whey Matrix 2kg',
                'slug' => 'whey-matrix-2kg',
                'description' => 'Proteína premium con liberación sostenida y perfil completo de aminoácidos.',
                'price' => 68999,
                'original_price' => 75999,
                'stock' => 45,
                'image_url' => 'https://images.unsplash.com/photo-1599058917663-9898a9f11a3b?auto=format&fit=crop&w=600&q=80',
                'is_active' => true,
                'is_featured' => true,
                'categories' => ['proteina', 'recuperacion'],
            ],
            [
                'title' => 'Amino Ignite',
                'slug' => 'amino-ignite',
                'description' => 'BCAA + EAA para recuperación inmediata y energía limpia.',
                'price' => 21999,
                'original_price' => 25999,
                'stock' => 60,
                'image_url' => 'https://images.unsplash.com/photo-1599058918140-769a4e46e1f6?auto=format&fit=crop&w=600&q=80',
                'is_active' => true,
                'is_featured' => true,
                'categories' => ['aminoacidos', 'recuperacion'],
            ],
        ];

        foreach ($featured as $item) {
            $categorySlugs = $item['categories'];
            unset($item['categories']);
            $item['brand_id'] = $brands->random()->id;

            $product = Product::create($item);
            $product->categories()->sync(
                $categories->only($categorySlugs)->pluck('id')->all()
            );
        }

        Product::factory()
            ->count(20)
            ->make()
            ->each(function (Product $product) use ($brands, $categories) {
                $product->brand_id = $brands->random()->id;
                $product->save();
                $product->categories()->sync(
                    $categories->random(rand(1, 3))->pluck('id')->all()
                );
            });
    }
}
