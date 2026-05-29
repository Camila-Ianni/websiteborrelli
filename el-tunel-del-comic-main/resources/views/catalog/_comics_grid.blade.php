@forelse($comics as $comic)
<div class="product-card-hover group relative bg-pure-white p-6 rounded-xl shadow-[0_32px_32px_-8px_rgba(32,42,54,0.04)] hover:shadow-[0_32px_32px_-4px_rgba(32,42,54,0.12)] transition-all duration-500 overflow-hidden border border-outline-variant">
    @if($comic->is_new_release)
        <div class="absolute top-4 right-4 z-10">
            <span class="bg-energy-lime text-primary px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter">New</span>
        </div>
    @endif

    <a href="{{ route('catalog.show', $comic) }}" class="block">
        <div class="relative h-64 mb-6 flex items-center justify-center bg-surface-container-low rounded-lg overflow-hidden">
            @if($comic->image_path)
                <img class="product-image h-full w-full object-cover transition-transform duration-500 z-0" 
                    src="{{ asset('storage/' . $comic->image_path) }}" 
                    alt="{{ $comic->title }}"/>
            @else
                <div class="w-full h-full bg-surface-container-low flex items-center justify-center">
                    <span class="text-outline-variant font-black text-6xl">{{ strtoupper(substr($comic->title, 0, 1)) }}</span>
                </div>
            @endif
            <div class="absolute inset-0 bg-primary/90 opacity-0 transform translate-y-4 transition-all duration-300 flex flex-col items-center justify-center gap-4 rounded-lg px-6 text-center add-to-cart-overlay">
                <p class="text-pure-white font-label-bold text-label-bold">{{ $comic->publisher->name }}</p>
                <div class="flex gap-3">
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
    </a>

    <div class="space-y-2">
        <div class="flex justify-between items-start gap-4">
            <div>
                <p class="text-label-sm uppercase tracking-widest text-on-surface-variant">{{ $comic->publisher->name }}</p>
                <h3 class="font-headline-md text-headline-md text-primary leading-tight uppercase">
                    <a href="{{ route('catalog.show', $comic) }}" class="hover:text-energy-lime transition-colors">
                        {{ $comic->title }}
                    </a>
                </h3>
            </div>
            <span class="font-headline-md text-headline-md text-primary">${{ number_format($comic->price, 2) }}</span>
        </div>

        <div class="flex gap-1 pt-2 items-center">
            <span class="material-symbols-outlined text-focus-zest text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
            <span class="material-symbols-outlined text-focus-zest text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
            <span class="material-symbols-outlined text-focus-zest text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
            <span class="material-symbols-outlined text-focus-zest text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
            <span class="material-symbols-outlined text-outline-variant text-sm">star</span>
        </div>
    </div>
</div>
@empty
<div class="col-span-full text-center py-20">
    <span class="material-symbols-outlined text-9xl text-outline-variant mb-6">search_off</span>
    <p class="text-2xl font-black text-primary mb-2">No se encontraron comics</p>
    <p class="text-on-surface-variant">Prueba con otros filtros o términos de búsqueda</p>
</div>
@endforelse
