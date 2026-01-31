<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
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
        Relation::morphMap([
            'artwork' => 'App\Models\Artwork',
            'book' => 'App\Models\Book',
        ]);

        Gate::define('view-original', function ($user) {
            return $user->hasPermissionTo('can view source image');
        });

        // Implicitly grant "admin" role all permissions
        // This works in the gate system which is used by $this->authorize()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}
