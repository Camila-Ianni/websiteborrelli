<nav class="bg-surface/80 dark:bg-primary/80 backdrop-blur-md border-b border-outline-variant dark:border-primary-container shadow-sm docked full-width top-0 sticky z-50 h-20">
    <div class="flex justify-between items-center w-full px-margin-desktop max-w-container-max mx-auto h-full">
        <a class="font-display-xl-mobile text-headline-md tracking-tighter text-primary dark:text-primary-fixed uppercase" href="{{ route('home') }}">
            {{ __('messages.brand_name') }}
        </a>
        <div class="hidden md:flex gap-gutter items-center">
            <a class="font-label-bold text-label-bold text-on-surface-variant hover:text-energy-lime transition-all duration-300" href="{{ route('catalog.index', ['categories' => 'creatina']) }}">{{ __('messages.nav_creatine') }}</a>
            <a class="font-label-bold text-label-bold text-on-surface-variant hover:text-energy-lime transition-all duration-300" href="{{ route('catalog.index', ['categories' => 'proteina']) }}">{{ __('messages.nav_protein') }}</a>
            <a class="font-label-bold text-label-bold text-on-surface-variant hover:text-energy-lime transition-all duration-300" href="{{ route('catalog.index', ['categories' => 'aminoacidos']) }}">{{ __('messages.nav_amino') }}</a>
            <a class="font-label-bold text-label-bold text-on-surface-variant hover:text-energy-lime transition-all duration-300" href="{{ route('home') }}#calculator">{{ __('messages.nav_calculator') }}</a>
            <a class="font-label-bold text-label-bold text-on-surface-variant hover:text-energy-lime transition-all duration-300" href="{{ route('home') }}#about">{{ __('messages.nav_about') }}</a>
        </div>
        <div class="flex items-center gap-gutter">
            <form action="{{ route('catalog.index') }}" class="relative hidden lg:block">
                <input class="bg-surface-container-low border-none rounded-full px-4 py-2 text-label-sm w-64 focus:ring-2 focus:ring-energy-lime transition-all" name="search" placeholder="{{ __('messages.search_placeholder') }}" type="text"/>
                <span class="material-symbols-outlined absolute right-3 top-2 text-on-surface-variant">search</span>
            </form>
            <a class="relative" href="{{ route('cart.index') }}">
                <span class="material-symbols-outlined text-primary cursor-pointer">shopping_cart</span>
                @if ($cartCount)
                    <span class="absolute -top-2 -right-2 bg-energy-lime text-primary text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                @endif
            </a>
            <div class="hidden md:flex items-center gap-2 text-label-sm font-label-bold">
                <a class="hover:text-energy-lime" href="{{ route('lang.switch', 'es') }}">ES</a>
                <span class="text-outline">/</span>
                <a class="hover:text-energy-lime" href="{{ route('lang.switch', 'en') }}">EN</a>
                <span class="text-outline">/</span>
                <a class="hover:text-energy-lime" href="{{ route('lang.switch', 'ko') }}">KO</a>
            </div>
            <a class="bg-primary-container text-on-primary px-6 py-2 font-label-bold text-label-bold rounded transition-all active:scale-95 duration-150 hover:bg-energy-lime hover:text-primary" href="{{ route('catalog.index') }}">
                {{ __('messages.nav_contact') }}
            </a>
        </div>
    </div>
</nav>
