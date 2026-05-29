@extends('layouts.admin')

@section('page_title', __('messages.admin_products'))
@section('page_subtitle', __('messages.admin_products_subtitle'))

@section('content')
    <div class="flex justify-between items-center mb-6">
        <a class="bg-primary text-on-primary px-6 py-3 rounded-lg font-semibold" href="{{ route('admin.products.create') }}">
            {{ __('messages.product_new') }}
        </a>
    </div>

    <form action="{{ route('admin.products.bulk') }}" method="POST" class="bg-surface rounded-xl p-6 mb-6 space-y-4">
        @csrf
        <div class="flex flex-wrap gap-4 items-center">
            <select name="action" class="rounded-lg border-outline-variant bg-surface px-4 py-2">
                <option value="activate">{{ __('messages.bulk_activate') }}</option>
                <option value="deactivate">{{ __('messages.bulk_deactivate') }}</option>
                <option value="stock">{{ __('messages.bulk_stock') }}</option>
            </select>
            <input type="number" name="stock" min="0" class="rounded-lg border-outline-variant bg-surface px-4 py-2" placeholder="{{ __('messages.bulk_stock_placeholder') }}">
            <button class="bg-primary text-on-primary px-4 py-2 rounded-lg font-semibold" type="submit">
                {{ __('messages.bulk_apply') }}
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-on-surface-variant border-b border-outline-variant">
                        <th class="py-3 pr-4"><input type="checkbox" id="select-all"></th>
                        <th class="py-3 pr-4">{{ __('messages.product_title') }}</th>
                        <th class="py-3 pr-4">{{ __('messages.product_brand') }}</th>
                        <th class="py-3 pr-4">{{ __('messages.product_price') }}</th>
                        <th class="py-3 pr-4">{{ __('messages.product_stock') }}</th>
                        <th class="py-3 pr-4">{{ __('messages.status') }}</th>
                        <th class="py-3 pr-4">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr class="border-b border-outline-variant">
                            <td class="py-3 pr-4">
                                <input type="checkbox" name="selected[]" value="{{ $product->id }}">
                            </td>
                            <td class="py-3 pr-4">{{ $product->title }}</td>
                            <td class="py-3 pr-4">{{ $product->brand->name }}</td>
                            <td class="py-3 pr-4">${{ number_format($product->price, 2, ',', '.') }}</td>
                            <td class="py-3 pr-4">{{ $product->stock }}</td>
                            <td class="py-3 pr-4">
                                <span class="px-2 py-1 rounded-full text-xs {{ $product->is_active ? 'bg-energy-lime text-primary' : 'bg-outline-variant text-on-surface' }}">
                                    {{ $product->is_active ? __('messages.active') : __('messages.inactive') }}
                                </span>
                            </td>
                            <td class="py-3 pr-4 flex gap-3">
                                <a class="text-primary font-semibold" href="{{ route('admin.products.edit', $product) }}">{{ __('messages.edit') }}</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-recovery-berry font-semibold" type="submit">
                                        {{ __('messages.delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pt-4">
            {{ $products->links() }}
        </div>
    </form>

    @push('scripts')
        <script>
            const selectAll = document.getElementById('select-all');
            if (selectAll) {
                selectAll.addEventListener('change', (event) => {
                    document.querySelectorAll('input[name="selected[]"]').forEach((checkbox) => {
                        checkbox.checked = event.target.checked;
                    });
                });
            }
        </script>
    @endpush
@endsection
