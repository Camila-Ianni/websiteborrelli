@extends('layouts.app')

@section('title', 'Checkout - El Túnel del Cómic')

@section('content')
<div class="pt-24 min-h-screen bg-background text-on-background">
    <div class="max-w-container-max mx-auto px-margin-desktop py-section-gap">
        <h1 class="text-5xl font-display-xl-mobile font-black uppercase mb-12 text-primary">{{ __('messages.checkout') }}</h1>

        @if($errors->any())
        <div class="bg-surface-container-lowest border border-recovery-berry text-primary px-6 py-4 mb-6 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li class="font-bold">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('warning'))
        <div class="bg-surface-container-lowest border border-focus-zest text-primary px-6 py-4 mb-6 rounded-lg">
            <p class="font-bold">{{ session('warning') }}</p>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Customer Information Form -->
            <div class="bg-surface-container-lowest border border-outline-variant p-8 rounded-xl shadow-[0px_32px_32px_rgba(32,42,54,0.04)]">
                <h2 class="font-display-xl-mobile font-black uppercase text-2xl mb-6 text-primary">{{ __('messages.customer_data') }}</h2>
                
                <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block font-label-bold text-label-bold uppercase text-sm mb-2 text-primary">{{ __('messages.full_name') }} *</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required 
                            class="w-full px-4 py-3 border border-outline-variant rounded-lg bg-surface-container-lowest font-bold focus:outline-none focus:border-energy-lime"/>
                    </div>

                    <div>
                        <label class="block font-label-bold text-label-bold uppercase text-sm mb-2 text-primary">DNI / {{ __('messages.document') }} *</label>
                        <input type="text" name="customer_dni" value="{{ old('customer_dni') }}" required 
                            class="w-full px-4 py-3 border border-outline-variant rounded-lg bg-surface-container-lowest font-bold focus:outline-none focus:border-energy-lime"/>
                    </div>

                    <div>
                        <label class="block font-label-bold text-label-bold uppercase text-sm mb-2 text-primary">Email *</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email') }}" required 
                            class="w-full px-4 py-3 border border-outline-variant rounded-lg bg-surface-container-lowest font-bold focus:outline-none focus:border-energy-lime"/>
                        <p class="text-sm text-on-surface-variant mt-1">{{ __('messages.email_confirmation_note') }}</p>
                    </div>

                    <div>
                        <label class="block font-label-bold text-label-bold uppercase text-sm mb-2 text-primary">{{ __('messages.phone') }}</label>
                        <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" 
                            class="w-full px-4 py-3 border border-outline-variant rounded-lg bg-surface-container-lowest font-bold focus:outline-none focus:border-energy-lime"
                            placeholder="+54 9 11 0000-0000"/>
                    </div>

                    <!-- Payment Method Selection -->
                    <div class="pt-4 border-t border-outline-variant">
                        <label class="block font-label-bold text-label-bold uppercase text-sm mb-4 text-primary">{{ __('messages.payment_method') }} *</label>
                        
                        <div class="space-y-3">
                            <!-- Bank Transfer -->
                            <label class="flex items-center p-4 border border-outline-variant cursor-pointer hover:bg-surface-container-low transition-colors has-[:checked]:bg-primary-container has-[:checked]:text-pure-white rounded-lg">
                                <input type="radio" name="payment_method" value="transfer" checked 
                                    class="w-5 h-5 text-primary border-black focus:ring-primary"/>
                                <div class="ml-4">
                                    <span class="font-bold uppercase block">{{ __('messages.bank_transfer') }}</span>
                                    <span class="text-sm text-on-surface-variant">{{ __('messages.transfer_note') }}</span>
                                </div>
                                <span class="material-symbols-outlined ml-auto text-2xl">account_balance</span>
                            </label>

                            <!-- MercadoPago -->
                            @if($mercadoPagoEnabled ?? false)
                            <label class="flex items-center p-4 border border-outline-variant cursor-pointer hover:bg-surface-container-low transition-colors has-[:checked]:bg-surface-container-low has-[:checked]:border-energy-lime rounded-lg">
                                <input type="radio" name="payment_method" value="mercadopago" 
                                    class="w-5 h-5 text-blue-500 border-black focus:ring-blue-500"/>
                                <div class="ml-4">
                                    <span class="font-bold uppercase block">MercadoPago</span>
                                    <span class="text-sm text-on-surface-variant">{{ __('messages.mercadopago_note') }}</span>
                                </div>
                                <img src="https://http2.mlstatic.com/frontend-assets/mp-web-navigation/badge.png" alt="MercadoPago" class="ml-auto h-8"/>
                            </label>
                            @else
                            <div class="p-4 border border-dashed border-outline-variant bg-surface-container-low rounded-lg">
                                <div class="flex items-center text-on-surface-variant">
                                    <span class="material-symbols-outlined mr-2">credit_card_off</span>
                                    <span class="text-sm">MercadoPago no configurado</span>
                                </div>
                            </div>
                            @endif

                            <!-- PayPal -->
                            @if($paypalEnabled ?? false)
                            <label class="flex items-center p-4 border border-outline-variant cursor-pointer hover:bg-surface-container-low transition-colors has-[:checked]:bg-surface-container-low has-[:checked]:border-energy-lime rounded-lg">
                                <input type="radio" name="payment_method" value="paypal" 
                                    class="w-5 h-5 text-yellow-600 border-black focus:ring-yellow-600"/>
                                <div class="ml-4">
                                    <span class="font-bold uppercase block">PayPal</span>
                                    <span class="text-sm text-on-surface-variant">{{ __('messages.paypal_note') }}</span>
                                </div>
                                <svg class="ml-auto h-8" viewBox="0 0 124 33" xmlns="http://www.w3.org/2000/svg"><path d="M46.211 6.749h-6.839a.95.95 0 0 0-.939.802l-2.766 17.537a.57.57 0 0 0 .564.658h3.265a.95.95 0 0 0 .939-.803l.746-4.73a.95.95 0 0 1 .938-.803h2.165c4.505 0 7.105-2.18 7.784-6.5.306-1.89.013-3.375-.872-4.415-.972-1.142-2.696-1.746-4.985-1.746zM47 13.154c-.374 2.454-2.249 2.454-4.062 2.454h-1.032l.724-4.583a.57.57 0 0 1 .563-.481h.473c1.235 0 2.4 0 3.002.704.359.42.469 1.044.332 1.906zM66.654 13.075h-3.275a.57.57 0 0 0-.563.481l-.145.916-.229-.332c-.709-1.029-2.29-1.373-3.868-1.373-3.619 0-6.71 2.741-7.312 6.586-.313 1.918.132 3.752 1.22 5.031.998 1.176 2.426 1.666 4.125 1.666 2.916 0 4.533-1.875 4.533-1.875l-.146.91a.57.57 0 0 0 .562.66h2.95a.95.95 0 0 0 .939-.803l1.77-11.209a.568.568 0 0 0-.561-.658zm-4.565 6.374c-.316 1.871-1.801 3.127-3.695 3.127-.951 0-1.711-.305-2.199-.883-.484-.574-.668-1.391-.514-2.301.295-1.855 1.805-3.152 3.67-3.152.93 0 1.686.309 2.184.892.499.589.697 1.411.554 2.317zM84.096 13.075h-3.291a.954.954 0 0 0-.787.417l-4.539 6.686-1.924-6.425a.953.953 0 0 0-.912-.678h-3.234a.57.57 0 0 0-.541.754l3.625 10.638-3.408 4.811a.57.57 0 0 0 .465.9h3.287a.949.949 0 0 0 .781-.408l10.946-15.8a.57.57 0 0 0-.468-.895z" fill="#253B80"/><path d="M94.992 6.749h-6.84a.95.95 0 0 0-.938.802l-2.766 17.537a.569.569 0 0 0 .562.658h3.51a.665.665 0 0 0 .656-.562l.785-4.971a.95.95 0 0 1 .938-.803h2.164c4.506 0 7.105-2.18 7.785-6.5.307-1.89.012-3.375-.873-4.415-.971-1.142-2.694-1.746-4.983-1.746zm.789 6.405c-.373 2.454-2.248 2.454-4.062 2.454h-1.031l.725-4.583a.568.568 0 0 1 .562-.481h.473c1.234 0 2.4 0 3.002.704.359.42.468 1.044.331 1.906zM115.434 13.075h-3.273a.567.567 0 0 0-.562.481l-.145.916-.23-.332c-.709-1.029-2.289-1.373-3.867-1.373-3.619 0-6.709 2.741-7.311 6.586-.312 1.918.131 3.752 1.219 5.031 1 1.176 2.426 1.666 4.125 1.666 2.916 0 4.533-1.875 4.533-1.875l-.146.91a.57.57 0 0 0 .564.66h2.949a.95.95 0 0 0 .938-.803l1.771-11.209a.571.571 0 0 0-.565-.658zm-4.565 6.374c-.314 1.871-1.801 3.127-3.695 3.127-.949 0-1.711-.305-2.199-.883-.484-.574-.666-1.391-.514-2.301.297-1.855 1.805-3.152 3.67-3.152.93 0 1.686.309 2.184.892.501.589.699 1.411.554 2.317zM119.295 7.23l-2.807 17.858a.569.569 0 0 0 .562.658h2.822c.469 0 .867-.34.939-.803l2.768-17.536a.57.57 0 0 0-.562-.659h-3.16a.571.571 0 0 0-.562.482z" fill="#179BD7"/><path d="M7.266 29.154l.523-3.322-1.165-.027H1.061L4.927 1.292a.316.316 0 0 1 .314-.268h9.38c3.114 0 5.263.648 6.385 1.927.526.6.861 1.227 1.023 1.917.17.724.173 1.589.007 2.644l-.012.077v.676l.526.298a3.69 3.69 0 0 1 1.065.812c.45.513.741 1.165.864 1.938.127.795.085 1.741-.123 2.812-.24 1.232-.628 2.305-1.152 3.183a6.547 6.547 0 0 1-1.825 2c-.696.494-1.523.869-2.458 1.109-.906.236-1.939.355-3.072.355h-.73c-.522 0-1.029.188-1.427.525a2.21 2.21 0 0 0-.744 1.328l-.055.299-.924 5.855-.042.215c-.011.068-.03.102-.058.125a.155.155 0 0 1-.096.035H7.266z" fill="#253B80"/><path d="M23.048 7.667c-.028.179-.06.362-.096.55-1.237 6.351-5.469 8.545-10.874 8.545H9.326c-.661 0-1.218.48-1.321 1.132L6.596 26.83l-.399 2.533a.704.704 0 0 0 .695.814h4.881c.578 0 1.069-.42 1.16-.99l.048-.248.919-5.832.059-.32c.09-.572.582-.992 1.16-.992h.73c4.729 0 8.431-1.92 9.513-7.476.452-2.321.218-4.259-.978-5.622a4.667 4.667 0 0 0-1.336-1.03z" fill="#179BD7"/><path d="M21.754 7.151a9.757 9.757 0 0 0-1.203-.267 15.284 15.284 0 0 0-2.426-.177h-7.352a1.172 1.172 0 0 0-1.159.992L8.05 17.605l-.045.289a1.336 1.336 0 0 1 1.321-1.132h2.752c5.405 0 9.637-2.195 10.874-8.545.037-.188.068-.371.096-.55a6.594 6.594 0 0 0-1.017-.429 9.045 9.045 0 0 0-.277-.087z" fill="#222D65"/><path d="M9.614 7.699a1.169 1.169 0 0 1 1.159-.991h7.352c.871 0 1.684.057 2.426.177a9.757 9.757 0 0 1 1.481.353c.365.121.704.264 1.017.429.368-2.347-.003-3.945-1.272-5.392C20.378.682 17.853 0 14.622 0h-9.38c-.66 0-1.223.48-1.325 1.133L.01 25.898a.806.806 0 0 0 .795.932h5.791l1.454-9.225 1.564-9.906z" fill="#253B80"/></svg>
                            </label>
                            @else
                            <div class="p-4 border border-dashed border-outline-variant bg-surface-container-low rounded-lg">
                                <div class="flex items-center text-on-surface-variant">
                                    <span class="material-symbols-outlined mr-2">credit_card_off</span>
                                    <span class="text-sm">PayPal no configurado</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-energy-lime text-primary py-4 font-label-bold uppercase rounded-lg hover:brightness-110 transition-all">
                            {{ __('messages.confirm_order') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div>
                <div class="bg-primary-container text-pure-white p-8 sticky top-24 rounded-xl shadow-[0px_32px_32px_rgba(32,42,54,0.06)]">
                    <h2 class="font-display-xl-mobile font-black uppercase text-2xl mb-6 text-energy-lime">{{ __('messages.order_summary') }}</h2>
                    
                    <div class="space-y-4 mb-6">
                        @foreach($cartItems as $item)
                        <div class="flex justify-between items-start pb-4 border-b border-white/10">
                            <div class="flex-1">
                                <p class="font-bold">{{ $item['comic']->title }}</p>
                                <p class="text-sm text-surface-variant">{{ __('messages.quantity') }}: {{ $item['quantity'] }}</p>
                                <p class="text-sm text-surface-variant">${{ number_format($item['comic']->price, 2) }} c/u</p>
                            </div>
                            <p class="font-black text-primary">
                                ${{ number_format($item['comic']->price * $item['quantity'], 2) }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="flex justify-between items-center pt-6 border-t border-white/10">
                        <span class="font-display-xl-mobile font-black uppercase text-2xl text-surface-variant">{{ __('messages.total') }}:</span>
                        <span class="font-display-xl-mobile font-black text-4xl text-energy-lime">${{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="mt-6 p-4 bg-surface-container-low border border-outline-variant rounded-lg">
                        <p class="text-sm text-on-surface-variant">
                            <strong>{{ __('messages.note') }}:</strong> {{ __('messages.order_note') }}
                        </p>
                    </div>
                </div>

                <a href="{{ route('cart.index') }}" class="block mt-6 text-center bg-surface-container-lowest text-primary py-4 font-label-bold uppercase border border-outline-variant rounded-lg hover:bg-energy-lime hover:text-primary transition-all">
                    {{ __('messages.back_to_cart') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
