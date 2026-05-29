<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', __('messages.admin_title'))</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#0b1621",
                        "primary-container": "#202a36",
                        "secondary-container": "#fdd983",
                        "on-primary": "#ffffff",
                        "on-surface": "#1a1c1c",
                        "on-surface-variant": "#44474b",
                        background: "#f9f9f9",
                        surface: "#ffffff",
                        "surface-container": "#f3f3f3",
                        "outline-variant": "#c5c6cc",
                        "energy-lime": "#D4FF00",
                        "recovery-berry": "#FF2E63"
                    },
                    fontFamily: {
                        headline: ["Montserrat"],
                        body: ["Inter"]
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-background text-on-surface font-body">
    <div class="flex min-h-screen">
        <x-admin.sidebar />
        <div class="flex-1 flex flex-col">
            <header class="bg-surface border-b border-outline-variant px-8 py-5 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-headline font-bold">@yield('page_title')</h1>
                    <p class="text-on-surface-variant text-sm">@yield('page_subtitle')</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-primary text-on-primary px-4 py-2 rounded font-semibold" type="submit">
                        {{ __('messages.logout') }}
                    </button>
                </form>
            </header>

            @if ($errors->any())
                <div class="px-8 pt-6">
                    <div class="bg-recovery-berry/10 text-recovery-berry p-4 rounded">
                        <ul class="list-disc pl-6 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="px-8 pt-6">
                    <div class="bg-energy-lime text-primary p-4 rounded font-semibold">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <main class="flex-1 px-8 py-8">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
