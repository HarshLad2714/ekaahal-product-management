<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    public const WEB_TOKEN_NAME = 'web-panel';

    public const API_TOKEN_NAME = 'api-access';

    public function __construct(
        protected UserRepositoryInterface $users,
    ) {}

    /**
     * Web admin panel: session + Sanctum token for stateful requests.
     *
     * @param  array{email: string, password: string, remember?: bool}  $credentials
     * @return array{user: User, token: string}
     */
    public function attemptLogin(array $credentials, string $tokenName = self::WEB_TOKEN_NAME): array
    {
        $remember = (bool) ($credentials['remember'] ?? false);

        if (! Auth::guard('web')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $remember)) {
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        /** @var User $user */
        $user = Auth::guard('web')->user();

        return [
            'user' => $user,
            'token' => $this->issueToken($user, $tokenName),
        ];
    }

    /**
     * API clients: Bearer token only (no session).
     *
     * @return array{user: User, token: string}
     */
    public function loginForApi(array $credentials): array
    {
        $user = $this->users->findByEmail($credentials['email']);

        if ($user === null || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        return [
            'user' => $user,
            'token' => $this->issueToken($user, self::API_TOKEN_NAME),
        ];
    }

    public function logout(?User $user = null, ?string $tokenName = null): void
    {
        if ($plain = request()->bearerToken()) {
            PersonalAccessToken::findToken($plain)?->delete();
        }

        $user ??= request()->user();

        if ($user !== null) {
            $current = $user->currentAccessToken();

            if ($current instanceof PersonalAccessToken) {
                $current->delete();
            } elseif ($tokenName !== null) {
                $user->tokens()->where('name', $tokenName)->delete();
            }
        }

        Auth::guard('web')->logout();

        if (request()->hasSession()) {
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }
    }

    protected function issueToken(User $user, string $tokenName): string
    {
        $user->tokens()->where('name', $tokenName)->delete();

        return $user->createToken($tokenName, $this->abilitiesFor($user))->plainTextToken;
    }

    /**
     * @return list<string>
     */
    public function abilitiesFor(User $user): array
    {
        return $user->isAdmin()
            ? ['admin', 'user', 'products:manage']
            : ['user', 'products:manage-own'];
    }
}
