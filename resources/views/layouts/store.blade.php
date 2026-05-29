<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', __('messages.site_title'))</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
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
    </style>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary-fixed-dim": "#d9c3a7",
                        "tertiary-container": "#342714",
                        "on-secondary-fixed-variant": "#5a4400",
                        "inverse-surface": "#2f3131",
                        "focus-zest": "#FF8C00",
                        "secondary-fixed-dim": "#e5c270",
                        "surface-bright": "#f9f9f9",
                        "on-primary": "#ffffff",
                        "primary-fixed": "#d9e3f4",
                        "on-tertiary-fixed-variant": "#54442f",
                        "surface-container-low": "#f3f3f3",
                        "on-primary-container": "#8791a0",
                        "tertiary": "#1e1303",
                        "surface-container-highest": "#e2e2e2",
                        "surface-tint": "#555f6d",
                        "pure-white": "#FFFFFF",
                        "on-secondary-container": "#785d13",
                        "surface-dim": "#dadada",
                        "on-tertiary": "#ffffff",
                        "on-primary-fixed": "#121c28",
                        "surface-container-lowest": "#ffffff",
                        "energy-lime": "#D4FF00",
                        "error": "#ba1a1a",
                        "surface-variant": "#e2e2e2",
                        "primary": "#0b1621",
                        "pure-black": "#1A1A1A",
                        "surface-container": "#eeeeee",
                        "secondary-fixed": "#ffdf97",
                        "on-error-container": "#93000a",
                        "on-surface": "#1a1c1c",
                        "on-secondary": "#ffffff",
                        "secondary": "#755b11",
                        "inverse-on-surface": "#f1f1f1",
                        "on-error": "#ffffff",
                        "secondary-container": "#fdd983",
                        "on-surface-variant": "#44474b",
                        "tertiary-fixed": "#f6dfc2",
                        "on-tertiary-container": "#a18d74",
                        "inverse-primary": "#bdc7d7",
                        "outline-variant": "#c5c6cc",
                        "on-primary-fixed-variant": "#3e4855",
                        "primary-container": "#202a36",
                        "background": "#f9f9f9",
                        "error-container": "#ffdad6",
                        "primary-fixed-dim": "#bdc7d7",
                        "on-secondary-fixed": "#251a00",
                        "on-tertiary-fixed": "#251908",
                        "outline": "#75777c",
                        "on-background": "#1a1c1c",
                        "recovery-berry": "#FF2E63",
                        "surface-container-high": "#e8e8e8",
                        "surface": "#f9f9f9"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin-mobile": "16px",
                        "container-max": "1280px",
                        "base": "8px",
                        "section-gap": "120px",
                        "gutter": "24px",
                        "margin-desktop": "64px"
                    },
                    "fontFamily": {
                        "body-lg": ["Inter"],
                        "headline-lg-mobile": ["Montserrat"],
                        "headline-lg": ["Montserrat"],
                        "display-xl-mobile": ["Montserrat"],
                        "label-sm": ["Inter"],
                        "display-xl": ["Montserrat"],
                        "headline-md": ["Montserrat"],
                        "label-bold": ["Inter"],
                        "body-md": ["Inter"]
                    },
                    "fontSize": {
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                        "headline-lg-mobile": ["32px", {"lineHeight": "38px", "fontWeight": "800"}],
                        "headline-lg": ["40px", {"lineHeight": "48px", "letterSpacing": "-0.02em", "fontWeight": "800"}],
                        "display-xl-mobile": ["48px", {"lineHeight": "52px", "letterSpacing": "-0.02em", "fontWeight": "900"}],
                        "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "500"}],
                        "display-xl": ["80px", {"lineHeight": "84px", "letterSpacing": "-0.04em", "fontWeight": "900"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                        "label-bold": ["14px", {"lineHeight": "20px", "letterSpacing": "0.05em", "fontWeight": "700"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}]
                    }
                },
            },
        }
    </script>
    @stack('styles')
</head>
<body class="bg-background text-on-background font-body-md overflow-x-hidden selection:bg-energy-lime selection:text-primary">
    <x-store.nav />

    @if ($errors->any())
        <div class="max-w-container-max mx-auto px-margin-desktop pt-6">
            <div class="bg-error-container text-on-error-container p-4 rounded">
                <ul class="list-disc pl-6 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="max-w-container-max mx-auto px-margin-desktop pt-6">
            <div class="bg-energy-lime text-primary p-4 rounded font-label-bold text-label-bold">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @yield('content')

    <x-store.footer />

    @stack('scripts')
</body>
</html>
