@extends('layouts.admin')

@section('page_title', __('messages.admin_orders'))
@section('page_subtitle', __('messages.admin_orders_subtitle'))

@section('content')
    <div class="bg-surface rounded-xl p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-on-surface-variant border-b border-outline-variant">
                        <th class="py-3 pr-4">{{ __('messages.order_number') }}</th>
                        <th class="py-3 pr-4">{{ __('messages.customer_name') }}</th>
                        <th class="py-3 pr-4">{{ __('messages.payment_method') }}</th>
                        <th class="py-3 pr-4">{{ __('messages.status') }}</th>
                        <th class="py-3 pr-4">{{ __('messages.total') }}</th>
                        <th class="py-3 pr-4">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="border-b border-outline-variant">
                            <td class="py-3 pr-4">{{ $order->order_number }}</td>
                            <td class="py-3 pr-4">{{ $order->customer_name }}</td>
                            <td class="py-3 pr-4">{{ ucfirst($order->payment_method) }}</td>
                            <td class="py-3 pr-4">{{ ucfirst($order->status) }}</td>
                            <td class="py-3 pr-4">${{ number_format($order->total, 2, ',', '.') }}</td>
                            <td class="py-3 pr-4">
                                <a class="text-primary font-semibold" href="{{ route('admin.orders.show', $order) }}">
                                    {{ __('messages.view') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pt-4">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
