@extends('layouts.admin')

@section('page_title', __('messages.order_detail_title', ['order' => $order->order_number]))
@section('page_subtitle', __('messages.order_detail_subtitle'))

@section('content')
    <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
        <div class="bg-surface rounded-xl p-6">
            <h2 class="text-lg font-bold mb-4">{{ __('messages.order_items') }}</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-on-surface-variant border-b border-outline-variant">
                            <th class="py-3 pr-4">{{ __('messages.product_title') }}</th>
                            <th class="py-3 pr-4">{{ __('messages.quantity') }}</th>
                            <th class="py-3 pr-4">{{ __('messages.product_price') }}</th>
                            <th class="py-3 pr-4">{{ __('messages.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr class="border-b border-outline-variant">
                                <td class="py-3 pr-4">{{ $item->product?->title ?? __('messages.product_deleted') }}</td>
                                <td class="py-3 pr-4">{{ $item->quantity }}</td>
                                <td class="py-3 pr-4">${{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                <td class="py-3 pr-4">${{ number_format($item->total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-surface rounded-xl p-6 space-y-6">
            <div class="space-y-2 text-sm">
                <p><span class="font-semibold">{{ __('messages.customer_name') }}:</span> {{ $order->customer_name }}</p>
                <p><span class="font-semibold">{{ __('messages.customer_email') }}:</span> {{ $order->customer_email }}</p>
                <p><span class="font-semibold">{{ __('messages.customer_phone') }}:</span> {{ $order->customer_phone }}</p>
                <p><span class="font-semibold">{{ __('messages.payment_method') }}:</span> {{ ucfirst($order->payment_method) }}</p>
                <p><span class="font-semibold">{{ __('messages.total') }}:</span> ${{ number_format($order->total, 2, ',', '.') }}</p>
            </div>

            <form class="space-y-4" method="POST" action="{{ route('admin.orders.update', $order) }}">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-semibold mb-2">{{ __('messages.status') }}</label>
                    <select class="w-full rounded-lg border-outline-variant bg-surface px-4 py-2" name="status">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected(old('status', $order->status) === $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button class="w-full bg-primary text-on-primary px-4 py-2 rounded font-semibold" type="submit">
                    {{ __('messages.update_order') }}
                </button>
            </form>
        </div>
    </div>
@endsection
