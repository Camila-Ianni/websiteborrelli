@extends('layouts.app')

@section('title', __('messages.payment_pending') . ' - El Túnel del Cómic')

@section('content')
<div class="pt-24 min-h-screen bg-background text-on-background">
    <div class="max-w-container-max mx-auto px-margin-desktop py-section-gap text-center">
        
        <!-- Pending Icon -->
        <div class="w-24 h-24 mx-auto mb-8 bg-focus-zest flex items-center justify-center rounded-full">
            <span class="material-symbols-outlined text-pure-white text-5xl">hourglass_top</span>
        </div>

        <h1 class="text-5xl font-display-xl-mobile font-black uppercase mb-4 text-focus-zest">
            {{ __('messages.payment_pending') }}
        </h1>
        
        <p class="text-xl text-on-surface-variant mb-8">
            {{ __('messages.payment_pending_description') }}
        </p>

        <!-- Order Details -->
        <div class="bg-surface-container-lowest border border-outline-variant p-8 text-left mb-8 rounded-xl shadow-[0px_32px_32px_rgba(32,42,54,0.04)]">
            <h2 class="font-display-xl-mobile font-black uppercase text-xl mb-4 text-primary">{{ __('messages.order_details') }}</h2>
            
            <div class="space-y-2 text-on-surface-variant">
                <p><strong>{{ __('messages.order_number') }}:</strong> {{ $order->order_number }}</p>
                <p><strong>{{ __('messages.customer') }}:</strong> {{ $order->customer_name }}</p>
                <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                <p><strong>{{ __('messages.total') }}:</strong> ${{ number_format($order->total, 2) }}</p>
                <p><strong>{{ __('messages.status') }}:</strong> 
                    <span class="px-2 py-1 bg-surface-container-low text-focus-zest font-bold text-sm rounded-full">{{ __('messages.pending') }}</span>
                </p>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-surface-container-low border border-outline-variant p-6 text-left mb-8 rounded-xl">
            <div class="flex items-start gap-4">
                <span class="material-symbols-outlined text-focus-zest text-2xl">info</span>
                <div>
                    <h3 class="font-bold text-primary mb-2">{{ __('messages.what_now') }}</h3>
                    <ul class="text-sm text-on-surface-variant space-y-2">
                        <li>• {{ __('messages.pending_note_1') }}</li>
                        <li>• {{ __('messages.pending_note_2') }}</li>
                        <li>• {{ __('messages.pending_note_3') }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="space-y-4">
            <a href="{{ route('home') }}" class="block bg-energy-lime text-primary py-4 font-label-bold uppercase rounded-lg hover:brightness-110 transition-all">
                {{ __('messages.back_to_home') }}
            </a>
        </div>
    </div>
</div>
@endsection
