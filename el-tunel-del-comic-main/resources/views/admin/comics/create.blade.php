@extends('layouts.app')

@section('title', 'Product Editor | ELITE LABS')

@section('content')
<div class="min-h-screen bg-surface text-on-surface font-body-md overflow-x-hidden">
    <aside class="hidden md:flex flex-col h-full py-8 bg-primary dark:bg-pure-black shadow-[32px_0_32px_rgba(32,42,54,0.06)] h-screen w-64 fixed left-0 top-0 z-50">
        <div class="px-6 mb-12">
            <h1 class="font-display-xl text-display-xl tracking-tighter text-pure-white leading-none">ELITE LABS</h1>
            <p class="font-label-bold text-label-bold text-energy-lime mt-1 tracking-widest uppercase">Admin Terminal</p>
        </div>
        <nav class="flex-1 space-y-1">
            <a class="flex items-center gap-4 px-6 py-4 text-surface-variant hover:text-pure-white transition-colors hover:bg-primary-container" href="{{ route('dashboard') }}"><span class="material-symbols-outlined">dashboard</span><span class="font-label-bold text-label-bold">Dashboard</span></a>
            <a class="flex items-center gap-4 px-6 py-4 bg-primary-container text-energy-lime border-r-4 border-energy-lime shadow-sm transition-all duration-150 ease-in-out" href="{{ route('admin.comics.index') }}"><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">inventory_2</span><span class="font-label-bold text-label-bold">Inventory</span></a>
            <a class="flex items-center gap-4 px-6 py-4 text-surface-variant hover:text-pure-white transition-colors hover:bg-primary-container" href="{{ route('admin.orders.index') }}"><span class="material-symbols-outlined">shopping_cart</span><span class="font-label-bold text-label-bold">Orders</span></a>
            <a class="flex items-center gap-4 px-6 py-4 text-surface-variant hover:text-pure-white transition-colors hover:bg-primary-container" href="#"><span class="material-symbols-outlined">group</span><span class="font-label-bold text-label-bold">Customers</span></a>
            <a class="flex items-center gap-4 px-6 py-4 text-surface-variant hover:text-pure-white transition-colors hover:bg-primary-container" href="#"><span class="material-symbols-outlined">settings</span><span class="font-label-bold text-label-bold">Settings</span></a>
        </nav>
        <div class="mt-auto px-6 pt-8 border-t border-primary-container">
            <a href="{{ route('home') }}" class="w-full bg-energy-lime text-primary font-label-bold text-label-bold py-3 mb-6 hover:brightness-110 transition-all flex items-center justify-center rounded-lg">View Storefront</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="flex items-center gap-4 py-4 text-surface-variant hover:text-recovery-berry transition-colors"><span class="material-symbols-outlined">logout</span><span class="font-label-bold text-label-bold">Log Out</span></button></form>
        </div>
    </aside>

    <header class="flex justify-between items-center ml-0 md:ml-64 px-margin-mobile md:px-margin-desktop w-full md:w-[calc(100%-16rem)] bg-surface border-b border-surface-variant h-20 sticky top-0 z-40">
        <div class="flex items-center gap-8 flex-1">
            <h2 class="md:hidden font-headline-md text-headline-md font-extrabold text-primary">ELITE LABS</h2>
            <div class="hidden md:flex flex-1 max-w-md relative group">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                <input class="w-full pl-10 pr-4 py-2 bg-surface-container-low border-none focus:ring-2 focus:ring-energy-lime font-body-md text-body-md" placeholder="Search product SKU or name..." type="text"/>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <nav class="hidden lg:flex items-center gap-8"><a class="font-label-bold text-label-bold text-on-surface-variant hover:text-focus-zest" href="#">Global Reports</a><a class="font-label-bold text-label-bold text-on-surface-variant hover:text-focus-zest" href="#">System Status</a></nav>
            <div class="flex items-center gap-4"><button class="material-symbols-outlined p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors">notifications</button><div class="h-10 w-10 bg-primary-container rounded-full flex items-center justify-center text-energy-lime overflow-hidden"><img alt="Elite Labs Admin Profile" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCEDVYtuiGkI92uexBWSiLxMKZI94q2t4kiCtXNgXE2KtRTh4_ACQwIKh4UEQHt80uxCNXsWplb9g7UrkhadMO28gy-velzV4514guJKnHgEIT62b19mDLwNRGbgEdKIfSkqSOuCECEqW2JqRr7b-4t0ru4Z-JLOl2aMRNPyuz8qXtBAuF02doBpHJ0UEyuQ-AWcc5wBs2AxUWqIZ-7PUD1qdM6X9S4PYk2wQ-LAX_SQFwq4914Jod2AuagXJ-IZV-bgUf5Ut6RRQ"/></div></div>
        </div>
    </header>

    <main class="ml-0 md:ml-64 p-margin-mobile md:p-margin-desktop transition-all duration-300">
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 text-outline mb-2">
                    <span class="font-label-bold text-label-bold uppercase tracking-widest">Inventory</span>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    <span class="font-label-bold text-label-bold uppercase tracking-widest text-primary">Create Product</span>
                </div>
                <h3 class="font-headline-lg text-headline-lg text-primary uppercase">Product Editor</h3>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('admin.comics.index') }}" class="px-8 py-3 bg-surface-container-highest text-primary font-label-bold text-label-bold hover:bg-surface-variant transition-colors rounded-lg">DISCARD</a>
                <button form="productForm" type="submit" class="px-8 py-3 bg-primary text-pure-white font-label-bold text-label-bold hover:bg-energy-lime hover:text-primary transition-all shadow-[0_12px_24px_rgba(32,42,54,0.12)] rounded-lg">SAVE PRODUCT</button>
            </div>
        </div>

        @if($errors->any())
        <div class="bg-surface-container-lowest border border-recovery-berry text-primary px-6 py-4 mb-6 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li class="font-bold">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="productForm" action="{{ route('admin.comics.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-12 gap-gutter">
            @csrf
            <div class="col-span-12 lg:col-span-8 space-y-8">
                <section class="bg-pure-white p-8 shadow-[0_32px_32px_rgba(32,42,54,0.04)] rounded-xl">
                    <h4 class="font-headline-md text-headline-md text-primary mb-8 border-l-4 border-energy-lime pl-4">General Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="flex flex-col gap-2">
                            <label class="font-label-bold text-label-bold text-outline">PRODUCT NAME</label>
                            <input class="w-full bg-surface border-0 border-b-2 border-surface-variant py-3 font-body-md text-body-md focus:border-energy-lime transition-all" name="title" required type="text" value="{{ old('title') }}"/>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-label-bold text-label-bold text-outline">PUBLISHER</label>
                            <select class="w-full bg-surface border-0 border-b-2 border-surface-variant py-3 font-body-md text-body-md focus:border-energy-lime transition-all appearance-none" name="publisher_id" required>
                                <option value="">Select publisher</option>
                                @foreach($publishers as $publisher)
                                    <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>{{ $publisher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-full flex flex-col gap-2">
                            <label class="font-label-bold text-label-bold text-outline">DESCRIPTION</label>
                            <textarea class="w-full bg-surface border-0 border-b-2 border-surface-variant py-3 font-body-md text-body-md focus:border-energy-lime transition-all" name="description" rows="4">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </section>

                <section class="bg-pure-white p-8 shadow-[0_32px_32px_rgba(32,42,54,0.04)] rounded-xl">
                    <h4 class="font-headline-md text-headline-md text-primary mb-8 border-l-4 border-energy-lime pl-4">Market &amp; Logistics</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="flex flex-col gap-2">
                            <label class="font-label-bold text-label-bold text-outline">BASE PRICE ($)</label>
                            <div class="relative">
                                <input class="w-full bg-surface border-0 border-b-2 border-surface-variant py-3 font-body-md text-body-md focus:border-energy-lime transition-all" name="price" required type="number" step="0.01" value="{{ old('price') }}"/>
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 text-outline-variant font-label-bold text-label-bold">USD</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-label-bold text-label-bold text-outline">STOCK LEVEL</label>
                            <input class="w-full bg-surface border-0 border-b-2 border-surface-variant py-3 font-body-md text-body-md focus:border-energy-lime transition-all" name="stock" required type="number" value="{{ old('stock', 0) }}"/>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-label-bold text-label-bold text-outline">SKU ID</label>
                            <input class="w-full bg-surface border-0 border-b-2 border-surface-variant py-3 font-body-md text-body-md focus:border-energy-lime transition-all" name="isbn" type="text" value="{{ old('isbn') }}"/>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-label-bold text-label-bold text-outline">PAGES</label>
                            <input class="w-full bg-surface border-0 border-b-2 border-surface-variant py-3 font-body-md text-body-md focus:border-energy-lime transition-all" name="pages" type="number" value="{{ old('pages') }}"/>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-label-bold text-label-bold text-outline">LANGUAGE</label>
                            <select class="w-full bg-surface border-0 border-b-2 border-surface-variant py-3 font-body-md text-body-md focus:border-energy-lime transition-all appearance-none" name="language" required>
                                <option value="es" {{ old('language') == 'es' ? 'selected' : '' }}>Español</option>
                                <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                                <option value="ja" {{ old('language') == 'ja' ? 'selected' : '' }}>Japanese</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-label-bold text-label-bold text-outline">PUBLICATION DATE</label>
                            <input class="w-full bg-surface border-0 border-b-2 border-surface-variant py-3 font-body-md text-body-md focus:border-energy-lime transition-all" name="publication_date" type="date" value="{{ old('publication_date') }}"/>
                        </div>
                    </div>
                </section>

                <section class="bg-pure-white p-8 shadow-[0_32px_32px_rgba(32,42,54,0.04)] rounded-xl">
                    <h4 class="font-headline-md text-headline-md text-primary mb-8 border-l-4 border-energy-lime pl-4">Flags</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-center gap-3 bg-surface p-4 rounded-lg border border-outline-variant">
                            <input class="w-5 h-5 accent-primary" name="is_featured" type="checkbox" value="1" {{ old('is_featured') ? 'checked' : '' }}/>
                            <span class="font-bold">Featured</span>
                        </label>
                        <label class="flex items-center gap-3 bg-surface p-4 rounded-lg border border-outline-variant">
                            <input class="w-5 h-5 accent-primary" name="is_new_release" type="checkbox" value="1" {{ old('is_new_release') ? 'checked' : '' }}/>
                            <span class="font-bold">New Release</span>
                        </label>
                        <label class="flex items-center gap-3 bg-surface p-4 rounded-lg border border-outline-variant">
                            <input class="w-5 h-5 accent-primary" name="is_active" type="checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }}/>
                            <span class="font-bold">Active</span>
                        </label>
                    </div>
                </section>
            </div>

            <div class="col-span-12 lg:col-span-4 space-y-8">
                <section class="bg-pure-white p-8 shadow-[0_32px_32px_rgba(32,42,54,0.04)] rounded-xl">
                    <h4 class="font-headline-md text-headline-md text-primary mb-8 border-l-4 border-energy-lime pl-4">Artwork</h4>
                    <input class="w-full bg-surface border border-outline-variant rounded-lg p-4" name="image" type="file" accept="image/*"/>
                    <p class="text-sm text-on-surface-variant mt-3">Upload the cover image for the product card and detail page.</p>
                </section>

                <section class="bg-primary text-pure-white p-8 shadow-[0_32px_32px_rgba(32,42,54,0.04)] rounded-xl">
                    <h4 class="font-headline-md text-headline-md text-energy-lime mb-6">Quick Note</h4>
                    <p class="text-sm text-surface-variant leading-6">This screen keeps the full Laravel create flow, but the structure and surfaces follow the external HTML product editor as closely as possible.</p>
                </section>
            </div>
        </form>
    </main>
</div>
@endsection