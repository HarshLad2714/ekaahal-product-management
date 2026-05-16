<aside class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col border-r border-slate-800 bg-surface-900 lg:flex">
    <div class="flex h-16 items-center gap-3 border-b border-slate-800 px-6">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-600 text-sm font-bold text-white">E</div>
        <div>
            <p class="text-sm font-semibold text-white">Ekahal</p>
            <p class="text-xs text-slate-400">Product Management</p>
        </div>
    </div>

    <nav class="flex-1 space-y-1 px-3 py-4">
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }} flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition">
            <svg class="h-5 w-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>
        <a href="{{ route('admin.products.index') }}"
           class="{{ request()->routeIs('admin.products.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }} flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition">
            <svg class="h-5 w-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Products
        </a>
    </nav>

    <div class="border-t border-slate-800 p-4">
        <div class="rounded-lg bg-slate-800/50 px-3 py-3">
            <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
            <p class="truncate text-xs text-slate-400">{{ auth()->user()->email }}</p>
            <span class="mt-2 inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ auth()->user()->isAdmin() ? 'bg-brand-600/20 text-brand-100' : 'bg-slate-700 text-slate-300' }}">
                {{ auth()->user()->role->label() }}
            </span>
        </div>
    </div>
</aside>
