<?php

namespace App\Modules\User\Providers;

use App\Modules\User\Livewire\Settings\Appearance;
use App\Modules\User\Livewire\Settings\DeleteUserForm;
use App\Modules\User\Livewire\Settings\Password;
use App\Modules\User\Livewire\Settings\Profile;
use App\Modules\User\Livewire\Settings\TwoFactor;
use App\Modules\User\Livewire\Settings\TwoFactor\RecoveryCodes;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class UserServiceProvider extends ServiceProvider
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
        Livewire::component('settings.appearance', Appearance::class);
        Livewire::component('settings.delete-user-form', DeleteUserForm::class);
        Livewire::component('settings.password', Password::class);
        Livewire::component('settings.profile', Profile::class);
        Livewire::component('settings.two-factor', TwoFactor::class);
        Livewire::component('settings.two-factor.recovery-codes', RecoveryCodes::class);
    }
}
