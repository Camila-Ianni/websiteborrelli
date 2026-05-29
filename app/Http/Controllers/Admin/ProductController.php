<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.products.index', [
            'products' => Product::with(['brand', 'categories'])->latest()->paginate(15),
            'brands' => Brand::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.products.create', [
            'brands' => Brand::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        if (! empty($data['image'])) {
            $data['image_url'] = $request->file('image')->store('products', 'public');
        }

        DB::transaction(function () use ($data) {
            $categories = $data['categories'] ?? [];
            unset($data['categories'], $data['image']);

            $product = Product::create($data);
            $product->categories()->sync($categories);
        });

        session()->flash('success', __('messages.product_created'));

        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', [
            'product' => $product->load('categories'),
            'brands' => Brand::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product->id);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        if (! empty($data['image'])) {
            $this->deleteProductImage($product);
            $data['image_url'] = $request->file('image')->store('products', 'public');
        }

        DB::transaction(function () use ($data, $product) {
            $categories = $data['categories'] ?? [];
            unset($data['categories'], $data['image']);

            $product->update($data);
            $product->categories()->sync($categories);
        });

        session()->flash('success', __('messages.product_updated'));

        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product)
    {
        $this->deleteProductImage($product);
        $product->delete();

        session()->flash('success', __('messages.product_deleted'));

        return redirect()->route('admin.products.index');
    }

    public function bulk(Request $request)
    {
        $data = $request->validate([
            'action' => ['required', 'in:activate,deactivate,stock'],
            'selected' => ['required', 'array', 'min:1'],
            'selected.*' => ['integer', 'exists:products,id'],
            'stock' => ['nullable', 'required_if:action,stock', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($data) {
            $query = Product::whereIn('id', $data['selected']);

            if ($data['action'] === 'activate') {
                $query->update(['is_active' => true]);
            }

            if ($data['action'] === 'deactivate') {
                $query->update(['is_active' => false]);
            }

            if ($data['action'] === 'stock') {
                $query->update(['stock' => $data['stock'] ?? 0]);
            }
        });

        session()->flash('success', __('messages.bulk_action_success'));

        return redirect()->route('admin.products.index');
    }

    private function validateProduct(Request $request, ?int $productId = null): array
    {
        if (! $request->filled('slug')) {
            $request->merge([
                'slug' => Str::slug($request->input('title', '')),
            ]);
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', 'unique:products,slug,' . $productId],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0', 'gte:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'brand_id' => ['required', 'exists:brands,id'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ], [], [
            'brand_id' => __('messages.brand'),
        ]);

        return $data;
    }

    private function deleteProductImage(Product $product): void
    {
        if (! $product->image_url || Str::startsWith($product->image_url, ['http://', 'https://'])) {
            return;
        }

        if (Storage::disk('public')->exists($product->image_url)) {
            Storage::disk('public')->delete($product->image_url);
        }
    }
}
