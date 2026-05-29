@extends('layouts.app')

@section('title', __('messages.payment_failed') . ' - El Túnel del Cómic')

@section('content')
<div class="pt-24 min-h-screen bg-background text-on-background">
    <div class="max-w-container-max mx-auto px-margin-desktop py-section-gap text-center">
        
        <!-- Error Icon -->
        <div class="w-24 h-24 mx-auto mb-8 bg-recovery-berry flex items-center justify-center rounded-full">
            <span class="material-symbols-outlined text-pure-white text-5xl">error</span>
        </div>

        <h1 class="text-5xl font-display-xl-mobile font-black uppercase mb-4 text-recovery-berry">
            {{ __('messages.payment_failed') }}
        </h1>
        
        <p class="text-xl text-on-surface-variant mb-8">
            {{ __('messages.payment_failed_description') }}
        </p>

        <!-- Order Details -->
        <div class="bg-surface-container-lowest border border-outline-variant p-8 text-left mb-8 rounded-xl shadow-[0px_32px_32px_rgba(32,42,54,0.04)]">
            <h2 class="font-display-xl-mobile font-black uppercase text-xl mb-4 text-primary">{{ __('messages.order_details') }}</h2>
            
            <div class="space-y-2 text-on-surface-variant">
                <p><strong>{{ __('messages.order_number') }}:</strong> {{ $order->order_number }}</p>
                <p><strong>{{ __('messages.total') }}:</strong> ${{ number_format($order->total, 2) }}</p>
                <p><strong>{{ __('messages.status') }}:</strong> 
                    <span class="px-2 py-1 bg-surface-container-low text-recovery-berry font-bold text-sm rounded-full">{{ __('messages.pending') }}</span>
                </p>
            </div>
        </div>

        <!-- Options -->
        <div class="space-y-4">
            <p class="text-on-surface-variant mb-4">{{ __('messages.retry_payment_note') }}</p>
            
            <a href="{{ route('checkout.show') }}" class="block bg-energy-lime text-primary py-4 font-label-bold uppercase rounded-lg hover:brightness-110 transition-all">
                {{ __('messages.retry_payment') }}
            </a>
            
            <p class="text-sm text-on-surface-variant my-4">{{ __('messages.or') }}</p>
            
            <a href="{{ route('checkout.success', $order) }}" class="block bg-primary text-pure-white py-4 font-label-bold uppercase rounded-lg hover:bg-energy-lime hover:text-primary transition-all">
                {{ __('messages.pay_by_transfer') }}
            </a>
            
            <a href="{{ route('home') }}" class="block text-on-surface-variant hover:text-energy-lime mt-4">
                ← {{ __('messages.back_to_home') }}
            </a>
        </div>
    </div>
</div>
@endsection
