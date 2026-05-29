@extends('layouts.app')

@section('title', 'Shopping Cart - El Túnel del Cómic')

@section('content')
<div class="pt-24 min-h-screen bg-background text-on-background">
    <div class="max-w-container-max mx-auto px-margin-desktop py-section-gap">
        <div class="flex items-end gap-4 mb-12">
            <h1 class="font-headline-lg text-headline-lg uppercase text-primary">Your Cart</h1>
            <span class="text-on-surface-variant font-label-bold mb-2">({{ $totalItems ?? 0 }} ITEMS)</span>
        </div>

        @if($cartItems && count($cartItems) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($cartItems as $item)
                <div class="bg-surface-container-lowest p-6 rounded-xl flex flex-col md:flex-row gap-6 shadow-[0px_32px_32px_rgba(32,42,54,0.04)] hover:shadow-[0px_32px_32px_rgba(32,42,54,0.12)] transition-all group">
                    <div class="w-full md:w-32 h-32 bg-surface-container rounded-lg flex items-center justify-center overflow-hidden shrink-0">
                        @if($item['comic']->image_path)
                            <img class="w-24 h-24 object-contain cart-item-image group-hover:scale-110 transition-transform duration-500" src="{{ asset('storage/' . $item['comic']->image_path) }}" alt="{{ $item['comic']->title }}"/>
                        @else
                            <div class="w-full h-full bg-surface-container-low flex items-center justify-center">
                                <span class="text-outline-variant font-black text-3xl">{{ strtoupper(substr($item['comic']->title, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-grow flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-headline-md text-headline-md text-primary uppercase">{{ $item['comic']->title }}</h3>
                                <p class="text-on-surface-variant text-label-sm uppercase tracking-widest mt-1">{{ $item['comic']->publisher->name }}</p>
                            </div>
                            <button class="text-on-surface-variant hover:text-recovery-berry transition-colors" type="submit" form="remove-{{ $item['comic']->id }}">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </div>
                        
                        <div class="flex justify-between items-end mt-4">
                            <form action="{{ route('cart.update', $item['comic']) }}" method="POST" class="flex items-center bg-surface-container rounded-full px-2 py-1">
                                @csrf
                                @method('PATCH')
                                <button class="w-8 h-8 flex items-center justify-center hover:bg-surface-container-highest rounded-full transition-colors" type="button" onclick="this.parentElement.querySelector('input').stepDown(); this.parentElement.submit();"><span class="material-symbols-outlined text-[16px]">remove</span></button>
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['comic']->stock }}" class="px-4 font-label-bold w-16 text-center bg-transparent border-none focus:ring-0" onchange="this.form.submit()"/>
                                <button class="w-8 h-8 flex items-center justify-center hover:bg-surface-container-highest rounded-full transition-colors" type="button" onclick="this.parentElement.querySelector('input').stepUp(); this.parentElement.submit();"><span class="material-symbols-outlined text-[16px]">add</span></button>
                            </form>

                            <span class="font-headline-md text-headline-md text-primary">${{ number_format($item['comic']->price * $item['quantity'], 2) }}</span>
                        </div>
                    </div>
                </div>
                <form id="remove-{{ $item['comic']->id }}" action="{{ route('cart.remove', $item['comic']) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-primary-container text-pure-white p-8 sticky top-24 rounded-xl shadow-[0px_32px_32px_rgba(32,42,54,0.06)]">
                    <h2 class="font-headline-md text-headline-md uppercase mb-8 border-b border-on-primary-container pb-4">Order Summary</h2>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between font-body-md text-on-primary-container">
                            <span>Subtotal</span>
                            <span class="font-black text-pure-white">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-body-md text-on-primary-container">
                            <span>Shipping</span>
                            <span class="text-energy-lime font-label-bold uppercase">FREE</span>
                        </div>
                        <div class="flex justify-between font-body-md text-on-primary-container">
                            <span>Tax</span>
                            <span class="font-black text-pure-white">$0.00</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-end mb-8">
                        <span class="font-label-bold text-on-primary-container">Total Amount</span>
                        <span class="font-display-xl-mobile text-headline-lg text-energy-lime">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    
                    <a href="{{ route('checkout.show') }}" class="w-full bg-energy-lime text-primary py-4 font-label-bold text-label-bold rounded-lg flex items-center justify-center gap-3 active:scale-95 duration-150 transition-all hover:bg-pure-white group block text-center">
                        PROCEED TO CHECKOUT
                        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                    
                    <div class="mt-8 flex items-center justify-center gap-4 opacity-50">
                        <span class="material-symbols-outlined text-headline-md">verified_user</span>
                        <p class="text-label-sm">Secure checkout powered by ELITE LABS SSL</p>
                    </div>
                </div>

                <div class="mt-8 bg-surface-container-low p-6 rounded-xl border border-outline-variant">
                    <p class="font-label-bold mb-3 uppercase tracking-tighter">HAVE A PROMO CODE?</p>
                    <div class="flex gap-2">
                        <input class="flex-grow bg-surface border-none rounded-lg px-4 py-2 text-body-md focus:ring-2 focus:ring-primary" placeholder="CODE10" type="text"/>
                        <button class="bg-primary text-pure-white px-4 rounded-lg font-label-bold text-label-sm hover:bg-on-primary-container transition-colors">APPLY</button>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-20 text-center shadow-[0px_32px_32px_rgba(32,42,54,0.04)]">
            <span class="material-symbols-outlined text-9xl text-outline-variant mb-6">shopping_cart</span>
            <h2 class="font-display-xl-mobile font-black text-3xl uppercase mb-4 text-primary">Your cart is empty</h2>
            <p class="text-on-surface-variant mb-8">Start adding some amazing comics to your collection!</p>
            <a href="{{ route('catalog.index') }}" class="inline-block bg-primary text-pure-white px-12 py-4 font-label-bold uppercase rounded-lg hover:bg-energy-lime hover:text-primary transition-all">
                Browse Catalog
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
