<?php

declare(strict_types=1);

namespace App\Modules\Permissions\Filament\Resources\RoleResource\Pages;

use App\Modules\Permissions\Filament\Resources\RoleResource;
use App\Modules\Shared\Filament\Concerns\RedirectsToIndex;
use Filament\Resources\Pages\CreateRecord;

final class CreateRole extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = RoleResource::class;
}
