<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['name' => 'Creatina', 'slug' => 'creatina', 'color' => '#D4FF00'],
            ['name' => 'Proteína', 'slug' => 'proteina', 'color' => '#FF8C00'],
            ['name' => 'Aminoácidos', 'slug' => 'aminoacidos', 'color' => '#FF2E63'],
            ['name' => 'Pre-entreno', 'slug' => 'pre-entreno', 'color' => '#0B1621'],
            ['name' => 'Recuperación', 'slug' => 'recuperacion', 'color' => '#755B11'],
        ]);
    }
}
