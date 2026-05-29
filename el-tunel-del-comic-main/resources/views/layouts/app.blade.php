<!DOCTYPE html>
<html class="light scroll-smooth" lang="es">
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
                        error: '#ba1a1a',
                        'error-container': '#ffdad6',
                        'focus-zest': '#FF8C00',
                        'inverse-on-surface': '#f1f1f1',
                        'inverse-primary': '#bdc7d7',
                        'inverse-surface': '#2f3131',
                        'on-background': '#1a1c1c',
                        'on-error': '#ffffff',
                        'on-error-container': '#93000a',
                        'on-primary': '#ffffff',
                        'on-primary-container': '#8791a0',
                        'on-primary-fixed': '#121c28',
                        'on-primary-fixed-variant': '#3e4855',
                        'on-secondary': '#ffffff',
                        'on-secondary-container': '#785d13',
                        'on-secondary-fixed': '#251a00',
                        'on-secondary-fixed-variant': '#5a4400',
                        'on-surface': '#1a1c1c',
                        'on-surface-variant': '#44474b',
                        'on-tertiary': '#ffffff',
                        'on-tertiary-container': '#a18d74',
                        'on-tertiary-fixed': '#251908',
                        'on-tertiary-fixed-variant': '#54442f',
                        outline: '#75777c',
                        'outline-variant': '#c5c6cc',
                        primary: '#0b1621',
                        'primary-container': '#202a36',
                        'primary-fixed': '#d9e3f4',
                        'primary-fixed-dim': '#bdc7d7',
                        'recovery-berry': '#FF2E63',
                        'secondary': '#755b11',
                        'secondary-container': '#fdd983',
                        'secondary-fixed': '#ffdf97',
                        'secondary-fixed-dim': '#e5c270',
                        surface: '#f9f9f9',
                        'surface-bright': '#f9f9f9',
                        'surface-container': '#eeeeee',
                        'surface-container-high': '#e8e8e8',
                        'surface-container-highest': '#e2e2e2',
                        'surface-container-low': '#f3f3f3',
                        'surface-container-lowest': '#ffffff',
                        'surface-dim': '#dadada',
                        'surface-tint': '#555f6d',
                        'surface-variant': '#e2e2e2',
                        'pure-black': '#1A1A1A',
                        'pure-white': '#FFFFFF',
                        'energy-lime': '#D4FF00',
                        tertiary: '#1e1303',
                        'tertiary-container': '#342714',
                        'tertiary-fixed': '#f6dfc2',
                        'tertiary-fixed-dim': '#d9c3a7',
                    },
                    fontFamily: {
                        'body-md': ['Inter', 'sans-serif'],
                        'body-lg': ['Inter', 'sans-serif'],
                        'display-xl': ['Montserrat', 'sans-serif'],
                        'display-xl-mobile': ['Montserrat', 'sans-serif'],
                        'headline-lg': ['Montserrat', 'sans-serif'],
                        'headline-lg-mobile': ['Montserrat', 'sans-serif'],
                        'headline-md': ['Montserrat', 'sans-serif'],
                        'label-bold': ['Inter', 'sans-serif'],
                        'label-sm': ['Inter', 'sans-serif'],
                    },
                    fontSize: {
                        'body-md': ['16px', { lineHeight: '24px', fontWeight: '400' }],
                        'body-lg': ['18px', { lineHeight: '28px', fontWeight: '400' }],
                        'display-xl': ['80px', { lineHeight: '84px', letterSpacing: '-0.04em', fontWeight: '900' }],
                        'display-xl-mobile': ['48px', { lineHeight: '52px', letterSpacing: '-0.02em', fontWeight: '900' }],
                        'headline-lg': ['40px', { lineHeight: '48px', letterSpacing: '-0.02em', fontWeight: '800' }],
                        'headline-lg-mobile': ['32px', { lineHeight: '38px', fontWeight: '800' }],
                        'headline-md': ['24px', { lineHeight: '32px', fontWeight: '700' }],
                        'label-bold': ['14px', { lineHeight: '20px', letterSpacing: '0.05em', fontWeight: '700' }],
                        'label-sm': ['12px', { lineHeight: '16px', fontWeight: '500' }],
                    },
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
        }
        .hero-gradient {
            background: radial-gradient(circle at center, #f9f9f9 0%, #eeeeee 100%);
        }
        .accent-glow-lime:hover {
            box-shadow: 0 0 40px rgba(212, 255, 0, 0.3);
        }
        .product-card:hover .product-image {
            transform: scale(1.05) translateY(-8px);
        }
        .floating-fruit {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #202a36; border-radius: 10px; }
    </style>
</head>
<body class="bg-background text-on-background font-body-md antialiased overflow-x-hidden selection:bg-energy-lime selection:text-primary">
    @include('layouts.navbar')

    <main>
        @yield('content')
    </main>

    @include('layouts.footer')
</body>
</html>
