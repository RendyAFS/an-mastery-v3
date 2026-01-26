<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::before(function (User $user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        Permission::all()->each(function ($permission) {
            Gate::define($permission->name, function (User $user) use ($permission) {
                return $user->hasPermissionTo($permission->name);
            });
        });
    }
}
