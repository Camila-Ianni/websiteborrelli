@extends('layouts.app')

@section('title', $comic->title . ' - El Túnel del Cómic')

@section('content')
<div class="pt-24 min-h-screen bg-background text-on-background">
    <div class="max-w-container-max mx-auto px-margin-desktop py-section-gap">
        <!-- Breadcrumbs -->
        <div class="mb-8 text-sm font-bold uppercase text-on-surface-variant">
            <a href="{{ route('home') }}" class="hover:text-energy-lime transition">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('catalog.index') }}" class="hover:text-energy-lime transition">Catalog</a>
            <span class="mx-2">/</span>
            <span class="text-primary">{{ $comic->title }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-start">
            <!-- Image -->
            <div class="lg:col-span-7 space-y-gutter">
                <div class="relative bg-surface-container-low rounded-xl overflow-hidden aspect-square flex items-center justify-center group shadow-sm transition-all duration-300 hover:shadow-xl">
                @if($comic->image_path)
                    <img class="w-4/5 h-auto object-contain transition-transform duration-700 group-hover:scale-105" src="{{ asset('storage/' . $comic->image_path) }}" alt="{{ $comic->title }}"/>
                @else
                    <div class="w-full h-full bg-surface-container-low flex items-center justify-center">
                        <span class="text-outline-variant font-black text-9xl">{{ strtoupper(substr($comic->title, 0, 1)) }}</span>
                    </div>
                @endif
                </div>
            </div>

            <!-- Details -->
            <div class="lg:col-span-5 space-y-8">
                @if($comic->is_new_release)
                <span class="inline-block bg-energy-lime text-primary px-4 py-2 font-black uppercase text-sm rounded-full">NEW RELEASE</span>
                @endif
                
                @if($comic->is_featured)
                <span class="inline-block bg-primary-container text-energy-lime px-4 py-2 font-black uppercase text-sm ml-2 rounded-full">FEATURED</span>
                @endif

                <h1 class="font-headline-lg text-headline-lg text-primary uppercase leading-tight mb-2">{{ $comic->title }}</h1>
                
                <div class="flex gap-4 items-center flex-wrap">
                    <p class="font-headline-lg text-headline-lg text-primary">${{ number_format($comic->price, 2) }}</p>
                    @if($comic->stock > 0)
                        <span class="text-sm font-bold text-energy-lime bg-primary-container px-3 py-1 rounded-full">✓ In Stock ({{ $comic->stock }} available)</span>
                    @else
                        <span class="text-sm font-bold text-recovery-berry">✗ Out of Stock</span>
                    @endif
                </div>

                @if($comic->description)
                <div class="prose max-w-none">
                    <p class="text-lg">{{ $comic->description }}</p>
                </div>
                @endif

                <!-- Details Grid -->
                <div class="grid grid-cols-2 gap-4 py-6 border-y border-outline-variant">
                    <div>
                        <p class="text-xs font-black uppercase text-on-surface-variant mb-1">Publisher</p>
                        <p class="font-bold">{{ $comic->publisher->name }}</p>
                    </div>
                    @if($comic->isbn)
                    <div>
                        <p class="text-xs font-black uppercase text-on-surface-variant mb-1">ISBN</p>
                        <p class="font-bold">{{ $comic->isbn }}</p>
                    </div>
                    @endif
                    @if($comic->pages)
                    <div>
                        <p class="text-xs font-black uppercase text-on-surface-variant mb-1">Pages</p>
                        <p class="font-bold">{{ $comic->pages }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs font-black uppercase text-on-surface-variant mb-1">Language</p>
                        <p class="font-bold uppercase">{{ $comic->language }}</p>
                    </div>
                    @if($comic->publication_date)
                    <div>
                        <p class="text-xs font-black uppercase text-on-surface-variant mb-1">Publication Date</p>
                        <p class="font-bold">{{ $comic->publication_date->format('M Y') }}</p>
                    </div>
                    @endif
                </div>

                <!-- Categories -->
                @if($comic->categories->count() > 0)
                <div>
                    <p class="text-xs font-black uppercase text-on-surface-variant mb-3">Categories</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($comic->categories as $category)
                        <span class="bg-surface-container-lowest border border-outline-variant px-3 py-1 text-sm font-bold uppercase rounded-full">
                            {{ $category->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Add to Cart -->
                @if($comic->stock > 0)
                <form action="{{ route('cart.add', $comic) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="flex gap-4">
                        <input type="number" name="quantity" value="1" min="1" max="{{ $comic->stock }}" class="w-20 px-4 py-3 border border-outline-variant rounded-lg font-black text-center bg-surface-container-lowest"/>
                        <button type="submit" class="flex-1 bg-primary text-pure-white px-8 py-4 font-label-bold uppercase rounded-lg hover:bg-energy-lime hover:text-primary transition-all shadow-[0px_32px_32px_rgba(32,42,54,0.06)]">
                            Add to Cart
                        </button>
                    </div>
                </form>
                @else
                <button disabled class="w-full bg-surface-container-high text-on-surface-variant px-8 py-4 font-label-bold uppercase rounded-lg cursor-not-allowed">
                    Out of Stock
                </button>
                @endif

                <div class="flex gap-4">
                    <a href="{{ route('catalog.index') }}" class="flex-1 text-center bg-surface-container-lowest text-primary px-8 py-4 font-label-bold uppercase border border-outline-variant rounded-lg hover:bg-energy-lime hover:text-primary transition-all">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
