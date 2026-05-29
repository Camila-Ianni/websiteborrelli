@php
    $product = $product ?? null;
@endphp

<div class="grid md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-semibold mb-2">{{ __('messages.product_title') }}</label>
        <input class="w-full rounded-lg border-outline-variant bg-surface px-4 py-2" name="title" value="{{ old('title', $product?->title) }}" required>
    </div>
    <div>
        <label class="block text-sm font-semibold mb-2">{{ __('messages.product_slug') }}</label>
        <input class="w-full rounded-lg border-outline-variant bg-surface px-4 py-2" name="slug" value="{{ old('slug', $product?->slug) }}" placeholder="{{ __('messages.product_slug_hint') }}">
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold mb-2">{{ __('messages.product_description') }}</label>
        <textarea class="w-full rounded-lg border-outline-variant bg-surface px-4 py-2 min-h-[140px]" name="description" required>{{ old('description', $product?->description) }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-semibold mb-2">{{ __('messages.product_brand') }}</label>
        <select class="w-full rounded-lg border-outline-variant bg-surface px-4 py-2" name="brand_id" required>
            @foreach ($brands as $brand)
                <option value="{{ $brand->id }}" @selected(old('brand_id', $product?->brand_id) == $brand->id)>{{ $brand->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-semibold mb-2">{{ __('messages.product_categories') }}</label>
        <select class="w-full rounded-lg border-outline-variant bg-surface px-4 py-2" name="categories[]" multiple>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(collect(old('categories', $product?->categories?->pluck('id')->all() ?? []))->contains($category->id))>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-semibold mb-2">{{ __('messages.product_price') }}</label>
        <input class="w-full rounded-lg border-outline-variant bg-surface px-4 py-2" name="price" type="number" step="0.01" min="0" value="{{ old('price', $product?->price) }}" required>
    </div>
    <div>
        <label class="block text-sm font-semibold mb-2">{{ __('messages.product_original_price') }}</label>
        <input class="w-full rounded-lg border-outline-variant bg-surface px-4 py-2" name="original_price" type="number" step="0.01" min="0" value="{{ old('original_price', $product?->original_price) }}">
    </div>
    <div>
        <label class="block text-sm font-semibold mb-2">{{ __('messages.product_stock') }}</label>
        <input class="w-full rounded-lg border-outline-variant bg-surface px-4 py-2" name="stock" type="number" min="0" value="{{ old('stock', $product?->stock ?? 0) }}" required>
    </div>
    <div>
        <label class="block text-sm font-semibold mb-2">{{ __('messages.product_image') }}</label>
        <input class="w-full rounded-lg border-outline-variant bg-surface px-4 py-2" name="image" type="file" accept="image/*">
        @if ($product?->image_url)
            <p class="text-xs text-on-surface-variant mt-2">{{ __('messages.product_image_current') }}</p>
        @endif
    </div>
    <div class="flex items-center gap-4">
        <label class="flex items-center gap-2 text-sm font-semibold">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product?->is_active ?? true))>
            {{ __('messages.product_active') }}
        </label>
        <label class="flex items-center gap-2 text-sm font-semibold">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product?->is_featured ?? false))>
            {{ __('messages.product_featured') }}
        </label>
    </div>
</div>
