<footer class="bg-primary text-pure-white py-16 mt-20 overflow-hidden relative">
    <div class="absolute top-0 right-0 w-1/3 h-full bg-energy-lime opacity-10 skew-x-12 translate-x-32"></div>
    <div class="max-w-container-max mx-auto px-margin-desktop grid grid-cols-1 md:grid-cols-4 gap-12 relative z-10">
        <div>
            <h3 class="font-display-xl-mobile text-headline-md tracking-tighter uppercase mb-4">
                EL TÚNEL<span class="text-energy-lime block text-xs tracking-widest">DEL CÓMIC</span>
            </h3>
            <p class="text-sm text-surface-variant">Tu tienda especializada en comics, manga y coleccionables.</p>
        </div>
        <div>
            <h4 class="font-label-bold text-label-bold uppercase text-energy-lime mb-4">Enlaces</h4>
            <ul class="space-y-2 text-sm text-surface-variant">
                <li><a href="{{ route('home') }}" class="hover:text-energy-lime transition">Inicio</a></li>
                <li><a href="{{ route('catalog.index') }}" class="hover:text-energy-lime transition">Catálogo</a></li>
                <li><a href="#" class="hover:text-energy-lime transition">Sobre Nosotros</a></li>
                <li><a href="#" class="hover:text-energy-lime transition">Contacto</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-label-bold text-label-bold uppercase text-energy-lime mb-4">Categorías</h4>
            <ul class="space-y-2 text-sm text-surface-variant">
                <li><a href="#" class="hover:text-energy-lime transition">Superhéroes</a></li>
                <li><a href="#" class="hover:text-energy-lime transition">Manga</a></li>
                <li><a href="#" class="hover:text-energy-lime transition">Sci-Fi</a></li>
                <li><a href="#" class="hover:text-energy-lime transition">Horror</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-label-bold text-label-bold uppercase text-energy-lime mb-4">Contacto</h4>
            <ul class="space-y-2 text-sm text-surface-variant">
                <li>Email: info@eltuneldelcomic.com</li>
                <li>Tel: +34 900 123 456</li>
                <li>Horario: L-V 9:00-20:00</li>
            </ul>
        </div>
    </div>
    <div class="max-w-container-max mx-auto px-margin-desktop mt-12 pt-8 border-t border-white/10 text-center text-sm text-surface-variant relative z-10">
        <p>&copy; {{ date('Y') }} El Túnel del Cómic. Todos los derechos reservados.</p>
    </div>
</footer>
