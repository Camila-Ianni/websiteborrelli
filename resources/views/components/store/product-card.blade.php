@props(['product', 'accent' => 'energy-lime'])

@php
    $imageUrl = $product->image_url;
    if ($imageUrl && !\Illuminate\Support\Str::startsWith($imageUrl, ['http://', 'https://'])) {
        $imageUrl = \Illuminate\Support\Facades\Storage::url($imageUrl);
    }
@endphp

<div class="product-card group bg-surface-container-low rounded-xl p-6 border border-outline-variant transition-all duration-300 hover:shadow-xl">
    <div class="flex justify-between items-start mb-4">
        <span class="px-3 py-1 rounded-full text-label-sm font-label-bold bg-{{ $accent }} text-primary uppercase tracking-widest">
            {{ __('messages.badge_featured') }}
        </span>
        <span class="material-symbols-outlined text-on-surface-variant hover:text-{{ $accent }} cursor-pointer">favorite</span>
    </div>
    <a class="flex justify-center items-center h-48 mb-6" href="{{ route('catalog.show', $product->slug) }}">
        <img alt="{{ $product->title }}" class="product-image w-40 h-40 object-contain transition-transform duration-500" src="{{ $imageUrl ?: 'https://images.unsplash.com/photo-1599058917212-d750089bc07c?auto=format&fit=crop&w=500&q=80' }}"/>
    </a>
    <h3 class="font-headline-md text-headline-md text-primary mb-2">
        <a href="{{ route('catalog.show', $product->slug) }}">{{ $product->title }}</a>
    </h3>
    <p class="font-body-md text-on-surface-variant mb-4 line-clamp-2">{{ $product->description }}</p>
    <div class="flex items-center justify-between">
        <div class="space-x-2">
            <span class="font-headline-md text-headline-md text-primary">${{ number_format($product->price, 2, ',', '.') }}</span>
            @if ($product->original_price)
                <span class="font-body-md text-on-surface-variant line-through">${{ number_format($product->original_price, 2, ',', '.') }}</span>
            @endif
        </div>
        <form action="{{ route('cart.add', $product) }}" method="POST">
            @csrf
            <button class="bg-{{ $accent }} text-primary px-4 py-2 rounded-full font-label-bold text-label-bold hover:bg-primary hover:text-pure-white transition-all" type="submit">
                {{ __('messages.add_to_cart') }}
            </button>
        </form>
    </div>
</div>
