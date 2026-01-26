<?php

declare(strict_types=1);

namespace App\Modules\Permissions\Filament\Resources\PermissionResource\Pages;

use App\Modules\Permissions\Filament\Resources\PermissionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewPermission extends ViewRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
