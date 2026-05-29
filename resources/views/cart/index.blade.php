@extends('layouts.store')

@section('title', __('messages.cart_title'))

@section('content')
    <section class="py-section-gap">
        <div class="max-w-container-max mx-auto px-margin-desktop">
            <div class="flex flex-col lg:flex-row justify-between items-start gap-8 mb-12">
                <div>
                    <h1 class="font-headline-lg text-headline-lg text-primary">{{ __('messages.cart_heading') }}</h1>
                    <p class="font-body-md text-body-md text-on-surface-variant">{{ __('messages.cart_subheading') }}</p>
                </div>
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button class="border border-primary text-primary px-6 py-3 rounded-full font-label-bold text-label-bold uppercase tracking-widest hover:bg-primary hover:text-on-primary transition-all">
                        {{ __('messages.cart_clear') }}
                    </button>
                </form>
            </div>

            <div class="grid lg:grid-cols-[1fr_360px] gap-gutter">
                <div class="space-y-6">
                    @forelse ($cart['items'] as $item)
                        @php
                            $imageUrl = $item['image_url'] ?? null;
                            if ($imageUrl && !\Illuminate\Support\Str::startsWith($imageUrl, ['http://', 'https://'])) {
                                $imageUrl = \Illuminate\Support\Facades\Storage::url($imageUrl);
                            }
                        @endphp
                        <div class="bg-surface-container-low rounded-xl p-6 flex flex-col md:flex-row md:items-center gap-6">
                            <img alt="{{ $item['title'] }}" class="w-32 h-32 object-contain" src="{{ $imageUrl ?: 'https://images.unsplash.com/photo-1599058917212-d750089bc07c?auto=format&fit=crop&w=500&q=80' }}"/>
                            <div class="flex-1 space-y-2">
                                <h3 class="font-headline-md text-headline-md text-primary">{{ $item['title'] }}</h3>
                                <p class="text-on-surface-variant">${{ number_format($item['price'], 2, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <form action="{{ route('cart.update', $item['product_id']) }}" method="POST" class="flex items-center gap-3">
                                    @csrf
                                    @method('PUT')
                                    <input class="w-20 rounded-full bg-surface px-4 py-2 text-center border-outline-variant" name="quantity" type="number" min="0" value="{{ $item['quantity'] }}">
                                    <button class="bg-primary text-on-primary px-4 py-2 rounded-full font-label-bold text-label-bold uppercase tracking-widest">
                                        {{ __('messages.cart_update') }}
                                    </button>
                                </form>
                                <form action="{{ route('cart.remove', $item['product_id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-on-surface-variant hover:text-recovery-berry" type="submit">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="bg-surface-container-low rounded-xl p-12 text-center text-on-surface-variant">
                            {{ __('messages.cart_empty') }}
                        </div>
                    @endforelse
                </div>

                <div class="bg-surface-container-low rounded-xl p-6 space-y-6 h-fit">
                    <h3 class="font-headline-md text-headline-md text-primary">{{ __('messages.cart_summary') }}</h3>
                    <div class="flex justify-between text-on-surface-variant">
                        <span>{{ __('messages.cart_subtotal') }}</span>
                        <span>${{ number_format($totals['subtotal'], 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-on-surface-variant">
                        <span>{{ __('messages.cart_total') }}</span>
                        <span class="font-headline-md text-headline-md text-primary">${{ number_format($totals['total'], 2, ',', '.') }}</span>
                    </div>
                    <a class="block w-full bg-primary text-on-primary px-6 py-3 rounded-full text-center font-label-bold text-label-bold uppercase tracking-widest hover:bg-energy-lime hover:text-primary transition-all" href="{{ route('checkout.index') }}">
                        {{ __('messages.cart_checkout') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
