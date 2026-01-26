<?php

declare(strict_types=1);

namespace App\Modules\Permissions\Filament\Resources\PermissionResource\Pages;

use App\Modules\Permissions\Filament\Resources\PermissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
