<nav class="bg-surface/80 backdrop-blur-md border-b border-outline-variant shadow-sm docked full-width top-0 sticky z-50 h-20">
    <div class="flex justify-between items-center w-full px-margin-desktop max-w-container-max mx-auto h-full">
        <div class="font-display-xl-mobile text-headline-md tracking-tighter text-primary uppercase">ELITE LABS</div>

        <div class="hidden md:flex gap-gutter items-center">
            <a class="font-label-bold text-label-bold {{ request()->routeIs('home') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-energy-lime transition-all duration-300' }}" href="{{ route('home') }}">{{ __('messages.home') }}</a>
            <a class="font-label-bold text-label-bold {{ request()->routeIs('catalog.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-energy-lime transition-all duration-300' }}" href="{{ route('catalog.index') }}">{{ __('messages.catalog') }}</a>
            <a class="font-label-bold text-label-bold text-on-surface-variant hover:text-energy-lime transition-all duration-300" href="#">About Us</a>
            <a class="font-label-bold text-label-bold text-on-surface-variant hover:text-energy-lime transition-all duration-300" href="#">Contact</a>
        </div>

        <div class="flex items-center gap-6">
            <form action="{{ route('catalog.index') }}" method="GET" class="relative hidden lg:block">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-body-md">search</span>
                <input class="pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-full text-body-md focus:ring-2 focus:ring-energy-lime transition-all" placeholder="{{ __('messages.search') }}" type="text" name="search" value="{{ request('search') }}"/>
            </form>

            <div class="relative group">
                <button class="flex items-center gap-1 font-label-bold text-label-bold text-on-surface-variant hover:text-energy-lime transition-all">
                    @if(app()->getLocale() == 'es') 🇪🇸
                    @elseif(app()->getLocale() == 'en') 🇺🇸
                    @else 🇰🇷
                    @endif
                </button>
                <div class="absolute right-0 top-full mt-2 bg-surface-container-lowest border border-outline-variant shadow-[0px_32px_32px_rgba(32,42,54,0.06)] hidden group-hover:block z-50 rounded-lg overflow-hidden">
                    <a href="{{ route('lang.switch', 'es') }}" class="block px-4 py-2 hover:bg-surface-container-low {{ app()->getLocale() == 'es' ? 'bg-energy-lime text-primary' : '' }}">🇪🇸 Español</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 hover:bg-surface-container-low {{ app()->getLocale() == 'en' ? 'bg-energy-lime text-primary' : '' }}">🇺🇸 English</a>
                    <a href="{{ route('lang.switch', 'ko') }}" class="block px-4 py-2 hover:bg-surface-container-low {{ app()->getLocale() == 'ko' ? 'bg-energy-lime text-primary' : '' }}">🇰🇷 한국어</a>
                </div>
            </div>

            @auth
                <span class="text-label-sm font-label-sm text-on-surface-variant hidden lg:block">{{ auth()->user()->name }}</span>
                @if(auth()->user()->is_admin)
                <a href="{{ route('admin.comics.index') }}" class="bg-primary-container text-energy-lime px-6 py-2 rounded-full font-label-bold text-label-bold hover:bg-energy-lime hover:text-primary transition-all active:scale-95 duration-150">
                    {{ __('messages.admin') }}
                </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-recovery-berry text-pure-white px-6 py-2 rounded-full font-label-bold text-label-bold hover:brightness-110 transition-all">
                        {{ __('messages.logout') }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="bg-primary text-pure-white px-6 py-2 rounded-full font-label-bold text-label-bold hover:bg-energy-lime hover:text-primary transition-all active:scale-95 duration-150">
                    {{ __('messages.login') }}
                </a>
            @endauth

            <a href="{{ route('cart.index') }}" class="relative text-primary hover:text-energy-lime transition-colors">
                <span class="material-symbols-outlined text-3xl">shopping_cart</span>
                @if(session('cart') && count(session('cart', [])) > 0)
                    <span class="absolute -top-2 -right-2 bg-energy-lime text-primary font-black text-xs rounded-full w-5 h-5 flex items-center justify-center">
                        {{ array_sum(array_column(session('cart', []), 'quantity')) }}
                    </span>
                @endif
            </a>
        </div>
    </div>
</nav>
