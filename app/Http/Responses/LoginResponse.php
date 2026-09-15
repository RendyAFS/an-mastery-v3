<?php

namespace App\Http\Responses;

use App\Helpers\MenuHelper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;

class LoginResponse implements LoginResponseContract, TwoFactorLoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        /** @var User $user */
        $user = Auth::user();

        $fallbackUrl = MenuHelper::getFirstAccessibleUrl($user);

        // Clear session intended URL if it points to dashboard when user cannot access dashboard
        $intendedUrl = session('url.intended');
        if ($intendedUrl && !$user->hasRole('Super Admin') && $user->cannot('dashboard.view')) {
            $dashboardPath = url('/dashboard');
            $homePath = url('/');
            if ($intendedUrl === $dashboardPath || $intendedUrl === $homePath) {
                session()->forget('url.intended');
            }
        }

        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended($fallbackUrl);
    }
}
