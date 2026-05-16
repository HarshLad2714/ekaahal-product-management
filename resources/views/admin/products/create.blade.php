@extends('layouts.admin')

@section('title', 'New Product')
@section('page-title', 'New product')
@section('page-subtitle', 'Add a new item to the catalog')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.1.15/dist/trix.min.css">
@endpush

@section('content')
    <div class="max-w-3xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm lg:p-8">
        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600" role="alert">
                <p class="font-medium">Please fix the errors below and try again.</p>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.store') }}" novalidate>
            @csrf
            @include('admin.products._form')
            <div class="mt-8 flex gap-3">
                <button type="submit" class="btn-primary">Create product</button>
                <a href="{{ route('admin.products.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/trix@2.1.15/dist/trix.umd.min.js"></script>
@endpush
