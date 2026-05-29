<!DOCTYPE html>
<html class="scroll-smooth" lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'El Túnel del Cómic')</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            background: '#f9f9f9',
                            'energy-lime': '#D4FF00',
                            'on-background': '#1a1c1c',
                            'on-surface-variant': '#44474b',
                            'outline-variant': '#c5c6cc',
                            primary: '#0b1621',
                            'primary-container': '#202a36',
                            surface: '#f9f9f9',
                            'surface-container-lowest': '#ffffff',
                            'surface-container-low': '#f3f3f3',
                            'surface-variant': '#e2e2e2',
                            'pure-white': '#FFFFFF',
                            'recovery-berry': '#FF2E63',
                        },
                        fontFamily: {
                            body: ['Inter', 'sans-serif'],
                            'body-md': ['Inter', 'sans-serif'],
                            'display-xl-mobile': ['Montserrat', 'sans-serif'],
                            headline: ['Montserrat', 'sans-serif'],
                            'label-bold': ['Inter', 'sans-serif'],
                        },
                    }
                }
            }
        </script>
        <style>
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: #f1f1f1; }
            ::-webkit-scrollbar-thumb { background: #202a36; border-radius: 10px; }
        </style>
    </head>
    <body class="bg-background text-on-background font-body-md antialiased overflow-x-hidden selection:bg-energy-lime selection:text-primary">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-10">
            <div class="mb-8">
                <a href="/" class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-primary flex items-center justify-center">
                        <span class="text-energy-lime font-display-xl-mobile text-3xl font-black">ET</span>
                    </div>
                    <span class="font-display-xl-mobile text-3xl font-black uppercase tracking-tighter text-primary">El Túnel del Cómic</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-8 bg-surface-container-lowest border border-outline-variant shadow-[0px_32px_32px_rgba(32,42,54,0.06)] rounded-xl">
                {{ $slot }}
            </div>

            <div class="mt-8">
                <a href="{{ route('home') }}" class="text-sm font-bold uppercase tracking-widest text-on-surface-variant hover:text-energy-lime transition">
                    ← Volver al Inicio
                </a>
            </div>
        </div>
    </body>
</html>
