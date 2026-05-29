@extends('layouts.admin')

@section('page_title', __('messages.product_edit_title'))
@section('page_subtitle', __('messages.product_edit_subtitle'))

@section('content')
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-surface rounded-xl p-8 space-y-6">
        @csrf
        @method('PUT')
        @include('admin.products._form', ['product' => $product])
        <div class="flex justify-end gap-3">
            <a class="px-6 py-3 rounded-lg border border-outline-variant" href="{{ route('admin.products.index') }}">
                {{ __('messages.cancel') }}
            </a>
            <button class="bg-primary text-on-primary px-6 py-3 rounded-lg font-semibold" type="submit">
                {{ __('messages.update_product') }}
            </button>
        </div>
    </form>
@endsection
