<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_filters_by_category_brand_and_search(): void
    {
        $brand = Brand::factory()->create(['name' => 'Borrelli Labs', 'slug' => 'borrelli-labs']);
        $category = Category::factory()->create(['name' => 'Creatina', 'slug' => 'creatina']);

        $matching = Product::factory()->create([
            'title' => 'Creatine Power',
            'description' => 'Creatina micronizada',
            'brand_id' => $brand->id,
            'is_active' => true,
        ]);
        $matching->categories()->attach($category);

        $other = Product::factory()->create([
            'title' => 'Proteina Whey',
            'description' => 'Proteína aislada',
            'brand_id' => $brand->id,
            'is_active' => true,
        ]);

        $response = $this->get('/catalog?search=Creatine&categories[]=creatina&brand=borrelli-labs');

        $response->assertOk();
        $response->assertSee('Creatine Power');
        $response->assertDontSee('Proteina Whey');
    }
}
