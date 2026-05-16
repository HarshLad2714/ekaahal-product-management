@extends('layouts.admin')

@section('title', 'Products')
@section('page-title', 'Products')
@section('page-subtitle', 'Search, filter, and manage your catalog')

@section('content')
    <div class="mb-6 flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex-1 space-y-3" novalidate>
            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
                <div class="min-w-[200px] flex-1 sm:max-w-md">
                    <label for="search" class="admin-label">Search</label>
                    <input type="search" id="search" name="search" value="{{ request('search') }}"
                           placeholder="Title or description…"
                           class="admin-input @error('search') input-error @enderror">
                    @include('partials.field-error', ['field' => 'search'])
                </div>

                <div class="w-full sm:w-auto">
                    <label for="available_from" class="admin-label">Available from</label>
                    <input type="date" id="available_from" name="available_from"
                           value="{{ request('available_from') }}"
                           class="admin-input @error('available_from') input-error @enderror">
                    @include('partials.field-error', ['field' => 'available_from'])
                </div>

                <div class="w-full sm:w-auto">
                    <label for="available_to" class="admin-label">Available to</label>
                    <input type="date" id="available_to" name="available_to"
                           value="{{ request('available_to') }}"
                           class="admin-input @error('available_to') input-error @enderror">
                    @include('partials.field-error', ['field' => 'available_to'])
                </div>

                <div class="flex gap-2 pb-0.5">
                    <button type="submit" class="btn-primary">Apply filters</button>
                    @if (request()->hasAny(['search', 'available_from', 'available_to']))
                        <a href="{{ route('admin.products.index') }}" class="btn-secondary">Clear</a>
                    @endif
                </div>
            </div>
        </form>

        <a href="{{ route('admin.products.create') }}" class="btn-primary shrink-0 self-start xl:self-auto">New product</a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-slate-600">Title</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-600">Price</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-600">Available</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-600">Owner</th>
                        <th class="px-6 py-3 text-right font-semibold text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($products as $product)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.products.show', $product) }}" class="font-medium text-brand-600 hover:text-brand-700">
                                    {{ $product->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-600">${{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $product->date_available->format('M j, Y') }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $product->user?->name }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end items-center gap-1">
                                    @can('update', $product)
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-brand-600 transition hover:bg-brand-50"
                                           title="Edit product"
                                           aria-label="Edit product">
                                            <i class="fa-solid fa-pen-to-square text-base"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $product)
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline"
                                              data-confirm="Move this product to trash?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 transition hover:bg-red-50"
                                                    title="Move to trash"
                                                    aria-label="Move to trash">
                                                <i class="fa-solid fa-trash-can text-base"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                No products found.
                                @if (request()->hasAny(['search', 'available_from', 'available_to']))
                                    Try adjusting your search or date filters.
                                @else
                                    <a href="{{ route('admin.products.create') }}" class="text-brand-600 hover:underline">Create your first product</a>.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
