<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use App\Models\Gallery;
use App\Models\ImageFabric;
use Illuminate\Support\Facades\View;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );

        $this->app->singleton(
            \Laravel\Fortify\Contracts\TwoFactorLoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = $request->input(Fortify::username()) . '|' . $request->ip();
            return Limit::perMinute(5)->by($throttleKey);
        });

        Fortify::authenticateUsing(function (Request $request) {

            $request->validate([
                'email' => ['required', 'string'],
                'password' => ['required', 'string'],
            ]);

            $login = $request->email;

            $user = User::where('email', $login)
                ->orWhere('name', $login)
                ->first();

            if (! $user) {
                throw ValidationException::withMessages([
                    'email' => __('auth.login_not_found'),
                ]);
            }

            if (is_null($user->is_active) || $user->is_active == 0) {
                throw ValidationException::withMessages([
                    'email' => __('auth.account_inactive'),
                ]);
            }

            if (! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'password' => __('auth.password_incorrect'),
                ]);
            }

            return $user;
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        View::composer('components.auth-slideshow', function ($view) {
            $view->with('authImages', $this->buildAuthImages());
        });
    }

    private function buildAuthImages(): \Illuminate\Support\Collection
    {
        $galleryImages = Gallery::with('media')
            ->whereNull('deleted_at')
            ->latest()
            ->get()
            ->flatMap(function (Gallery $gallery) {
                $media = $gallery->getMedia('galleries')->isNotEmpty()
                    ? $gallery->getMedia('galleries')
                    : $gallery->getMedia();

                return $media->map(fn($m) => [
                    'url'      => $m->getUrl(),
                    'title'    => $gallery->name,
                    'subtitle' => $gallery->notes ?: '-',
                    'badge'    => 'Galeri Produksi',
                ]);
            });

        $fabricImages = ImageFabric::with('media')
            ->whereNull('deleted_at')
            ->latest()
            ->get()
            ->flatMap(function (ImageFabric $fabric) {
                $media = $fabric->getMedia('image-fabrics')->isNotEmpty()
                    ? $fabric->getMedia('image-fabrics')
                    : $fabric->getMedia();

                return $media->map(fn($m) => [
                    'url'      => $m->getUrl(),
                    'title'    => $fabric->name,
                    'subtitle' => $fabric->notes ?: '-',
                    'badge'    => 'Katalog Kain',
                ]);
            });

        return $galleryImages->concat($fabricImages)->values();
    }
}
