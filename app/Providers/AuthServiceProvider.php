<?php

namespace App\Providers;

use App\Modules\Permissions\Enums\Roles;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::before(fn (User $user) => $user->hasRole(Roles::SuperAdmin) ? true : null
        );
    }
}
