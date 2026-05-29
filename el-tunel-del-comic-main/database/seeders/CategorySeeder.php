<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Superhéroes', 'description' => 'Comics de superhéroes clásicos y modernos'],
            ['name' => 'Manga', 'description' => 'Manga japonés de todas las editoriales'],
            ['name' => 'Sci-Fi', 'description' => 'Ciencia ficción y futurismo'],
            ['name' => 'Horror', 'description' => 'Terror y suspenso'],
            ['name' => 'Fantasy', 'description' => 'Fantasía épica y mitología'],
            ['name' => 'Acción', 'description' => 'Historias llenas de adrenalina'],
            ['name' => 'Aventura', 'description' => 'Grandes viajes y exploraciones'],
            ['name' => 'Drama', 'description' => 'Historias dramáticas y emocionales'],
            ['name' => 'Comedia', 'description' => 'Humor y risas'],
            ['name' => 'Seinen', 'description' => 'Manga para adultos jóvenes'],
            ['name' => 'Shonen', 'description' => 'Manga para jóvenes'],
            ['name' => 'Shojo', 'description' => 'Manga para chicas'],
            ['name' => 'Mechas', 'description' => 'Robots gigantes y tecnología'],
            ['name' => 'Deportes', 'description' => 'Manga y comics deportivos'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
            ]);
        }
    }
}
