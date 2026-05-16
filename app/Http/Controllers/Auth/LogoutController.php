<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;

class LogoutController extends Controller
{
    public function __construct(
        protected AuthService $authService,
    ) {}

    public function __invoke(): RedirectResponse
    {
        $this->authService->logout(
            request()->user(),
            AuthService::WEB_TOKEN_NAME,
        );

        return redirect()->route('login');
    }
}
