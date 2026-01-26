<?php

declare(strict_types=1);

namespace App\Modules\Permissions\Filament\Resources\PermissionResource\Pages;

use App\Modules\Permissions\Filament\Resources\PermissionResource;
use App\Modules\Shared\Filament\Concerns\RedirectsToIndex;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

final class EditPermission extends EditRecord
{
    use RedirectsToIndex;

    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
