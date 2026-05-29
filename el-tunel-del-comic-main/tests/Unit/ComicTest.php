<?php

namespace Tests\Unit;

use App\Models\Comic;
use App\Models\Publisher;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComicTest extends TestCase
{
    use RefreshDatabase;

    public function test_comic_can_be_created(): void
    {
        $comic = Comic::factory()->create([
            'title' => 'Test Comic',
            'price' => 29.99,
            'stock' => 10,
        ]);

        $this->assertDatabaseHas('comics', [
            'title' => 'Test Comic',
            'price' => 29.99,
        ]);
    }

    public function test_comic_belongs_to_publisher(): void
    {
        $publisher = Publisher::factory()->create(['name' => 'Marvel']);
        $comic = Comic::factory()->create(['publisher_id' => $publisher->id]);

        $this->assertEquals('Marvel', $comic->publisher->name);
    }

    public function test_comic_can_have_categories(): void
    {
        $comic = Comic::factory()->create();
        $category = Category::factory()->create(['name' => 'Action']);

        $comic->categories()->attach($category);

        $this->assertTrue($comic->categories->contains($category));
    }

    public function test_comic_is_active_scope(): void
    {
        Comic::factory()->create(['is_active' => true]);
        Comic::factory()->create(['is_active' => false]);

        $activeComics = Comic::active()->get();

        $this->assertCount(1, $activeComics);
    }

    public function test_comic_in_stock_scope(): void
    {
        Comic::factory()->create(['stock' => 5]);
        Comic::factory()->create(['stock' => 0]);

        $inStockComics = Comic::where('stock', '>', 0)->get();

        $this->assertCount(1, $inStockComics);
    }

    public function test_comic_price_is_decimal(): void
    {
        $comic = Comic::factory()->create(['price' => 19.99]);

        $this->assertEquals(19.99, $comic->price);
    }

    public function test_comic_slug_is_generated(): void
    {
        $comic = Comic::factory()->create(['title' => 'My Awesome Comic']);

        $this->assertNotEmpty($comic->slug);
    }

    public function test_comic_can_decrement_stock(): void
    {
        $comic = Comic::factory()->create(['stock' => 10]);

        $comic->decrement('stock', 3);

        $this->assertEquals(7, $comic->fresh()->stock);
    }

    public function test_comic_can_increment_stock(): void
    {
        $comic = Comic::factory()->create(['stock' => 10]);

        $comic->increment('stock', 5);

        $this->assertEquals(15, $comic->fresh()->stock);
    }
}
