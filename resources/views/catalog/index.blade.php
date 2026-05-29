@extends('layouts.store')

@section('title', __('messages.catalog_title'))

@section('content')
    <section class="py-section-gap">
        <div class="max-w-container-max mx-auto px-margin-desktop">
            <div class="flex flex-col lg:flex-row justify-between items-start gap-8 mb-12">
                <div>
                    <h1 class="font-headline-lg text-headline-lg text-primary">{{ __('messages.catalog_heading') }}</h1>
                    <p class="font-body-md text-body-md text-on-surface-variant">{{ __('messages.catalog_subheading') }}</p>
                </div>
                <form action="{{ route('catalog.index') }}" class="w-full lg:w-auto flex gap-3">
                    <input class="bg-surface-container-low border-none rounded-full px-4 py-3 text-label-sm w-full lg:w-72 focus:ring-2 focus:ring-energy-lime transition-all" name="search" placeholder="{{ __('messages.search_placeholder') }}" type="text" value="{{ request('search') }}"/>
                    <button class="bg-primary text-on-primary px-6 rounded-full font-label-bold text-label-bold uppercase tracking-widest" type="submit">
                        {{ __('messages.search_button') }}
                    </button>
                </form>
            </div>

            <div class="grid lg:grid-cols-[280px_1fr] gap-gutter">
                <form class="bg-surface-container-low rounded-xl p-6 space-y-8" method="GET" action="{{ route('catalog.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <div>
                        <h3 class="font-label-bold text-label-bold uppercase tracking-widest text-primary mb-4">{{ __('messages.filter_categories') }}</h3>
                        <div class="space-y-3">
                            @foreach ($categories as $category)
                                <label class="flex items-center gap-3 text-on-surface-variant">
                                    <input type="checkbox" name="categories[]" value="{{ $category->slug }}" class="rounded border-outline-variant text-energy-lime focus:ring-energy-lime"
                                        @checked(in_array($category->slug, (array) request('categories', []), true))/>
                                    <span>{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h3 class="font-label-bold text-label-bold uppercase tracking-widest text-primary mb-4">{{ __('messages.filter_brands') }}</h3>
                        <select name="brand" class="w-full rounded-full bg-surface px-4 py-2 border-outline-variant focus:ring-energy-lime">
                            <option value="">{{ __('messages.filter_all_brands') }}</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->slug }}" @selected(request('brand') === $brand->slug)>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <h3 class="font-label-bold text-label-bold uppercase tracking-widest text-primary mb-4">{{ __('messages.filter_price') }}</h3>
                        <div class="flex items-center gap-3">
                            <input class="w-full rounded-full bg-surface px-4 py-2 border-outline-variant focus:ring-energy-lime" name="price_min" placeholder="{{ __('messages.filter_min') }}" type="number" min="0" step="0.01" value="{{ request('price_min') }}">
                            <input class="w-full rounded-full bg-surface px-4 py-2 border-outline-variant focus:ring-energy-lime" name="price_max" placeholder="{{ __('messages.filter_max') }}" type="number" min="0" step="0.01" value="{{ request('price_max') }}">
                        </div>
                    </div>
                    <button class="w-full bg-primary text-on-primary px-6 py-3 rounded-full font-label-bold text-label-bold uppercase tracking-widest">
                        {{ __('messages.filter_apply') }}
                    </button>
                </form>

                <div class="space-y-8">
                    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-gutter">
                        @forelse ($products as $product)
                            <x-store.product-card :product="$product" accent="energy-lime" />
                        @empty
                            <div class="col-span-3 bg-surface-container-low p-10 rounded-xl text-center text-on-surface-variant">
                                {{ __('messages.catalog_empty') }}
                            </div>
                        @endforelse
                    </div>

                    <div>
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
