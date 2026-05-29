@extends('layouts.app')

@section('title', 'El Túnel del Cómic - Professional Anime & Manga Store')

@section('content')
<!-- Hero Section -->
<header class="relative min-h-screen flex items-center pt-20 overflow-hidden hero-gradient">
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(212,255,0,0.12)_0%,rgba(249,249,249,0.92)_45%,rgba(238,238,238,0.98)_100%)]"></div>
        <img alt="Comic Splash" class="w-full h-full object-cover opacity-20 mix-blend-multiply scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD233ttSBhPR0DQyu7dEq3AppJgzAJAMDqRscX-51ase4gW2yUxGrqXTkyDt_11eqAgte-UPcnOf2qCmTRgxjjqIA1N1TlELWfg5qBwe1QVJ858PgKLZhQ9AfY6sfvY_jvP3nV8WMgSTBHl8nxikxvkuLSB9XFf1yUbKOyIUlf7ypypbHX9uH5WHly_d1Jg3jg_ypeY-gJOHsYkZZksfZT2EhMfKSN2gdxYS66BKx8qBwr6i6R63xfFHVQzBBB6J8YoVfKaxR1X2NJ7"/>
    </div>
    <div class="container mx-auto px-6 relative z-10 flex flex-col items-center text-center lg:items-start lg:text-left">
        <div class="inline-block bg-energy-lime text-primary px-4 py-1 font-label-bold text-label-bold uppercase text-sm mb-4 rounded-full">
            Premium Archive
        </div>
        <h1 class="text-7xl md:text-[10rem] font-display-xl font-black text-primary leading-[0.8] mb-8">
            ENTER THE <br/>
            <span class="text-primary relative inline-block">
                MULTIVERSE
                <span class="absolute -bottom-4 left-0 w-full h-2 bg-energy-lime rounded-full"></span>
            </span>
        </h1>
        <p class="max-w-xl text-on-surface-variant font-label-bold uppercase tracking-widest mb-10 text-lg border-l-4 border-energy-lime pl-6">
            High-end collectibles, limited runs, and the finest graphic storytelling on the planet.
        </p>
        <div class="flex flex-wrap gap-6">
            <a href="{{ route('catalog.index') }}" class="bg-primary text-pure-white px-12 py-5 font-label-bold uppercase text-2xl hover:bg-energy-lime hover:text-primary transition-all rounded-lg shadow-[0px_32px_32px_rgba(32,42,54,0.08)]">
                The Archive
            </a>
            <a href="{{ route('catalog.index') }}" class="bg-transparent text-primary px-12 py-5 font-label-bold uppercase text-2xl hover:bg-surface-container-low transition-all border border-outline-variant rounded-lg">
                Scanner
            </a>
        </div>
    </div>
</header>

<!-- Editorial Categories - Manga Panel Style -->
<section class="py-12 bg-surface-container-low overflow-hidden">
    <div class="grid grid-cols-2 md:grid-cols-6 h-[400px] gap-2 p-2">
        @foreach($publishers->take(6) as $index => $publisher)
        <div class="group relative overflow-hidden cursor-pointer transform hover:scale-105 transition-all duration-500 z-10 rounded-xl border border-outline-variant {{ $index == 0 ? 'bg-red-600' : '' }} {{ $index == 1 ? '-skew-y-3 md:-skew-y-0 md:skew-x-2 bg-blue-700' : '' }} {{ $index == 2 ? 'bg-emerald-700' : '' }} {{ $index == 3 ? 'skew-x-2 bg-primary border-black' : '' }} {{ $index == 4 ? '-skew-x-2 bg-orange-600' : '' }} {{ $index == 5 ? 'bg-indigo-600' : '' }}">
            <div class="absolute inset-0 {{ $index == 3 ? 'bg-primary opacity-80' : 'grayscale' }} group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700"></div>
            @if($index == 3)
            <div class="absolute inset-0 halftone-bg opacity-20"></div>
            @endif
            <div class="absolute bottom-4 left-4">
                <h3 class="{{ $index == 3 ? 'text-primary' : 'text-pure-white' }} font-display-xl-mobile font-black text-2xl md:text-4xl group-hover:rotate-0 transition-transform uppercase">
                    {{ $publisher->name }}
                </h3>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- New Arrivals - Collectible Cards -->
<section class="py-24 bg-surface relative">
    <div class="absolute top-0 right-0 w-64 h-64 bg-energy-lime/10 rounded-full blur-3xl"></div>
    <div class="container mx-auto px-6">
        <div class="flex items-end justify-between mb-16 gap-8">
            <h2 class="text-6xl font-display-xl-mobile font-black uppercase border-b-8 border-energy-lime pb-2 text-primary">New Drops</h2>
            <div class="flex-grow h-px bg-outline-variant mb-4"></div>
            <a href="{{ route('catalog.index') }}" class="font-label-bold text-label-bold text-primary text-xl hover:text-energy-lime">VIEW ALL //</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            @foreach($newReleases->take(4) as $comic)
            <div class="collectible-card bg-surface-container-lowest group p-4 rounded-xl border border-outline-variant shadow-[0px_32px_32px_rgba(32,42,54,0.04)]">
                <div class="aspect-[2/3] relative overflow-hidden mb-6 rounded-lg border border-outline-variant">
                    @if($comic->image_path)
                        <img class="w-full h-full object-cover group-hover:scale-110 transition-all duration-500" src="{{ asset('storage/' . $comic->image_path) }}" alt="{{ $comic->title }}"/>
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400 font-black text-4xl">{{ strtoupper(substr($comic->title, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4">
                        <div class="flex gap-2">
                            <form action="{{ route('cart.add', $comic) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-energy-lime text-primary p-3 rounded-lg hover:brightness-110 transition-colors">
                                    <span class="material-symbols-outlined">shopping_cart</span>
                                </button>
                            </form>
                            <a href="{{ route('catalog.show', $comic) }}" class="bg-surface-container-lowest text-primary p-3 rounded-lg hover:bg-energy-lime transition-colors">
                                <span class="material-symbols-outlined">visibility</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div>
                    <span class="bg-primary-container text-energy-lime text-[10px] font-black px-2 py-0.5 mb-2 inline-block rounded-full">{{ strtoupper($comic->publisher->name) }}</span>
                    <h3 class="font-display-xl-mobile font-black text-xl uppercase leading-tight mb-2 text-primary">{{ $comic->title }}</h3>
                    <p class="text-2xl font-black text-primary">${{ number_format($comic->price, 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Comics -->
<section class="py-24 bg-primary">
    <div class="container mx-auto px-6">
        <div class="flex items-end justify-between mb-16 gap-8">
            <h2 class="text-6xl font-display-xl-mobile font-black uppercase text-pure-white border-b-8 border-energy-lime pb-2">Featured</h2>
            <div class="flex-grow h-px bg-white/20 mb-4"></div>
            <a href="{{ route('catalog.index') }}" class="font-label-bold text-label-bold text-energy-lime text-xl hover:text-pure-white">EXPLORE //</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featured->take(3) as $comic)
            <div class="group relative overflow-hidden border border-white/20 hover:border-energy-lime transition-all cursor-pointer rounded-xl">
                <div class="aspect-[16/9] overflow-hidden">
                    @if($comic->image_path)
                        <img class="w-full h-full object-cover group-hover:scale-110 transition-all duration-500" src="{{ asset('storage/' . $comic->image_path) }}" alt="{{ $comic->title }}"/>
                    @else
                        <div class="w-full h-full bg-gray-700 flex items-center justify-center">
                            <span class="text-white font-black text-6xl">{{ strtoupper(substr($comic->title, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-primary to-transparent p-6">
                    <h3 class="text-pure-white font-display-xl-mobile font-black text-2xl uppercase mb-2">{{ $comic->title }}</h3>
                    <p class="text-energy-lime font-black text-xl">${{ number_format($comic->price, 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-32 bg-surface-container-low relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(212,255,0,0.12)_0%,transparent_60%)]"></div>
    <div class="container mx-auto px-6 relative z-10 text-center">
        <h2 class="text-7xl font-display-xl-mobile font-black uppercase mb-8 text-primary">
            JOIN THE ARCHIVE
        </h2>
        <p class="text-2xl font-label-bold mb-12 text-on-surface-variant max-w-2xl mx-auto">
            Subscribe to get exclusive drops, early access, and insider intel on limited editions.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-xl mx-auto">
            <input type="email" placeholder="YOUR EMAIL" class="flex-1 px-6 py-4 border border-outline-variant rounded-lg bg-surface-container-lowest font-black uppercase focus:outline-none focus:ring-2 focus:ring-energy-lime"/>
            <button class="bg-primary text-pure-white px-12 py-4 font-label-bold uppercase rounded-lg hover:bg-energy-lime hover:text-primary transition-all">
                SUBSCRIBE
            </button>
        </div>
    </div>
</section>
@endsection