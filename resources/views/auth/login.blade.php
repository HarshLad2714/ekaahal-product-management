@extends('layouts.guest')

@section('title', 'Sign in')

@section('content')
    <div class="w-full max-w-md">
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-600 text-xl font-bold text-white shadow-lg shadow-brand-600/30">E</div>
            <h1 class="text-2xl font-bold text-white">Welcome back</h1>
            <p class="mt-1 text-sm text-slate-300">Sign in to Ekahal Product Management</p>
        </div>

        <div class="rounded-2xl border border-slate-700/50 bg-slate-800/50 p-8 shadow-xl backdrop-blur">
            @if ($errors->any())
                <div class="mb-5 rounded-lg border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-300" role="alert">
                    <p class="font-medium">Please fix the errors below and try again.</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-5" novalidate>
                @csrf

                <div>
                    <label for="email" class="admin-label text-slate-200">Email address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" autofocus
                           autocomplete="email"
                           @class([
                               'admin-input border-slate-600 bg-slate-900/50 text-white placeholder:text-slate-500',
                               'input-error !border-red-500 !ring-2 !ring-red-500/30' => $errors->has('email'),
                           ])>
                    @include('partials.field-error', ['field' => 'email', 'theme' => 'dark'])
                </div>

                <div>
                    <label for="password" class="admin-label text-slate-200">Password</label>
                    <input id="password" name="password" type="password"
                           autocomplete="current-password"
                           @class([
                               'admin-input border-slate-600 bg-slate-900/50 text-white',
                               'input-error !border-red-500 !ring-2 !ring-red-500/30' => $errors->has('password'),
                           ])>
                    @include('partials.field-error', ['field' => 'password', 'theme' => 'dark'])
                </div>

                <div>
                    <label class="flex items-center gap-2 text-sm text-slate-300">
                        <input type="checkbox" name="remember" value="1"
                               @checked(old('remember'))
                               class="rounded border-slate-600 bg-slate-900 text-brand-600 focus:ring-brand-500">
                        Remember me
                    </label>
                    @include('partials.field-error', ['field' => 'remember', 'theme' => 'dark'])
                </div>

                <button type="submit" class="btn-primary w-full">
                    Sign in
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-slate-400">
            Demo: admin.ekahal@gmail.com / Admin@123
        </p>
    </div>
@endsection
