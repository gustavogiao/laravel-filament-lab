<?php

namespace App\Modules\User\Filament\Resources\UserResource\Pages;

use App\Modules\User\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;

final class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;
}
