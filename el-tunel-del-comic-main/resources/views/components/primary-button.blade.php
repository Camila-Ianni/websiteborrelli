<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-primary text-pure-white rounded-full font-label-bold text-label-bold uppercase tracking-widest hover:bg-energy-lime hover:text-primary active:scale-95 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-energy-lime focus:ring-offset-2 focus:ring-offset-surface']) }}>
    {{ $slot }}
</button>
