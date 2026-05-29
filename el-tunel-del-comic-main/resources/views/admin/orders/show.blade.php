@extends('layouts.app')

@section('title', 'Pedido ' . $order->order_number . ' - Admin')

@section('content')
<div class="pt-24 min-h-screen bg-background text-on-background">
    <div class="max-w-container-max mx-auto px-margin-desktop py-section-gap">
        <div class="flex justify-between items-center mb-8">
            <h1 class="font-display-xl-mobile text-headline-lg font-black uppercase text-primary">Pedido {{ $order->order_number }}</h1>
            <a href="{{ route('admin.orders.index') }}" class="bg-primary-container text-energy-lime px-6 py-3 font-label-bold text-label-bold uppercase rounded-lg hover:bg-energy-lime hover:text-primary transition-all">
                Volver a Pedidos
            </a>
        </div>

        @if(session('success'))
        <div class="bg-surface-container-lowest border border-outline-variant text-primary px-6 py-4 mb-6 font-bold rounded-lg shadow-[0px_32px_32px_rgba(32,42,54,0.04)]">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <div class="bg-surface-container-lowest border border-outline-variant p-8 rounded-xl shadow-[0px_32px_32px_rgba(32,42,54,0.04)]">
                    <h2 class="font-display-xl-mobile font-black uppercase text-2xl mb-6 text-primary">Información del Cliente</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-black uppercase text-on-surface-variant mb-1">Nombre Completo</p>
                            <p class="font-bold text-lg">{{ $order->customer_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-black uppercase text-on-surface-variant mb-1">DNI / Documento</p>
                            <p class="font-bold text-lg">{{ $order->customer_dni }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-black uppercase text-on-surface-variant mb-1">Email</p>
                            <p class="font-bold text-lg">
                                <a href="mailto:{{ $order->customer_email }}" class="text-energy-lime hover:underline">
                                    {{ $order->customer_email }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-surface-container-lowest border border-outline-variant p-8 rounded-xl shadow-[0px_32px_32px_rgba(32,42,54,0.04)]">
                    <h2 class="font-display-xl-mobile font-black uppercase text-2xl mb-6 text-primary">Items del Pedido</h2>
                    
                    <table class="w-full">
                        <thead class="bg-primary-container text-energy-lime">
                            <tr>
                                <th class="px-4 py-3 text-left font-black uppercase">Comic</th>
                                <th class="px-4 py-3 text-left font-black uppercase">Editorial</th>
                                <th class="px-4 py-3 text-center font-black uppercase">Cant.</th>
                                <th class="px-4 py-3 text-right font-black uppercase">Precio</th>
                                <th class="px-4 py-3 text-right font-black uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @foreach($order->items as $item)
                            <tr>
                                <td class="px-4 py-4 font-bold">{{ $item->comic->title }}</td>
                                <td class="px-4 py-4 text-sm text-on-surface-variant">{{ $item->comic->publisher->name }}</td>
                                <td class="px-4 py-4 text-center font-bold">{{ $item->quantity }}</td>
                                <td class="px-4 py-4 text-right font-bold">${{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-4 text-right font-bold">${{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                            <tr class="bg-surface-container-low">
                                <td colspan="4" class="px-4 py-4 text-right font-black uppercase text-xl text-primary">TOTAL:</td>
                                <td class="px-4 py-4 text-right font-black text-2xl text-primary">${{ number_format($order->total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status Card -->
                <div class="bg-surface-container-lowest border border-outline-variant p-8 rounded-xl shadow-[0px_32px_32px_rgba(32,42,54,0.04)]">
                    <h3 class="font-display-xl-mobile font-black uppercase text-xl mb-6 text-primary">Estado del Pedido</h3>
                    
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-6">
                            <label class="block font-label-bold text-label-bold uppercase text-sm mb-3 text-primary">Estado Actual</label>
                            <select name="status" class="w-full px-4 py-3 border border-outline-variant rounded-lg bg-surface-container-lowest font-bold focus:outline-none focus:border-energy-lime">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>
                                    Pendiente
                                </option>
                                <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>
                                    Pagado
                                </option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>
                                    Cancelado
                                </option>
                            </select>
                        </div>
                        
                        <button type="submit" class="w-full bg-energy-lime text-primary py-3 font-label-bold uppercase rounded-lg hover:brightness-110 transition-all">
                            Actualizar Estado
                        </button>
                    </form>
                </div>

                <!-- Order Info Card -->
                <div class="bg-primary-container text-pure-white p-8 rounded-xl shadow-[0px_32px_32px_rgba(32,42,54,0.04)]">
                    <h3 class="font-display-xl-mobile font-black uppercase text-xl text-energy-lime mb-6">Información</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm uppercase text-surface-variant mb-1">Fecha de Pedido</p>
                            <p class="font-bold">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm uppercase text-surface-variant mb-1">Última Actualización</p>
                            <p class="font-bold">{{ $order->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm uppercase text-surface-variant mb-1">Total de Items</p>
                            <p class="font-bold text-2xl text-energy-lime">{{ $order->items->sum('quantity') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm uppercase text-surface-variant mb-1">Estado</p>
                            @if($order->status === 'pending')
                                <span class="inline-block bg-focus-zest text-pure-white px-4 py-2 font-black uppercase text-sm rounded-full">
                                    Pendiente
                                </span>
                            @elseif($order->status === 'paid')
                                <span class="inline-block bg-energy-lime text-primary px-4 py-2 font-black uppercase text-sm rounded-full">
                                    Pagado
                                </span>
                            @else
                                <span class="inline-block bg-recovery-berry text-pure-white px-4 py-2 font-black uppercase text-sm rounded-full">
                                    Cancelado
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-surface-container-lowest border border-outline-variant p-6 rounded-xl shadow-[0px_32px_32px_rgba(32,42,54,0.04)]">
                    <h3 class="font-display-xl-mobile font-black uppercase text-lg mb-4 text-primary">Acciones Rápidas</h3>
                    
                    <div class="space-y-3">
                        <a href="mailto:{{ $order->customer_email }}?subject=Pedido {{ $order->order_number }}" 
                           class="block w-full text-center bg-primary text-pure-white py-3 font-bold uppercase text-sm rounded-lg hover:bg-energy-lime hover:text-primary transition-all">
                            Enviar Email
                        </a>
                        
                        <button onclick="window.print()" class="w-full bg-surface-container-low text-primary py-3 font-bold uppercase text-sm rounded-lg hover:bg-energy-lime hover:text-primary transition-all">
                            Imprimir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
