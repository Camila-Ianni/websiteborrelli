<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::insert([
            ['name' => 'Borrelli Labs', 'slug' => 'borrelli-labs', 'color' => '#D4FF00'],
            ['name' => 'Borrelli Performance', 'slug' => 'borrelli-performance', 'color' => '#FF8C00'],
            ['name' => 'Borrelli Elite', 'slug' => 'borrelli-elite', 'color' => '#FF2E63'],
        ]);
    }
}
