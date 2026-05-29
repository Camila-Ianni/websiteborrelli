<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-surface-container-lowest border border-outline-variant rounded-full font-label-bold text-label-bold uppercase tracking-widest text-on-surface-variant shadow-[0px_32px_32px_rgba(32,42,54,0.04)] hover:bg-surface-container-low hover:text-primary active:scale-95 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-energy-lime focus:ring-offset-2 focus:ring-offset-surface']) }}>
    {{ $slot }}
</button>
