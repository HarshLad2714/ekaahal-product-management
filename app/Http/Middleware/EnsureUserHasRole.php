<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * @param  string  $role  Comma-separated roles, e.g. "admin" or "admin,user"
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if ($user === null) {
            abort(401);
        }

        $allowed = collect(explode(',', $role))
            ->map(fn (string $r) => UserRole::tryFrom(trim($r)))
            ->filter()
            ->all();

        if ($allowed === [] || ! in_array($user->role, $allowed, true)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
