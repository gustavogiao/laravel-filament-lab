<?php

declare(strict_types=1);

namespace App\Modules\Permissions\Filament\Resources\PermissionResource\Pages;

use App\Modules\Permissions\Filament\Resources\PermissionResource;
use App\Modules\Shared\Filament\Concerns\RedirectsToIndex;
use Filament\Resources\Pages\CreateRecord;

final class CreatePermission extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = PermissionResource::class;
}
