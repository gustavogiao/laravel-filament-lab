<?php

declare(strict_types=1);

use App\Modules\Permissions\Enums\PermissionsEnum;
use App\Modules\Permissions\Enums\Roles;
use App\Modules\Permissions\Models\Permission;
use App\Modules\Permissions\Models\Role;
use App\Modules\Project\Models\Project;
use App\Modules\Task\Models\Task;
use App\Modules\Telemetry\Models\Device;
use App\Modules\Telemetry\Models\Patient;
use App\Modules\Telemetry\Models\PatientDeviceAssignment;
use App\Modules\Telemetry\Models\VitalSignReading;
use App\Modules\User\Models\User;

return [
    'permissions' => [
        Roles::SuperAdmin->value => [
            Role::class => PermissionsEnum::cases(),
            Permission::class => [
                PermissionsEnum::ViewAny,
                PermissionsEnum::View,
                PermissionsEnum::Create,
                PermissionsEnum::Update,
                PermissionsEnum::Delete,
                PermissionsEnum::ForceDelete,
                PermissionsEnum::Restore,
            ],
            Project::class => PermissionsEnum::cases(),
            Task::class => PermissionsEnum::cases(),
            User::class => PermissionsEnum::cases(),
            Patient::class => PermissionsEnum::cases(),
            Device::class => PermissionsEnum::cases(),
            VitalSignReading::class => PermissionsEnum::cases(),
            PatientDeviceAssignment::class => PermissionsEnum::cases(),
        ],
        Roles::User->value => [
            Project::class => [
                PermissionsEnum::ViewAny,
                PermissionsEnum::View,
                PermissionsEnum::Create,
                PermissionsEnum::Update,
            ],
            Task::class => [
                PermissionsEnum::ViewAny,
                PermissionsEnum::View,
                PermissionsEnum::Create,
                PermissionsEnum::Update,
                PermissionsEnum::Delete,
            ],
            Patient::class => [
                PermissionsEnum::ViewAny,
                PermissionsEnum::View,
            ],
            Device::class => [
                PermissionsEnum::ViewAny,
                PermissionsEnum::View,
            ],
            VitalSignReading::class => [
                PermissionsEnum::ViewAny,
                PermissionsEnum::View,
            ],
            PatientDeviceAssignment::class => [
                PermissionsEnum::ViewAny,
                PermissionsEnum::View,
            ],
        ],
    ],
];
