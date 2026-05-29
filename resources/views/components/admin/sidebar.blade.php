<aside class="w-72 bg-primary text-on-primary flex flex-col px-6 py-8">
    <div class="text-3xl font-headline font-extrabold mb-10">{{ __('messages.admin_brand') }}</div>
    <nav class="flex flex-col gap-4 text-sm">
        <a class="px-4 py-3 rounded bg-primary-container hover:bg-energy-lime hover:text-primary transition" href="{{ route('admin.dashboard') }}">
            {{ __('messages.admin_dashboard') }}
        </a>
        <a class="px-4 py-3 rounded bg-primary-container hover:bg-energy-lime hover:text-primary transition" href="{{ route('admin.products.index') }}">
            {{ __('messages.admin_products') }}
        </a>
        <a class="px-4 py-3 rounded bg-primary-container hover:bg-energy-lime hover:text-primary transition" href="{{ route('admin.orders.index') }}">
            {{ __('messages.admin_orders') }}
        </a>
    </nav>
</aside>
