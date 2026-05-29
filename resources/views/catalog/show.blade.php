@extends('layouts.store')

@section('title', $product->title)

@section('content')
    <section class="py-section-gap">
        <div class="max-w-container-max mx-auto px-margin-desktop grid lg:grid-cols-2 gap-gutter items-start">
            @php
                $imageUrl = $product->image_url;
                if ($imageUrl && !\Illuminate\Support\Str::startsWith($imageUrl, ['http://', 'https://'])) {
                    $imageUrl = \Illuminate\Support\Facades\Storage::url($imageUrl);
                }
            @endphp
            <div class="bg-surface-container-low rounded-2xl p-10 flex items-center justify-center">
                <img alt="{{ $product->title }}" class="w-96 h-96 object-contain" src="{{ $imageUrl ?: 'https://images.unsplash.com/photo-1599058917212-d750089bc07c?auto=format&fit=crop&w=600&q=80' }}"/>
            </div>
            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    @foreach ($product->categories as $category)
                        <span class="px-3 py-1 rounded-full text-label-sm font-label-bold bg-energy-lime text-primary uppercase tracking-widest">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>
                <h1 class="font-display-xl text-display-xl text-primary">{{ $product->title }}</h1>
                <p class="font-body-md text-body-md text-on-surface-variant">{{ $product->description }}</p>
                <div class="flex items-center gap-4">
                    <span class="font-headline-lg text-headline-lg text-primary">${{ number_format($product->price, 2, ',', '.') }}</span>
                    @if ($product->original_price)
                        <span class="font-body-md text-on-surface-variant line-through">${{ number_format($product->original_price, 2, ',', '.') }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-4">
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="flex items-center gap-4">
                        @csrf
                        <input class="w-24 rounded-full bg-surface-container-low border-outline-variant px-4 py-2 text-center" name="quantity" type="number" min="1" max="{{ $product->stock }}" value="1">
                        <button class="bg-primary text-on-primary px-8 py-3 rounded-full font-label-bold text-label-bold uppercase tracking-widest hover:bg-energy-lime hover:text-primary transition-all">
                            {{ __('messages.add_to_cart') }}
                        </button>
                    </form>
                    <span class="text-on-surface-variant">{{ __('messages.stock_label', ['count' => $product->stock]) }}</span>
                </div>
                <div class="bg-surface-container-low rounded-xl p-6">
                    <h3 class="font-label-bold text-label-bold uppercase tracking-widest text-primary">{{ __('messages.product_details') }}</h3>
                    <ul class="mt-4 space-y-2 text-on-surface-variant">
                        <li>{{ __('messages.product_brand') }}: {{ $product->brand->name }}</li>
                        <li>{{ __('messages.product_categories') }}: {{ $product->categories->pluck('name')->implode(', ') }}</li>
                        <li>{{ __('messages.product_shipping') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="py-section-gap bg-surface-container-low">
        <div class="max-w-container-max mx-auto px-margin-desktop">
            <div class="flex justify-between items-center mb-8">
                <h2 class="font-headline-lg text-headline-lg text-primary">{{ __('messages.related_products') }}</h2>
                <a class="font-label-bold text-label-bold uppercase tracking-widest text-primary hover:text-energy-lime transition-colors" href="{{ route('catalog.index') }}">
                    {{ __('messages.view_all') }}
                </a>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-gutter">
                @foreach ($related as $relatedProduct)
                    <x-store.product-card :product="$relatedProduct" accent="energy-lime" />
                @endforeach
            </div>
        </div>
    </section>
@endsection
