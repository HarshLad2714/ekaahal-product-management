@extends('layouts.admin')

@section('title', $product->title)
@section('page-title', $product->title)
@section('page-subtitle', 'Product details')

@section('content')
    <div class="max-w-3xl">
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm lg:p-8">
            <dl class="grid gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-slate-500">Price</dt>
                    <dd class="mt-1 text-lg font-semibold text-slate-900">${{ number_format($product->price, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Date available</dt>
                    <dd class="mt-1 text-lg font-semibold text-slate-900">{{ $product->date_available->format('F j, Y') }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-slate-500">Created by</dt>
                    <dd class="mt-1 text-slate-900">{{ $product->user?->name }} ({{ $product->user?->email }})</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-slate-500">Description</dt>
                    <dd class="prose prose-slate mt-2 max-w-none text-slate-700">
                        {!! $product->description !!}
                    </dd>
                </div>
            </dl>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">Back to list</a>
            @can('update', $product)
                <a href="{{ route('admin.products.edit', $product) }}" class="btn-primary" title="Edit product">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Edit</span>
                </a>
            @endcan
            @can('delete', $product)
                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                      data-confirm="Are you sure you want to delete this product?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger" title="Move to trash">
                        <i class="fa-solid fa-trash-can"></i>
                        <span>Delete</span>
                    </button>
                </form>
            @endcan
        </div>
    </div>
@endsection
