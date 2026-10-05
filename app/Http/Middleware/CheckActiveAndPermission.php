<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckActiveAndPermission
{
    public function handle(Request $request, Closure $next)
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

        if (is_null($user->workshop_id)) {
            $allowedRoutes = ['workshops.switch', 'workshops.active-list', 'logout', 'locale.switch'];
            $currentRoute = $request->route()?->getName();

            if (!in_array($currentRoute, $allowedRoutes) && ($request->expectsJson() || $request->ajax())) {
                if ($request->has('draw')) {
                    return response()->json([
                        'draw'            => (int) $request->input('draw'),
                        'recordsTotal'    => 0,
                        'recordsFiltered' => 0,
                        'data'            => [],
                    ]);
                }

                if ($request->isMethod('GET')) {
                    return response()->json([
                        'data' => [],
                    ]);
                }

                return response()->json([
                    'message' => __('workshop.must_select_workshop')
                ], 403);
            }
        }

        return $next($request);
    }
}
