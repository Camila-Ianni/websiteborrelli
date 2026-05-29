@extends('layouts.store')

@section('title', __('messages.home_title'))

@section('content')
    <section class="hero-gradient pt-section-gap pb-20 relative overflow-hidden">
        <div class="max-w-container-max mx-auto px-margin-desktop grid lg:grid-cols-2 gap-gutter items-center">
            <div class="space-y-8">
                <span class="font-label-bold text-label-bold uppercase tracking-[0.3em] text-on-surface-variant">{{ __('messages.hero_tagline') }}</span>
                <h1 class="font-display-xl text-display-xl text-primary">
                    {{ __('messages.hero_title_main') }}
                    <span class="text-energy-lime">{{ __('messages.hero_title_highlight') }}</span>
                </h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant max-w-xl">{{ __('messages.hero_description') }}</p>
                <div class="flex flex-wrap gap-4">
                    <a class="bg-primary text-on-primary px-8 py-4 rounded-full font-label-bold text-label-bold uppercase tracking-widest hover:bg-energy-lime hover:text-primary transition-all" href="{{ route('catalog.index') }}">
                        {{ __('messages.hero_cta_shop') }}
                    </a>
                    <a class="border border-primary text-primary px-8 py-4 rounded-full font-label-bold text-label-bold uppercase tracking-widest hover:bg-primary hover:text-on-primary transition-all" href="#about">
                        {{ __('messages.hero_cta_learn') }}
                    </a>
                </div>
            </div>
            <div class="relative flex justify-center">
                <img alt="Borrelli hero" class="w-[420px] floating-fruit" src="https://images.unsplash.com/photo-1598449152411-6ecf1c2f7ad1?auto=format&fit=crop&w=600&q=80"/>
                <div class="absolute -bottom-8 right-12 bg-pure-white px-6 py-4 rounded-xl shadow-lg">
                    <p class="font-label-bold text-label-bold text-primary">{{ __('messages.hero_badge_title') }}</p>
                    <p class="font-body-md text-on-surface-variant">{{ __('messages.hero_badge_subtitle') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-section-gap bg-surface">
        <div class="max-w-container-max mx-auto px-margin-desktop space-y-12">
            <div class="flex flex-col lg:flex-row justify-between items-start gap-8">
                <div>
                    <h2 class="font-headline-lg text-headline-lg text-primary">{{ __('messages.featured_title') }}</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">{{ __('messages.featured_subtitle') }}</p>
                </div>
                <a class="font-label-bold text-label-bold uppercase tracking-widest text-primary hover:text-energy-lime transition-colors" href="{{ route('catalog.index') }}">
                    {{ __('messages.featured_cta') }}
                </a>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-gutter">
                @php
                    $accents = ['energy-lime', 'recovery-berry', 'focus-zest'];
                @endphp
                @forelse ($featuredProducts as $product)
                    <x-store.product-card :product="$product" :accent="$accents[$loop->index % 3]" />
                @empty
                    <div class="col-span-3 bg-surface-container-low p-10 rounded-xl text-center text-on-surface-variant">
                        {{ __('messages.no_featured_products') }}
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="about" class="py-section-gap bg-surface-container-low">
        <div class="max-w-container-max mx-auto px-margin-desktop grid lg:grid-cols-2 gap-gutter items-center">
            <div class="space-y-6">
                <span class="font-label-bold text-label-bold uppercase tracking-[0.3em] text-on-surface-variant">{{ __('messages.about_badge') }}</span>
                <h2 class="font-headline-lg text-headline-lg text-primary">{{ __('messages.about_title') }}</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">{{ __('messages.about_description') }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-pure-white rounded-xl p-6 shadow-lg">
                    <p class="font-label-bold text-label-bold text-primary">{{ __('messages.about_stat_1_value') }}</p>
                    <p class="font-body-md text-on-surface-variant">{{ __('messages.about_stat_1_label') }}</p>
                </div>
                <div class="bg-pure-white rounded-xl p-6 shadow-lg">
                    <p class="font-label-bold text-label-bold text-primary">{{ __('messages.about_stat_2_value') }}</p>
                    <p class="font-body-md text-on-surface-variant">{{ __('messages.about_stat_2_label') }}</p>
                </div>
                <div class="bg-pure-white rounded-xl p-6 shadow-lg">
                    <p class="font-label-bold text-label-bold text-primary">{{ __('messages.about_stat_3_value') }}</p>
                    <p class="font-body-md text-on-surface-variant">{{ __('messages.about_stat_3_label') }}</p>
                </div>
                <div class="bg-pure-white rounded-xl p-6 shadow-lg">
                    <p class="font-label-bold text-label-bold text-primary">{{ __('messages.about_stat_4_value') }}</p>
                    <p class="font-body-md text-on-surface-variant">{{ __('messages.about_stat_4_label') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section id="calculator" class="py-section-gap bg-primary text-on-primary">
        <div class="max-w-container-max mx-auto px-margin-desktop grid lg:grid-cols-2 gap-gutter items-center">
            <div class="space-y-6">
                <span class="font-label-bold text-label-bold uppercase tracking-[0.3em] text-energy-lime">{{ __('messages.calculator_badge') }}</span>
                <h2 class="font-headline-lg text-headline-lg">{{ __('messages.calculator_title') }}</h2>
                <p class="font-body-md text-body-md text-on-primary-container">{{ __('messages.calculator_description') }}</p>
            </div>
            <div class="bg-primary-container rounded-xl p-8 space-y-4">
                <label class="block font-label-bold text-label-bold uppercase tracking-widest">{{ __('messages.calculator_weight') }}</label>
                <input class="w-full rounded-full bg-surface text-primary px-6 py-3" placeholder="70 kg" type="text"/>
                <label class="block font-label-bold text-label-bold uppercase tracking-widest">{{ __('messages.calculator_goal') }}</label>
                <select class="w-full rounded-full bg-surface text-primary px-6 py-3">
                    <option>{{ __('messages.calculator_goal_cut') }}</option>
                    <option>{{ __('messages.calculator_goal_bulk') }}</option>
                    <option>{{ __('messages.calculator_goal_recovery') }}</option>
                </select>
                <button class="w-full bg-energy-lime text-primary px-6 py-3 rounded-full font-label-bold text-label-bold uppercase tracking-widest">
                    {{ __('messages.calculator_cta') }}
                </button>
            </div>
        </div>
    </section>
@endsection
