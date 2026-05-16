<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(
        protected AuthService $authService,
    ) {}

    public function create(): View|RedirectResponse
    {
        if (auth('sanctum')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $result = $this->authService->attemptLogin($request->validated());

        $request->session()->regenerate();
        $request->session()->put('sanctum_token', $result['token']);

        return redirect()->intended(route('admin.dashboard'));
    }
}
