<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function home()
    {
        $featuredProducts = Product::query()
            ->with(['categories', 'brand'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->take(3)
            ->get();

        return view('home', [
            'featuredProducts' => $featuredProducts,
        ]);
    }

    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['categories', 'brand'])
            ->where('is_active', true);

        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categoryFilter = $request->input('categories', []);
        if (! empty($categoryFilter)) {
            $categoryFilter = is_array($categoryFilter) ? $categoryFilter : [$categoryFilter];
            $query->whereHas('categories', function ($builder) use ($categoryFilter) {
                $builder->whereIn('categories.slug', $categoryFilter)
                    ->orWhereIn('categories.id', $categoryFilter);
            });
        }

        if ($brand = $request->input('brand')) {
            $query->whereHas('brand', function ($builder) use ($brand) {
                $builder->where('brands.slug', $brand)
                    ->orWhere('brands.id', $brand);
            });
        }

        if ($min = $request->input('price_min')) {
            $query->where('price', '>=', (float) $min);
        }

        if ($max = $request->input('price_max')) {
            $query->where('price', '<=', (float) $max);
        }

        $products = $query->paginate(12)->withQueryString();

        return view('catalog.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'filters' => $request->all(),
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::query()
            ->with(['categories', 'brand'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('catalog.show', [
            'product' => $product,
            'related' => Product::query()
                ->where('is_active', true)
                ->where('id', '!=', $product->id)
                ->take(3)
                ->get(),
        ]);
    }
}
