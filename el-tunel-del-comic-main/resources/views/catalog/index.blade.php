@extends('layouts.app')

@section('title', 'Catálogo - El Túnel del Cómic')

@section('content')
<main class="pt-24 min-h-screen flex flex-col md:flex-row bg-background text-on-background">
    <!-- Sidebar Filters -->
    <aside class="w-full md:w-80 bg-surface-container-lowest border-r border-outline-variant shadow-[32px_0_32px_rgba(32,42,54,0.04)]">
        <div class="p-8 border-b border-outline-variant bg-primary-container relative overflow-hidden">
            <div class="absolute inset-0 bg-white/5"></div>
            <div class="relative z-10">
                <h2 class="text-xl font-black text-pure-white font-display-xl-mobile uppercase tracking-widest">FILTROS</h2>
                <p class="font-label-bold text-label-bold uppercase tracking-widest text-energy-lime">Refinar Búsqueda</p>
            </div>
        </div>
        
        <form id="filterForm" action="{{ route('catalog.index') }}" method="GET" class="p-8 space-y-10 max-h-screen overflow-y-auto custom-scrollbar">
            <!-- Search -->
            <section>
                <h3 class="font-label-bold text-label-bold uppercase tracking-[0.2em] mb-4 text-primary">Buscar</h3>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Título o descripción..."
                    class="w-full px-4 py-3 border border-outline-variant bg-surface-container-lowest rounded-lg font-bold focus:outline-none focus:border-energy-lime"/>
            </section>

            <!-- Publisher Filter -->
            <section>
                <h3 class="font-label-bold text-label-bold uppercase tracking-[0.2em] mb-6 text-primary">Editorial</h3>
                <div class="space-y-3 max-h-48 overflow-y-auto">
                    @foreach($publishers as $publisher)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="publishers[]" value="{{ $publisher->id }}" 
                            {{ in_array($publisher->id, request('publishers', [])) ? 'checked' : '' }}
                            class="filter-checkbox w-5 h-5 border-2 border-gray-400 rounded-none accent-primary focus:ring-0"/>
                        <span class="font-label-bold text-label-bold uppercase text-sm tracking-tight group-hover:text-energy-lime transition-colors">
                            {{ $publisher->name }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </section>

            <!-- Category Filter -->
            <section>
                <h3 class="font-label-bold text-label-bold uppercase tracking-[0.2em] mb-6 text-primary">Categorías</h3>
                <div class="space-y-3 max-h-48 overflow-y-auto">
                    @foreach($categories as $category)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                            {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}
                            class="filter-checkbox w-5 h-5 border-2 border-gray-400 rounded-none accent-primary focus:ring-0"/>
                        <span class="font-label-bold text-label-bold uppercase text-sm tracking-tight group-hover:text-energy-lime transition-colors">
                            {{ $category->name }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </section>

            <!-- Price Range -->
            <section>
                <h3 class="font-label-bold text-label-bold uppercase tracking-[0.2em] mb-6 text-primary">Rango de Precio</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="price_range" value="" {{ !request('price_range') ? 'checked' : '' }} class="filter-checkbox w-5 h-5 accent-primary"/>
                        <span class="font-label-bold text-label-bold uppercase text-sm">Todos</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="price_range" value="0-20" {{ request('price_range') == '0-20' ? 'checked' : '' }} class="filter-checkbox w-5 h-5 accent-primary"/>
                        <span class="font-label-bold text-label-bold uppercase text-sm">$0 - $20</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="price_range" value="20-40" {{ request('price_range') == '20-40' ? 'checked' : '' }} class="filter-checkbox w-5 h-5 accent-primary"/>
                        <span class="font-label-bold text-label-bold uppercase text-sm">$20 - $40</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="price_range" value="40-60" {{ request('price_range') == '40-60' ? 'checked' : '' }} class="filter-checkbox w-5 h-5 accent-primary"/>
                        <span class="font-label-bold text-label-bold uppercase text-sm">$40 - $60</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="price_range" value="60+" {{ request('price_range') == '60+' ? 'checked' : '' }} class="filter-checkbox w-5 h-5 accent-primary"/>
                        <span class="font-label-bold text-label-bold uppercase text-sm">$60+</span>
                    </label>
                </div>
            </section>

            <!-- Sort -->
            <section>
                <h3 class="font-label-bold text-label-bold uppercase tracking-[0.2em] mb-4 text-primary">Ordenar Por</h3>
                <select name="sort" class="w-full px-4 py-3 border border-outline-variant rounded-lg bg-surface-container-lowest font-bold focus:outline-none focus:border-energy-lime filter-checkbox">
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Más Recientes</option>
                    <option value="price" {{ request('sort') == 'price' && request('order') != 'desc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Título A-Z</option>
                </select>
                <input type="hidden" name="order" value="{{ request('order', 'desc') }}"/>
            </section>

            <div class="space-y-3">
                <button type="submit" class="w-full py-4 bg-primary text-pure-white font-black uppercase tracking-widest text-xs hover:bg-energy-lime hover:text-primary transition-all rounded-lg">
                    Aplicar Filtros
                </button>
                <a href="{{ route('catalog.index') }}" class="block w-full py-4 bg-surface-container-high text-primary font-black uppercase tracking-widest text-xs text-center rounded-lg hover:bg-energy-lime hover:text-primary transition-all">
                    Limpiar Filtros
                </a>
            </div>
        </form>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 p-8 lg:p-margin-desktop">
        <div class="mb-12 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-display-xl-mobile text-headline-lg text-primary uppercase mb-2">
                    {{ request('search') ? 'Resultados' : 'Catálogo' }}
                </h2>
                <p class="text-sm text-on-surface-variant font-label-bold" id="resultsCount">{{ $comics->total() }} comics encontrados</p>
            </div>
            
            @if(request()->hasAny(['search', 'publishers', 'categories', 'price_range']))
            <div class="flex flex-wrap gap-2">
                @if(request('search'))
                <span class="bg-energy-lime text-primary px-3 py-1 font-bold text-sm rounded-full">
                    "{{ request('search') }}"
                    <a href="{{ route('catalog.index', request()->except('search')) }}" class="ml-2">&times;</a>
                </span>
                @endif
                @if(request('price_range'))
                <span class="bg-energy-lime text-primary px-3 py-1 font-bold text-sm rounded-full">
                    ${{ request('price_range') }}
                    <a href="{{ route('catalog.index', request()->except('price_range')) }}" class="ml-2">&times;</a>
                </span>
                @endif
            </div>
            @endif
        </div>

        <!-- Comics Grid -->
        <div id="comicsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @include('catalog._comics_grid')
        </div>

        <!-- Pagination -->
        <div class="mt-12" id="pagination">
            {{ $comics->links() }}
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit on filter change (optional - can be removed for manual submit only)
    const filterCheckboxes = document.querySelectorAll('.filter-checkbox');
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Optional: Add a small delay for better UX
            // document.getElementById('filterForm').submit();
        });
    });
});
</script>
@endsection
