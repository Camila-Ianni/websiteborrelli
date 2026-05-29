<?php

namespace Tests\Feature;

use App\Models\Comic;
use App\Models\Publisher;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_page_can_be_rendered(): void
    {
        $response = $this->get('/catalog');

        $response->assertStatus(200);
    }

    public function test_catalog_shows_active_comics(): void
    {
        $activeComic = Comic::factory()->create([
            'title' => 'Active Comic',
            'is_active' => true,
        ]);

        $inactiveComic = Comic::factory()->create([
            'title' => 'Inactive Comic',
            'is_active' => false,
        ]);

        $response = $this->get('/catalog');

        $response->assertSee('Active Comic');
        $response->assertDontSee('Inactive Comic');
    }

    public function test_catalog_can_search_by_title(): void
    {
        Comic::factory()->create(['title' => 'Spider-Man', 'is_active' => true]);
        Comic::factory()->create(['title' => 'Batman', 'is_active' => true]);

        $response = $this->get('/catalog?search=Spider');

        $response->assertSee('Spider-Man');
        $response->assertDontSee('Batman');
    }

    public function test_catalog_can_filter_by_publisher(): void
    {
        $marvel = Publisher::factory()->create(['name' => 'Marvel']);
        $dc = Publisher::factory()->create(['name' => 'DC']);

        Comic::factory()->create([
            'title' => 'Iron Man',
            'publisher_id' => $marvel->id,
            'is_active' => true,
        ]);

        Comic::factory()->create([
            'title' => 'Superman',
            'publisher_id' => $dc->id,
            'is_active' => true,
        ]);

        $response = $this->get('/catalog?publisher=' . $marvel->id);

        $response->assertSee('Iron Man');
        $response->assertDontSee('Superman');
    }

    public function test_catalog_can_filter_by_category(): void
    {
        $action = Category::factory()->create(['name' => 'Action']);
        $horror = Category::factory()->create(['name' => 'Horror']);

        $actionComic = Comic::factory()->create([
            'title' => 'Action Comic',
            'is_active' => true,
        ]);
        $actionComic->categories()->attach($action);

        $horrorComic = Comic::factory()->create([
            'title' => 'Horror Comic',
            'is_active' => true,
        ]);
        $horrorComic->categories()->attach($horror);

        $response = $this->get('/catalog?category=' . $action->id);

        $response->assertSee('Action Comic');
    }

    public function test_catalog_can_sort_by_price(): void
    {
        Comic::factory()->create(['title' => 'Cheap', 'price' => 10, 'is_active' => true]);
        Comic::factory()->create(['title' => 'Expensive', 'price' => 100, 'is_active' => true]);

        $response = $this->get('/catalog?sort=price_asc');

        $response->assertStatus(200);
    }

    public function test_catalog_paginates_results(): void
    {
        Comic::factory()->count(30)->create(['is_active' => true]);

        $response = $this->get('/catalog');

        $response->assertStatus(200);
    }
}
