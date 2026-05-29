@extends('layouts.store')

@section('title', __('messages.transfer_title'))

@section('content')
    <section class="py-section-gap">
        <div class="max-w-container-max mx-auto px-margin-desktop">
            <div class="bg-surface-container-low rounded-xl p-10 text-center space-y-6">
                <h1 class="font-headline-lg text-headline-lg text-primary">{{ __('messages.transfer_heading') }}</h1>
                <p class="font-body-md text-body-md text-on-surface-variant">{{ __('messages.transfer_subheading', ['order' => $order->order_number]) }}</p>
                <div class="bg-surface rounded-xl p-6 text-left space-y-2 max-w-xl mx-auto">
                    <p><strong>{{ __('messages.transfer_bank') }}</strong> Borrelli Nutrition</p>
                    <p><strong>{{ __('messages.transfer_cbu') }}</strong> 0000003100051234567890</p>
                    <p><strong>{{ __('messages.transfer_alias') }}</strong> BORRELLI.SUPER</p>
                    <p><strong>{{ __('messages.transfer_holder') }}</strong> Borrelli Labs SA</p>
                </div>
                <a class="inline-block bg-primary text-on-primary px-8 py-3 rounded-full font-label-bold text-label-bold uppercase tracking-widest" href="{{ route('home') }}">
                    {{ __('messages.back_home') }}
                </a>
            </div>
        </div>
    </section>
@endsection
