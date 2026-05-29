@extends('layouts.store')

@section('title', __('messages.checkout_failure_title'))

@section('content')
    <section class="py-section-gap">
        <div class="max-w-container-max mx-auto px-margin-desktop text-center">
            <div class="bg-surface-container-low rounded-xl p-10 space-y-6">
                <h1 class="font-headline-lg text-headline-lg text-primary">{{ __('messages.checkout_failure_heading') }}</h1>
                <p class="font-body-md text-body-md text-on-surface-variant">{{ __('messages.checkout_failure_message') }}</p>
                <a class="inline-block bg-primary text-on-primary px-8 py-3 rounded-full font-label-bold text-label-bold uppercase tracking-widest" href="{{ route('checkout.index') }}">
                    {{ __('messages.try_again') }}
                </a>
            </div>
        </div>
    </section>
@endsection
