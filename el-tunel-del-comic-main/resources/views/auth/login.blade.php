@extends('layouts.app')

@section('title', 'Iniciar Sesión - El Túnel del Cómic')

@section('content')
<div class="pt-24 min-h-screen bg-background flex items-center justify-center">
    <div class="w-full max-w-md px-8 py-10 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-[0px_32px_32px_rgba(32,42,54,0.06)]">
        <h1 class="font-display-xl-mobile text-headline-lg font-black uppercase text-center mb-8 text-primary">Iniciar Sesión</h1>
        
        @if(session('status'))
        <div class="bg-surface-container-low border border-energy-lime text-primary px-4 py-3 mb-6 font-bold rounded-lg">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-6">
                <label for="email" class="block font-label-bold text-label-bold uppercase text-sm mb-2 text-primary">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 border border-outline-variant rounded-lg bg-surface-container-lowest font-bold focus:outline-none focus:border-energy-lime"/>
                @error('email')
                    <p class="text-red-600 text-sm font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block font-label-bold text-label-bold uppercase text-sm mb-2 text-primary">Contraseña</label>
                <input id="password" type="password" name="password" required
                    class="w-full px-4 py-3 border border-outline-variant rounded-lg bg-surface-container-lowest font-bold focus:outline-none focus:border-energy-lime"/>
                @error('password')
                    <p class="text-red-600 text-sm font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" class="w-5 h-5 accent-primary">
                    <span class="font-bold text-sm text-on-surface-variant">Recordarme</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-primary text-pure-white py-4 font-label-bold uppercase rounded-lg hover:bg-energy-lime hover:text-primary transition-all">
                Entrar
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('register') }}" class="font-bold text-sm text-on-surface-variant hover:text-energy-lime">
                ¿No tienes cuenta? Regístrate
            </a>
        </div>
    </div>
</div>
@endsection
