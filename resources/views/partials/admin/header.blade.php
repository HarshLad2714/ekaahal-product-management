<header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 bg-white/90 px-6 backdrop-blur lg:px-8">
    <div>
        <h1 class="text-lg font-semibold text-slate-900">@yield('page-title', 'Dashboard')</h1>
        @hasSection('page-subtitle')
            <p class="text-sm text-slate-500">@yield('page-subtitle')</p>
        @endif
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-secondary text-xs">
            Sign out
        </button>
    </form>
</header>
