@extends('layouts.app')

@section('title', 'Order Management | ELITE LABS')

@section('content')
<div class="min-h-screen bg-surface text-on-surface font-body-md overflow-x-hidden">
    <aside class="hidden md:flex flex-col h-full py-8 bg-primary dark:bg-pure-black shadow-[32px_0_32px_rgba(32,42,54,0.06)] h-screen w-64 fixed left-0 top-0 z-50">
        <div class="px-6 mb-12">
            <h1 class="font-display-xl text-display-xl tracking-tighter text-pure-white leading-none">ELITE LABS</h1>
            <p class="font-label-bold text-label-bold text-energy-lime mt-1 tracking-widest uppercase">Admin Terminal</p>
        </div>
        <nav class="flex-1 space-y-1">
            <a class="flex items-center gap-4 px-6 py-4 text-surface-variant hover:text-pure-white transition-colors hover:bg-primary-container" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-label-bold text-label-bold">Dashboard</span>
            </a>
            <a class="flex items-center gap-4 px-6 py-4 text-surface-variant hover:text-pure-white transition-colors hover:bg-primary-container" href="{{ route('admin.comics.index') }}">
                <span class="material-symbols-outlined">inventory_2</span>
                <span class="font-label-bold text-label-bold">Inventory</span>
            </a>
            <a class="flex items-center gap-4 px-6 py-4 bg-primary-container text-energy-lime border-r-4 border-energy-lime shadow-sm transition-all duration-150 ease-in-out" href="{{ route('admin.orders.index') }}">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">shopping_cart</span>
                <span class="font-label-bold text-label-bold">Orders</span>
            </a>
            <a class="flex items-center gap-4 px-6 py-4 text-surface-variant hover:text-pure-white transition-colors hover:bg-primary-container" href="#">
                <span class="material-symbols-outlined">group</span>
                <span class="font-label-bold text-label-bold">Customers</span>
            </a>
            <a class="flex items-center gap-4 px-6 py-4 text-surface-variant hover:text-pure-white transition-colors hover:bg-primary-container" href="#">
                <span class="material-symbols-outlined">settings</span>
                <span class="font-label-bold text-label-bold">Settings</span>
            </a>
        </nav>
        <div class="mt-auto px-6 pt-8 border-t border-primary-container">
            <a href="{{ route('home') }}" class="w-full bg-energy-lime text-primary font-label-bold text-label-bold py-3 mb-6 hover:brightness-110 transition-all flex items-center justify-center rounded-lg">
                View Storefront
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-4 py-4 text-surface-variant hover:text-recovery-berry transition-colors">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-label-bold text-label-bold">Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    <header class="flex justify-between items-center ml-0 md:ml-64 px-margin-mobile md:px-margin-desktop w-full md:w-[calc(100%-16rem)] bg-surface border-b border-surface-variant h-20 sticky top-0 z-40">
        <div class="flex items-center gap-8 flex-1">
            <h2 class="md:hidden font-headline-md text-headline-md font-extrabold text-primary">ELITE LABS</h2>
            <div class="hidden md:flex flex-1 max-w-md relative group">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                <input class="w-full pl-10 pr-4 py-2 bg-surface-container-low border-none focus:ring-2 focus:ring-energy-lime font-body-md text-body-md" form="filtersForm" name="search" placeholder="Search orders, transactions, IDs..." type="text" value="{{ request('search') }}"/>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <nav class="hidden lg:flex items-center gap-8">
                <a class="font-label-bold text-label-bold text-on-surface-variant hover:text-focus-zest" href="#">Global Reports</a>
                <a class="font-label-bold text-label-bold text-on-surface-variant hover:text-focus-zest" href="#">System Status</a>
            </nav>
            <div class="flex items-center gap-4">
                <button class="material-symbols-outlined p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors">notifications</button>
                <div class="h-10 w-10 bg-primary-container rounded-full flex items-center justify-center text-energy-lime overflow-hidden">
                    <img alt="Elite Labs Admin Profile" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCEDVYtuiGkI92uexBWSiLxMKZI94q2t4kiCtXNgXE2KtRTh4_ACQwIKh4UEQHt80uxCNXsWplb9g7UrkhadMO28gy-velzV4514guJKnHgEIT62b19mDLwNRGbgEdKIfSkqSOuCECEqW2JqRr7b-4t0ru4Z-JLOl2aMRNPyuz8qXtBAuF02doBpHJ0UEyuQ-AWcc5wBs2AxUWqIZ-7PUD1qdM6X9S4PYk2wQ-LAX_SQFwq4914Jod2AuagXJ-IZV-bgUf5Ut6RRQ"/>
                </div>
            </div>
        </div>
    </header>

    <main class="ml-0 md:ml-64 p-margin-mobile md:p-margin-desktop transition-all duration-300">
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 text-outline mb-2">
                    <span class="font-label-bold text-label-bold uppercase tracking-widest">Orders</span>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    <span class="font-label-bold text-label-bold uppercase tracking-widest text-primary">Fulfillment</span>
                </div>
                <h3 class="font-headline-lg text-headline-lg text-primary uppercase">Order Management</h3>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('admin.comics.index') }}" class="px-8 py-3 bg-surface-container-highest text-primary font-label-bold text-label-bold hover:bg-surface-variant transition-colors rounded-lg">VIEW INVENTORY</a>
                <a href="#" class="px-8 py-3 bg-primary text-pure-white font-label-bold text-label-bold hover:bg-energy-lime hover:text-primary transition-all shadow-[0_12px_24px_rgba(32,42,54,0.12)] rounded-lg">EXPORT CSV</a>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-surface-container-lowest border border-outline-variant text-primary px-6 py-4 mb-6 font-bold rounded-lg shadow-[0px_32px_32px_rgba(32,42,54,0.04)]">
            {{ session('success') }}
        </div>
        @endif

        <form id="filtersForm" method="GET" action="{{ route('admin.orders.index') }}" class="mb-8">
            <div class="bg-pure-white rounded-xl shadow-[32px_32px_64px_rgba(32,42,54,0.04)] border border-surface-variant/50 overflow-hidden">
                <div class="px-8 py-6 border-b border-surface-variant flex flex-wrap gap-4 items-center justify-between bg-surface-bright">
                    <div class="flex gap-2 flex-wrap">
                        <button type="submit" class="px-4 py-2 bg-primary text-energy-lime rounded font-label-bold text-label-bold">All Orders</button>
                        <button type="button" class="px-4 py-2 hover:bg-surface-variant rounded font-label-bold text-label-bold transition-colors">Pending</button>
                        <button type="button" class="px-4 py-2 hover:bg-surface-variant rounded font-label-bold text-label-bold transition-colors">Paid</button>
                        <button type="button" class="px-4 py-2 hover:bg-surface-variant rounded font-label-bold text-label-bold transition-colors">Cancelled</button>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="font-label-bold text-label-bold text-on-surface-variant">Sort by:</span>
                        <select name="status" class="bg-surface border border-outline-variant rounded-lg font-label-bold text-label-bold py-2 px-4 focus:ring-energy-lime focus:border-energy-lime">
                            <option value="">Newest First</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-0 border-b border-surface-variant bg-surface-container-low">
                    <div class="p-6 border-r border-surface-variant">
                        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Total Orders</p>
                        <h4 class="font-headline-md text-headline-md text-primary">{{ $orders->total() }}</h4>
                    </div>
                    <div class="p-6 border-r border-surface-variant">
                        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Pending</p>
                        <h4 class="font-headline-md text-headline-md text-focus-zest">{{ \App\Models\Order::where('status', 'pending')->count() }}</h4>
                    </div>
                    <div class="p-6 border-r border-surface-variant">
                        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Paid</p>
                        <h4 class="font-headline-md text-headline-md text-energy-lime">{{ \App\Models\Order::where('status', 'paid')->count() }}</h4>
                    </div>
                    <div class="p-6">
                        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Cancelled</p>
                        <h4 class="font-headline-md text-headline-md text-recovery-berry">{{ \App\Models\Order::where('status', 'cancelled')->count() }}</h4>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-primary text-pure-white">
                            <tr>
                                <th class="px-6 py-5 font-label-bold text-label-bold uppercase tracking-wider">Order</th>
                                <th class="px-6 py-5 font-label-bold text-label-bold uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-5 font-label-bold text-label-bold uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-5 font-label-bold text-label-bold uppercase tracking-wider text-center">Items</th>
                                <th class="px-6 py-5 font-label-bold text-label-bold uppercase tracking-wider text-right">Total</th>
                                <th class="px-6 py-5 font-label-bold text-label-bold uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-5 font-label-bold text-label-bold uppercase tracking-wider text-center">Date</th>
                                <th class="px-6 py-5 font-label-bold text-label-bold uppercase tracking-wider text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-variant">
                            @foreach($orders as $order)
                            <tr class="data-grid-row">
                                <td class="px-6 py-4 font-black text-primary">{{ $order->order_number }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-primary">{{ $order->customer_name }}</p>
                                    <p class="text-sm text-on-surface-variant">DNI: {{ $order->customer_dni }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-on-surface-variant">{{ $order->customer_email }}</td>
                                <td class="px-6 py-4 text-center font-bold">{{ $order->items->count() }}</td>
                                <td class="px-6 py-4 text-right font-black text-lg text-primary">${{ number_format($order->total, 2) }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($order->status === 'pending')
                                        <span class="inline-block bg-focus-zest text-pure-white px-4 py-2 font-black uppercase text-xs rounded-full">Pending</span>
                                    @elseif($order->status === 'paid')
                                        <span class="inline-block bg-energy-lime text-primary px-4 py-2 font-black uppercase text-xs rounded-full">Paid</span>
                                    @else
                                        <span class="inline-block bg-recovery-berry text-pure-white px-4 py-2 font-black uppercase text-xs rounded-full">Cancelled</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-sm">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="inline-block bg-primary text-pure-white px-4 py-2 font-bold uppercase text-xs rounded-lg hover:bg-energy-lime hover:text-primary transition-all">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-8 py-6 border-t border-surface-variant">
                    {{ $orders->links() }}
                </div>
            </div>
        </form>
    </main>
</div>
@endsection