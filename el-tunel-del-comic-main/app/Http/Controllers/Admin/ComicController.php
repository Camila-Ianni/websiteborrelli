<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comic;
use App\Models\Publisher;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComicController extends Controller
{
    public function index(Request $request)
    {
        $query = Comic::with(['publisher', 'categories']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        // Filter by stock
        if ($request->filled('filter_stock')) {
            switch ($request->filter_stock) {
                case 'low':
                    $query->where('stock', '>', 0)->where('stock', '<', 5);
                    break;
                case 'out':
                    $query->where('stock', 0);
                    break;
                case 'available':
                    $query->where('stock', '>', 0);
                    break;
            }
        }

        // Filter by active status
        if ($request->filled('filter_active')) {
            $query->where('is_active', $request->filter_active);
        }

        $comics = $query->latest()->paginate(20)->withQueryString();
        return view('admin.comics.index', compact('comics'));
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'comic_ids' => 'required|array',
            'comic_ids.*' => 'exists:comics,id',
            'bulk_action' => 'required|in:activate,deactivate,add_stock,price_up,price_down',
        ]);

        $comics = Comic::whereIn('id', $request->comic_ids)->get();
        $count = $comics->count();

        switch ($request->bulk_action) {
            case 'activate':
                Comic::whereIn('id', $request->comic_ids)->update(['is_active' => true]);
                $message = "{$count} comics activados.";
                break;
            case 'deactivate':
                Comic::whereIn('id', $request->comic_ids)->update(['is_active' => false]);
                $message = "{$count} comics desactivados.";
                break;
            case 'add_stock':
                Comic::whereIn('id', $request->comic_ids)->increment('stock', 5);
                $message = "Stock aumentado (+5) en {$count} comics.";
                break;
            case 'price_up':
                foreach ($comics as $comic) {
                    $comic->update(['price' => round($comic->price * 1.10, 2)]);
                }
                $message = "Precio aumentado (+10%) en {$count} comics.";
                break;
            case 'price_down':
                foreach ($comics as $comic) {
                    $comic->update(['price' => round($comic->price * 0.90, 2)]);
                }
                $message = "Precio reducido (-10%) en {$count} comics.";
                break;
        }

        return redirect()->route('admin.comics.index')->with('success', $message);
    }

    public function create()
    {
        $publishers = Publisher::all();
        $categories = Category::all();
        return view('admin.comics.create', compact('publishers', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'publisher_id' => 'required|exists:publishers,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'isbn' => 'nullable|string|unique:comics,isbn',
            'pages' => 'nullable|integer',
            'language' => 'required|string',
            'publication_date' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_new_release' => 'boolean',
            'is_active' => 'boolean',
            'categories' => 'array',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('comics', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_new_release'] = $request->has('is_new_release');
        $validated['is_active'] = $request->has('is_active') ? true : true; // Default active

        $comic = Comic::create($validated);

        if ($request->filled('categories')) {
            $comic->categories()->attach($request->categories);
        }

        return redirect()->route('admin.comics.index')->with('success', 'Comic creado exitosamente!');
    }

    public function show(Comic $comic)
    {
        $comic->load(['publisher', 'categories']);
        return view('admin.comics.show', compact('comic'));
    }

    public function edit(Comic $comic)
    {
        $publishers = Publisher::all();
        $categories = Category::all();
        $comic->load('categories');
        return view('admin.comics.edit', compact('comic', 'publishers', 'categories'));
    }

    public function update(Request $request, Comic $comic)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'publisher_id' => 'required|exists:publishers,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'isbn' => 'nullable|string|unique:comics,isbn,' . $comic->id,
            'pages' => 'nullable|integer',
            'language' => 'required|string',
            'publication_date' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_new_release' => 'boolean',
            'is_active' => 'boolean',
            'categories' => 'array',
        ]);

        if ($request->hasFile('image')) {
            if ($comic->image_path) {
                Storage::disk('public')->delete($comic->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('comics', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_new_release'] = $request->has('is_new_release');
        $validated['is_active'] = $request->has('is_active') ?: $comic->is_active;

        $comic->update($validated);

        if ($request->has('categories')) {
            $comic->categories()->sync($request->categories);
        }

        return redirect()->route('admin.comics.index')->with('success', 'Comic actualizado exitosamente!');
    }

    public function destroy(Comic $comic)
    {
        if ($comic->image_path) {
            Storage::disk('public')->delete($comic->image_path);
        }

        $comic->delete();
        return redirect()->route('admin.comics.index')->with('success', 'Comic eliminado exitosamente!');
    }
}
