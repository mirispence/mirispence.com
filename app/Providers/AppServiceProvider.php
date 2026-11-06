<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;

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

        Gate::define('view-original', function (User $user): bool {
            return $user->hasPermissionTo('can view source image');
        });

        // Implicitly grant "admin" role all permissions
        // This works in the gate system which is used by $this->authorize()
        Gate::before(function (User $user, string $ability): ?bool {
            return $user->hasRole('admin') ? true : null;
        });

        $this->defineFeatures();
    }

    /**
     * Feature flags (ADR-0006): each one resolves from config/features.php and
     * is global, so every visitor (admin or guest) sees the same value.
     */
    private function defineFeatures(): void
    {
        Feature::resolveScopeUsing(fn () => null);

        Feature::define('books', fn (): bool => (bool) config('features.books'));

        // A switched-off area should look absent (404), not forbidden.
        EnsureFeaturesAreActive::whenInactive(fn () => abort(404));
    }
}
