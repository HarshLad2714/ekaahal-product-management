@if (session('success'))
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="alert">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any() && ! request()->routeIs('login', 'login.store', 'admin.products.index', 'admin.products.create', 'admin.products.store', 'admin.products.edit', 'admin.products.update'))
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
        <p class="font-medium">Please correct the following:</p>
        <ul class="mt-1 list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
