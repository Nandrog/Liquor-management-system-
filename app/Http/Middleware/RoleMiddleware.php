<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        //This is the way to check user's role
        $user = Auth::user();
        //debug output
        //\Log::info('User roles:', $user ? $user->getRoleNames()->toArray() : []);
        //\Log::info('Required roles:', $roles);

        if (!$user || !$user->hasAnyRole($roles)) {
            abort(403, 'Unauthorized: Role does not match.');
        }

        return $next($request);
    }
}
