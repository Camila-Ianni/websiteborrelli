@extends('layouts.admin')

@section('page_title', __('messages.admin_dashboard'))
@section('page_subtitle', __('messages.admin_dashboard_subtitle'))

@section('content')
    <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="bg-surface rounded-xl p-6 shadow-sm">
            <p class="text-on-surface-variant text-sm">{{ __('messages.dashboard_revenue') }}</p>
            <p class="text-2xl font-bold text-primary">${{ number_format($totalRevenue, 2, ',', '.') }}</p>
        </div>
        <div class="bg-surface rounded-xl p-6 shadow-sm">
            <p class="text-on-surface-variant text-sm">{{ __('messages.dashboard_orders') }}</p>
            <p class="text-2xl font-bold text-primary">{{ $totalOrders }}</p>
        </div>
        <div class="bg-surface rounded-xl p-6 shadow-sm">
            <p class="text-on-surface-variant text-sm">{{ __('messages.dashboard_customers') }}</p>
            <p class="text-2xl font-bold text-primary">{{ $activeCustomers }}</p>
        </div>
        <div class="bg-surface rounded-xl p-6 shadow-sm">
            <p class="text-on-surface-variant text-sm">{{ __('messages.dashboard_low_stock') }}</p>
            <p class="text-2xl font-bold text-primary">{{ $lowStock }}</p>
        </div>
    </div>

    <div class="bg-surface rounded-xl p-6">
        <h2 class="text-lg font-bold mb-4">{{ __('messages.dashboard_recent_orders') }}</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-on-surface-variant border-b border-outline-variant">
                        <th class="py-3 pr-4">{{ __('messages.order_number') }}</th>
                        <th class="py-3 pr-4">{{ __('messages.customer_name') }}</th>
                        <th class="py-3 pr-4">{{ __('messages.status') }}</th>
                        <th class="py-3 pr-4">{{ __('messages.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentOrders as $order)
                        <tr class="border-b border-outline-variant">
                            <td class="py-3 pr-4">{{ $order->order_number }}</td>
                            <td class="py-3 pr-4">{{ $order->customer_name }}</td>
                            <td class="py-3 pr-4">{{ ucfirst($order->status) }}</td>
                            <td class="py-3 pr-4">${{ number_format($order->total, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
