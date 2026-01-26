<?php

namespace App\Modules\User\Filament\Resources\UserResource\Pages;

use App\Modules\User\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;

final class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;
}
