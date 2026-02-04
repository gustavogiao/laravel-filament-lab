<?php

namespace App\Modules\Permissions\Providers;

use App\Modules\Permissions\Commands\SyncPermissions\SyncPermissionsCommand;
use App\Modules\Permissions\Models\Permission;
use App\Modules\Permissions\Models\Role;
use App\Modules\Permissions\Policies\RolePolicy;
use App\Modules\Project\Models\Project;
use App\Modules\Task\Models\Task;
use App\Modules\Telemetry\Models\Device;
use App\Modules\Telemetry\Models\Patient;
use App\Modules\Telemetry\Models\PatientDeviceAssignment;
use App\Modules\Telemetry\Models\VitalSignReading;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(base_path('config/permissions.php'), 'permission');
        $this->commands(SyncPermissionsCommand::class);
    }

    public function boot(): void
    {
        Relation::morphMap([
            'roles' => Role::class,
            'permissions' => Permission::class,
            'projects' => Project::class,
            'tasks' => Task::class,
            'users' => User::class,
            'patients' => Patient::class,
            'devices' => Device::class,
            'vital_sign_readings' => VitalSignReading::class,
            'patient_device_assignments' => PatientDeviceAssignment::class,
        ]);

        Gate::policy(Role::class, RolePolicy::class);
    }
}
