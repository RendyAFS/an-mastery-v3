<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckActiveAndPermission
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        if (is_null($user->is_active) || $user->is_active == 0) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            abort(403, 'Your account is inactive.');
        }

        if ($permissions && !$user->hasAnyPermission($permissions)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
