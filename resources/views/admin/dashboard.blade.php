@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview of your product catalog')

@section('content')
    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total products</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['products'] }}</p>
            @if (! auth()->user()->isAdmin())
                <p class="mt-1 text-xs text-slate-400">Visible across the system</p>
            @endif
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Your products</p>
            <p class="mt-2 text-3xl font-bold text-brand-600">{{ $stats['my_products'] }}</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:col-span-2 xl:col-span-1">
            <p class="text-sm font-medium text-slate-500">Your role</p>
            <p class="mt-2 text-xl font-semibold text-slate-900">{{ auth()->user()->role->label() }}</p>
            <p class="mt-2 text-sm text-slate-500">
                @if (auth()->user()->isAdmin())
                    Full access to all products and records.
                @else
                    You can manage only products you create.
                @endif
            </p>
        </div>
    </div>

    <div class="mt-8 flex flex-wrap gap-3">
        <a href="{{ route('admin.products.index') }}" class="btn-secondary">View all products</a>
        <a href="{{ route('admin.products.create') }}" class="btn-primary">Add new product</a>
    </div>
@endsection
