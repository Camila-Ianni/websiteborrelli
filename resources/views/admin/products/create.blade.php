@extends('layouts.admin')

@section('page_title', __('messages.product_create_title'))
@section('page_subtitle', __('messages.product_create_subtitle'))

@section('content')
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-surface rounded-xl p-8 space-y-6">
        @csrf
        @include('admin.products._form')
        <div class="flex justify-end">
            <button class="bg-primary text-on-primary px-6 py-3 rounded-lg font-semibold" type="submit">
                {{ __('messages.save_product') }}
            </button>
        </div>
    </form>
@endsection
