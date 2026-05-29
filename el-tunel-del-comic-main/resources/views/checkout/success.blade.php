@extends('layouts.app')

@section('title', 'Pedido Confirmado - El Túnel del Cómic')

@section('content')
<div class="pt-24 min-h-screen bg-background text-on-background">
    <div class="max-w-container-max mx-auto px-margin-desktop py-section-gap">
        <!-- Success Message -->
        <div class="text-center mb-12">
            <div class="inline-block bg-energy-lime text-primary p-6 rounded-full mb-6">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-6xl font-display-xl-mobile font-black uppercase mb-4 text-primary">¡Pedido Confirmado!</h1>
            <p class="text-2xl font-bold text-on-surface-variant">Número de Pedido: <span class="text-primary">{{ $order->order_number }}</span></p>
        </div>

        <!-- Payment Information -->
        <div class="bg-primary-container text-pure-white border border-outline-variant p-8 mb-8 rounded-xl shadow-[0px_32px_32px_rgba(32,42,54,0.06)]">
            <h2 class="font-display-xl-mobile font-black uppercase text-3xl text-energy-lime mb-6">
                Información de Pago
            </h2>

            <div class="bg-surface-container-low border border-outline-variant p-6 mb-6 rounded-lg">
                <p class="text-primary font-black text-xl mb-2">IMPORTANTE:</p>
                <p class="text-on-surface-variant">
                    Para completar tu pedido, realiza una transferencia bancaria por el monto total y envía el comprobante a nuestro email.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-surface-container-low p-6 border border-outline-variant rounded-lg">
                    <p class="text-energy-lime font-black uppercase text-sm mb-3">Banco</p>
                    <p class="font-black text-2xl">Banco Galicia</p>
                </div>

                <div class="bg-surface-container-low p-6 border border-outline-variant rounded-lg">
                    <p class="text-energy-lime font-black uppercase text-sm mb-3">Titular</p>
                    <p class="font-black text-2xl">El Túnel del Cómic</p>
                </div>

                <div class="bg-surface-container-low p-6 border border-outline-variant rounded-lg">
                    <p class="text-energy-lime font-black uppercase text-sm mb-3">CBU</p>
                    <p class="font-black text-2xl font-mono">0070 0990 2000 0012 3456 78</p>
                </div>

                <div class="bg-surface-container-low p-6 border border-outline-variant rounded-lg">
                    <p class="text-energy-lime font-black uppercase text-sm mb-3">Alias</p>
                    <p class="font-black text-2xl uppercase">tunel.comic.ar</p>
                </div>

                <div class="bg-surface-container-low p-6 border border-outline-variant md:col-span-2 rounded-lg">
                    <p class="text-energy-lime font-black uppercase text-sm mb-3">Monto Total</p>
                    <p class="font-black text-4xl text-primary">${{ number_format($order->total, 2) }}</p>
                </div>
            </div>

            <div class="mt-8 p-6 bg-surface-container-low border border-outline-variant rounded-lg">
                <p class="font-black text-xl mb-4 text-primary">📧 Enviar comprobante a:</p>
                <p class="text-energy-lime font-black text-2xl mb-4">pagos@eltuneldelcomic.com</p>
                <p class="text-sm text-on-surface-variant">
                    <strong>Asunto del email:</strong> Pedido {{ $order->order_number }}<br>
                    <strong>Incluir en el email:</strong> Tu nombre completo y DNI
                </p>
            </div>
        </div>

        <!-- Order Details -->
        <div class="bg-surface-container-lowest border border-outline-variant p-8 mb-8 rounded-xl shadow-[0px_32px_32px_rgba(32,42,54,0.04)]">
            <h2 class="font-display-xl-mobile font-black uppercase text-2xl mb-6 text-primary">Detalles del Pedido</h2>
            
            <div class="mb-6 pb-6 border-b border-outline-variant">
                <p class="text-sm font-black uppercase text-on-surface-variant mb-1">Cliente</p>
                <p class="font-bold text-lg">{{ $order->customer_name }}</p>
                <p class="text-on-surface-variant">DNI: {{ $order->customer_dni }}</p>
                <p class="text-on-surface-variant">Email: {{ $order->customer_email }}</p>
            </div>

            <table class="w-full">
                <thead class="bg-primary-container text-energy-lime">
                    <tr>
                        <th class="px-4 py-3 text-left font-black uppercase">Comic</th>
                        <th class="px-4 py-3 text-center font-black uppercase">Cantidad</th>
                        <th class="px-4 py-3 text-right font-black uppercase">Precio</th>
                        <th class="px-4 py-3 text-right font-black uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-4 py-3 font-bold">{{ $item->comic->title }}</td>
                        <td class="px-4 py-3 text-center font-bold">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 text-right font-bold">${{ number_format($item->price, 2) }}</td>
                        <td class="px-4 py-3 text-right font-bold">${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-surface-container-low">
                        <td colspan="3" class="px-4 py-4 text-right font-black uppercase text-xl text-primary">TOTAL:</td>
                        <td class="px-4 py-4 text-right font-black text-2xl text-primary">${{ number_format($order->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 justify-center">
            <a href="{{ route('home') }}" class="bg-primary text-pure-white px-12 py-4 font-label-bold uppercase rounded-lg hover:bg-energy-lime hover:text-primary transition-all">
                Volver al Inicio
            </a>
            <a href="{{ route('catalog.index') }}" class="bg-surface-container-lowest text-primary px-12 py-4 font-label-bold uppercase rounded-lg border border-outline-variant hover:bg-energy-lime hover:text-primary transition-all">
                Seguir Comprando
            </a>
        </div>

        <!-- Additional Note -->
        <div class="mt-12 text-center">
            <p class="font-bold text-primary">
                Una vez que verifiquemos tu pago, te enviaremos un email de confirmación con los detalles de envío.
            </p>
            <p class="text-sm text-on-surface-variant mt-2">
                Si tienes alguna consulta, contáctanos a: info@eltuneldelcomic.com
            </p>
        </div>
    </div>
</div>
@endsection
