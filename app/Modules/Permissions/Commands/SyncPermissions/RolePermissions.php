<?php

namespace App\Modules\Permissions\Commands\SyncPermissions;

use App\Modules\Permissions\Enums\PermissionsEnum;

readonly class RolePermissions
{
    /**
     * @param  array<string, array<int, PermissionsEnum>>  $resources
     */
    public function __construct(
        public string $role,
        public array $resources,
    ) {}
}
