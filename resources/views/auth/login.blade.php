<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ __('messages.login_title') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@400;500;700&display=swap" rel="stylesheet"/>
</head>
<body class="bg-background text-on-surface font-body flex items-center justify-center min-h-screen">
    <div class="bg-surface rounded-xl p-10 shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6">{{ __('messages.login_heading') }}</h1>
        @if ($errors->any())
            <div class="bg-recovery-berry/10 text-recovery-berry p-4 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif
        <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold mb-2">{{ __('messages.customer_email') }}</label>
                <input class="w-full rounded-lg border-outline-variant bg-surface px-4 py-2" name="email" type="email" required>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">{{ __('messages.password') }}</label>
                <input class="w-full rounded-lg border-outline-variant bg-surface px-4 py-2" name="password" type="password" required>
            </div>
            <button class="w-full bg-primary text-on-primary px-4 py-3 rounded-lg font-semibold" type="submit">
                {{ __('messages.login_button') }}
            </button>
        </form>
    </div>
</body>
</html>
