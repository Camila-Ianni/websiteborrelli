@extends('layouts.store')

@section('title', __('messages.checkout_title'))

@section('content')
    <section class="py-section-gap">
        <div class="max-w-container-max mx-auto px-margin-desktop">
            <div class="flex flex-col lg:flex-row justify-between items-start gap-8 mb-12">
                <div>
                    <h1 class="font-headline-lg text-headline-lg text-primary">{{ __('messages.checkout_heading') }}</h1>
                    <p class="font-body-md text-body-md text-on-surface-variant">{{ __('messages.checkout_subheading') }}</p>
                </div>
            </div>

            <div class="grid lg:grid-cols-[1fr_360px] gap-gutter">
                <form class="bg-surface-container-low rounded-xl p-6 space-y-6" action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-label-bold text-label-bold uppercase tracking-widest text-primary mb-2">{{ __('messages.customer_name') }}</label>
                            <input class="w-full rounded-full bg-surface px-4 py-2 border-outline-variant" name="customer_name" required value="{{ old('customer_name') }}">
                        </div>
                        <div>
                            <label class="block font-label-bold text-label-bold uppercase tracking-widest text-primary mb-2">{{ __('messages.customer_dni') }}</label>
                            <input class="w-full rounded-full bg-surface px-4 py-2 border-outline-variant" name="customer_dni" required value="{{ old('customer_dni') }}">
                        </div>
                        <div>
                            <label class="block font-label-bold text-label-bold uppercase tracking-widest text-primary mb-2">{{ __('messages.customer_email') }}</label>
                            <input class="w-full rounded-full bg-surface px-4 py-2 border-outline-variant" name="customer_email" type="email" required value="{{ old('customer_email') }}">
                        </div>
                        <div>
                            <label class="block font-label-bold text-label-bold uppercase tracking-widest text-primary mb-2">{{ __('messages.customer_phone') }}</label>
                            <input class="w-full rounded-full bg-surface px-4 py-2 border-outline-variant" name="customer_phone" required value="{{ old('customer_phone') }}">
                        </div>
                    </div>
                    <div>
                        <label class="block font-label-bold text-label-bold uppercase tracking-widest text-primary mb-2">{{ __('messages.payment_method') }}</label>
                        <select class="w-full rounded-full bg-surface px-4 py-2 border-outline-variant" name="payment_method" required>
                            <option value="mercadopago" @selected(old('payment_method') === 'mercadopago')>{{ __('messages.payment_mercadopago') }}</option>
                            <option value="paypal" @selected(old('payment_method') === 'paypal')>{{ __('messages.payment_paypal') }}</option>
                            <option value="transfer" @selected(old('payment_method') === 'transfer')>{{ __('messages.payment_transfer') }}</option>
                        </select>
                    </div>
                    <button class="w-full bg-primary text-on-primary px-6 py-3 rounded-full font-label-bold text-label-bold uppercase tracking-widest hover:bg-energy-lime hover:text-primary transition-all">
                        {{ __('messages.checkout_confirm') }}
                    </button>
                </form>

                <div class="bg-surface-container-low rounded-xl p-6 space-y-6 h-fit">
                    <h3 class="font-headline-md text-headline-md text-primary">{{ __('messages.order_summary') }}</h3>
                    <div class="space-y-4">
                        @foreach ($cart['items'] as $item)
                            <div class="flex justify-between text-on-surface-variant">
                                <span>{{ $item['title'] }} x{{ $item['quantity'] }}</span>
                                <span>${{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between text-on-surface-variant">
                        <span>{{ __('messages.cart_subtotal') }}</span>
                        <span>${{ number_format($totals['subtotal'], 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-on-surface-variant">
                        <span>{{ __('messages.cart_total') }}</span>
                        <span class="font-headline-md text-headline-md text-primary">${{ number_format($totals['total'], 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
