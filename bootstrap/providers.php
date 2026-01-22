<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Modules\User\Providers\UserServiceProvider::class,
    App\Modules\Auth\Providers\FortifyServiceProvider::class,
];
