<?php

namespace App\Providers;

use App\Models\Gallery;
use App\Models\ImageFabric;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');

        Gate::define('viewLogViewer', function (User $user) {
            return $user->hasRole('Super Admin') || $user->hasPermissionTo('dashboard.log-viewer');
        });

        View::composer(['auth.login', 'auth.register', 'layouts.auth'], function ($view) {
            $galleries = Gallery::with('media')->whereNull('deleted_at')->latest()->get();
            $imageFabrics = ImageFabric::with('media')->whereNull('deleted_at')->latest()->get();

            $images = collect();

            foreach ($galleries as $g) {
                $url = $g->getFirstMediaUrl('galleries') ?: $g->getFirstMediaUrl();
                if ($url) {
                    $images->push([
                        'url'      => $url,
                        'title'    => $g->name,
                        'subtitle' => $g->notes ?: 'Koleksi Galeri Produksi',
                        'badge'    => 'Galeri'
                    ]);
                }
            }

            foreach ($imageFabrics as $f) {
                $url = $f->getFirstMediaUrl('image-fabrics') ?: $f->getFirstMediaUrl();
                if ($url) {
                    $images->push([
                        'url'      => $url,
                        'title'    => $f->name,
                        'subtitle' => $f->notes ?: 'Katalog Bahan Kain',
                        'badge'    => 'Kain'
                    ]);
                }
            }

            $view->with('authImages', $images->shuffle());
        });
    }
}
