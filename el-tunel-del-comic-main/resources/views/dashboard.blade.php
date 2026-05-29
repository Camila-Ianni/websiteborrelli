@extends('layouts.app')

@section('title', 'ELITE LABS | Admin Terminal')

@section('content')
<div class="min-h-screen bg-background text-on-surface font-body-md antialiased">
    <!-- SideNavBar Shell -->
    <aside class="h-screen w-64 fixed left-0 top-0 bg-primary dark:bg-pure-black shadow-[32px_0_32px_rgba(32,42,54,0.06)] flex flex-col h-full py-8 z-50">
        <div class="px-6 mb-10">
            <h1 class="font-display-xl text-[32px] tracking-tighter text-pure-white leading-none">ELITE LABS</h1>
            <p class="font-label-bold text-label-bold text-energy-lime uppercase mt-1">Admin Terminal</p>
        </div>
        <nav class="flex-1 space-y-1">
            <a class="flex items-center gap-4 px-6 py-4 bg-primary-container text-energy-lime border-r-4 border-energy-lime transition-colors" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-label-bold text-label-bold">Dashboard</span>
            </a>
            <a class="flex items-center gap-4 px-6 py-4 text-surface-variant hover:text-pure-white hover:bg-primary-container transition-colors" href="{{ route('admin.comics.index') }}">
                <span class="material-symbols-outlined">inventory_2</span>
                <span class="font-label-bold text-label-bold">Inventory</span>
            </a>
            <a class="flex items-center gap-4 px-6 py-4 text-surface-variant hover:text-pure-white hover:bg-primary-container transition-colors" href="{{ route('admin.orders.index') }}">
                <span class="material-symbols-outlined">shopping_cart</span>
                <span class="font-label-bold text-label-bold">Orders</span>
            </a>
            <a class="flex items-center gap-4 px-6 py-4 text-surface-variant hover:text-pure-white hover:bg-primary-container transition-colors" href="#">
                <span class="material-symbols-outlined">group</span>
                <span class="font-label-bold text-label-bold">Customers</span>
            </a>
            <a class="flex items-center gap-4 px-6 py-4 text-surface-variant hover:text-pure-white hover:bg-primary-container transition-colors" href="#">
                <span class="material-symbols-outlined">settings</span>
                <span class="font-label-bold text-label-bold">Settings</span>
            </a>
        </nav>
        <div class="px-6 mt-auto">
            <a href="{{ route('home') }}" class="block w-full bg-energy-lime text-primary py-3 rounded-lg font-label-bold flex items-center justify-center gap-2 hover:brightness-110 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-[20px]">open_in_new</span>
                View Storefront
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="flex items-center gap-4 py-6 text-surface-variant hover:text-recovery-berry transition-colors border-t border-white/10 w-full">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-label-bold text-label-bold">Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="ml-64 min-h-screen">
        <!-- TopNavBar Shell -->
        <header class="docked full-width h-20 top-0 sticky z-40 bg-surface dark:bg-background border-b border-surface-variant flex justify-between items-center px-margin-desktop w-full glass-panel">
            <div class="flex items-center gap-8 flex-1">
                <div class="relative w-full max-w-md">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input class="w-full bg-surface-container-low border-none rounded-full py-2.5 pl-12 pr-4 focus:ring-2 focus:ring-energy-lime font-body-md" placeholder="Search terminal..." type="text"/>
                </div>
                <div class="hidden lg:flex items-center gap-6">
                    <a class="text-primary dark:text-energy-lime font-bold border-b-2 border-primary dark:border-energy-lime pb-1 font-label-bold" href="#">Global Reports</a>
                    <a class="text-on-surface-variant dark:text-surface-variant hover:text-focus-zest transition-all font-label-bold" href="#">System Status</a>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button class="p-2 text-on-surface-variant hover:bg-surface-container rounded-full transition-colors relative">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-recovery-berry rounded-full"></span>
                </button>
                <div class="h-10 w-10 rounded-full overflow-hidden border-2 border-surface-variant">
                    <img class="w-full h-full object-cover" alt="Admin Profile" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCN-_hPSr2QI_e4eWkDb_LzGf28KxIDCapPfBUIOsWVo1zhNILmnSpoXJMol4D74XNw-QlJQd_lxRkISctsrl_qYZ6N2zhEQp3aMmI1Wqn5qv2JzFdxEULrYUyp41LCawIzDW1CcHSnC8Z_DnxgXN8kPErQRAQnmn6FlSnsDL2hZQRC4q00AyP1OzsccLCzzKYeV1kBIpec0w6v_vZ4e3qYVBewZ8Y2pdOVr6SB47B1LI9N8GIPnZSUjWs5HSdIswXrava-KiF57A"/>
                </div>
            </div>
        </header>

        <!-- Dashboard Canvas -->
        <div class="p-margin-desktop">
            <div class="mb-10">
                <h2 class="font-headline-lg text-headline-lg text-primary">Performance Overview</h2>
                <p class="text-on-surface-variant font-body-lg">Real-time metrics from the global distribution network.</p>
            </div>

            <!-- KPI Bento Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter mb-gutter">
                <div class="bg-pure-white p-8 rounded-xl shadow-[32px_0_32px_rgba(32,42,54,0.04)] border-l-4 border-energy-lime flex flex-col justify-between group hover:shadow-[32px_0_32px_rgba(32,42,54,0.08)] transition-all">
                    <div>
                        <p class="font-label-bold text-label-bold text-on-surface-variant uppercase tracking-widest mb-2">Total Revenue</p>
                        <h3 class="font-display-xl text-4xl text-primary">$1,284,592</h3>
                    </div>
                    <div class="flex items-center gap-2 mt-4 text-green-600 font-label-bold">
                        <span class="material-symbols-outlined text-[18px]">trending_up</span>
                        <span>+12.4% vs last mo.</span>
                    </div>
                </div>
                <div class="bg-pure-white p-8 rounded-xl shadow-[32px_0_32px_rgba(32,42,54,0.04)] border-l-4 border-primary flex flex-col justify-between group hover:shadow-[32px_0_32px_rgba(32,42,54,0.08)] transition-all">
                    <div>
                        <p class="font-label-bold text-label-bold text-on-surface-variant uppercase tracking-widest mb-2">Total Orders</p>
                        <h3 class="font-display-xl text-4xl text-primary">8,432</h3>
                    </div>
                    <div class="flex items-center gap-2 mt-4 text-green-600 font-label-bold">
                        <span class="material-symbols-outlined text-[18px]">trending_up</span>
                        <span>+5.2% vs last mo.</span>
                    </div>
                </div>
                <div class="bg-pure-white p-8 rounded-xl shadow-[32px_0_32px_rgba(32,42,54,0.04)] border-l-4 border-focus-zest flex flex-col justify-between group hover:shadow-[32px_0_32px_rgba(32,42,54,0.08)] transition-all">
                    <div>
                        <p class="font-label-bold text-label-bold text-on-surface-variant uppercase tracking-widest mb-2">Active Customers</p>
                        <h3 class="font-display-xl text-4xl text-primary">45.2k</h3>
                    </div>
                    <div class="flex items-center gap-2 mt-4 text-green-600 font-label-bold">
                        <span class="material-symbols-outlined text-[18px]">group</span>
                        <span>1.2k new this week</span>
                    </div>
                </div>
                <div class="bg-primary p-8 rounded-xl shadow-[32px_0_32px_rgba(32,42,54,0.04)] flex flex-col justify-between group hover:shadow-[32px_0_32px_rgba(32,42,54,0.08)] transition-all relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="font-label-bold text-label-bold text-surface-variant uppercase tracking-widest mb-2">Low Stock Alerts</p>
                        <h3 class="font-display-xl text-4xl text-energy-lime">14</h3>
                    </div>
                    <div class="flex items-center gap-2 mt-4 text-recovery-berry font-label-bold relative z-10">
                        <span class="material-symbols-outlined text-[18px]">warning</span>
                        <span>Immediate action required</span>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-10">
                        <span class="material-symbols-outlined text-[120px]">inventory</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter items-start">
                <div class="lg:col-span-2 bg-pure-white p-8 rounded-xl shadow-[32px_0_32px_rgba(32,42,54,0.04)]">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h4 class="font-headline-md text-headline-md text-primary">Sales Performance</h4>
                            <p class="text-on-surface-variant font-label-bold">Projected Revenue vs Actuals</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="px-4 py-2 bg-surface-container rounded-lg font-label-bold text-primary hover:bg-energy-lime transition-all">Week</button>
                            <button class="px-4 py-2 bg-primary text-energy-lime rounded-lg font-label-bold">Month</button>
                        </div>
                    </div>
                    <div class="h-[400px] w-full relative">
                        <div class="absolute inset-x-0 bottom-0 h-3/4 chart-gradient rounded-t-2xl overflow-hidden">
                            <div class="absolute bottom-0 left-0 right-0 h-full flex items-end justify-around px-4">
                                <div class="w-16 bg-primary rounded-t-xl h-[45%]"></div>
                                <div class="w-16 bg-energy-lime rounded-t-xl h-[78%]"></div>
                                <div class="w-16 bg-primary rounded-t-xl h-[55%]"></div>
                                <div class="w-16 bg-energy-lime rounded-t-xl h-[88%]"></div>
                                <div class="w-16 bg-primary rounded-t-xl h-[70%]"></div>
                                <div class="w-16 bg-energy-lime rounded-t-xl h-[96%]"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-gutter">
                    <div class="bg-pure-white p-8 rounded-xl shadow-[32px_0_32px_rgba(32,42,54,0.04)]">
                        <h4 class="font-headline-md text-headline-md text-primary mb-6">Top Products</h4>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="font-label-bold text-label-bold uppercase">ISO Shark Whey</span>
                                <span class="text-energy-lime font-black">84%</span>
                            </div>
                            <div class="h-2 bg-surface-container rounded-full overflow-hidden"><div class="h-full w-[84%] bg-energy-lime"></div></div>
                            <div class="flex items-center justify-between">
                                <span class="font-label-bold text-label-bold uppercase">Amino Burst</span>
                                <span class="text-primary font-black">61%</span>
                            </div>
                            <div class="h-2 bg-surface-container rounded-full overflow-hidden"><div class="h-full w-[61%] bg-primary"></div></div>
                            <div class="flex items-center justify-between">
                                <span class="font-label-bold text-label-bold uppercase">Crea-Pure 500</span>
                                <span class="text-recovery-berry font-black">53%</span>
                            </div>
                            <div class="h-2 bg-surface-container rounded-full overflow-hidden"><div class="h-full w-[53%] bg-recovery-berry"></div></div>
                        </div>
                    </div>

                    <div class="bg-pure-white p-8 rounded-xl shadow-[32px_0_32px_rgba(32,42,54,0.04)]">
                        <h4 class="font-headline-md text-headline-md text-primary mb-6">Notifications</h4>
                        <div class="space-y-4">
                            <div class="flex gap-4 items-start">
                                <span class="material-symbols-outlined text-energy-lime">local_shipping</span>
                                <p class="text-sm text-on-surface-variant">4 shipments were dispatched in the last hour.</p>
                            </div>
                            <div class="flex gap-4 items-start">
                                <span class="material-symbols-outlined text-focus-zest">warning</span>
                                <p class="text-sm text-on-surface-variant">7 products need restocking today.</p>
                            </div>
                            <div class="flex gap-4 items-start">
                                <span class="material-symbols-outlined text-recovery-berry">payments</span>
                                <p class="text-sm text-on-surface-variant">One pending payment requires review.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection