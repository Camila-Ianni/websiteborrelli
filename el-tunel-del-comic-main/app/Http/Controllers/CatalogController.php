<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Publisher;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Comic::with(['publisher', 'categories'])
            ->where('is_active', true)
            ->where('stock', '>', 0);

        // Search in title and description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by publishers
        if ($request->filled('publishers')) {
            $publishers = is_array($request->publishers) ? $request->publishers : [$request->publishers];
            $query->whereIn('publisher_id', $publishers);
        }

        // Filter by categories
        if ($request->filled('categories')) {
            $categories = is_array($request->categories) ? $request->categories : [$request->categories];
            $query->whereHas('categories', function ($q) use ($categories) {
                $q->whereIn('categories.id', $categories);
            });
        }

        // Filter by price range
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Legacy price range filter
        if ($request->filled('price_range')) {
            $range = $request->price_range;
            if ($range == '0-20') {
                $query->where('price', '<=', 20);
            } elseif ($range == '20-40') {
                $query->whereBetween('price', [20, 40]);
            } elseif ($range == '40-60') {
                $query->whereBetween('price', [40, 60]);
            } elseif ($range == '60+') {
                $query->where('price', '>=', 60);
            }
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        $allowedSorts = ['created_at', 'price', 'title'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        $comics = $query->paginate(12)->withQueryString();
        $publishers = Publisher::all();
        $categories = Category::all();

        // For AJAX requests, return partial view
        if ($request->ajax()) {
            return response()->json([
                'html' => view('catalog._comics_grid', compact('comics'))->render(),
                'pagination' => $comics->links()->render(),
                'total' => $comics->total(),
            ]);
        }

        return view('catalog.index', compact('comics', 'publishers', 'categories'));
    }

    public function show(Comic $comic)
    {
        // Only show active comics
        if (!$comic->is_active) {
            abort(404);
        }
        
        $comic->load(['publisher', 'categories']);
        
        // Get related comics from same categories
        $relatedComics = Comic::where('id', '!=', $comic->id)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->whereHas('categories', function($q) use ($comic) {
                $q->whereIn('categories.id', $comic->categories->pluck('id'));
            })
            ->limit(4)
            ->get();
        
        return view('catalog.show', compact('comic', 'relatedComics'));
    }
}
