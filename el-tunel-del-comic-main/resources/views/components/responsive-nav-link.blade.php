@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-energy-lime text-start text-base font-medium text-primary bg-surface-container-low focus:outline-none focus:text-primary focus:bg-surface-container-low focus:border-energy-lime transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-on-surface-variant hover:text-primary hover:bg-surface-container-low hover:border-outline-variant focus:outline-none focus:text-primary focus:bg-surface-container-low focus:border-outline-variant transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
