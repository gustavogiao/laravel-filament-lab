<?php

declare(strict_types=1);

namespace App\Modules\Permissions\Filament\Resources\RoleResource\Pages;

use App\Modules\Permissions\Filament\Resources\RoleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
