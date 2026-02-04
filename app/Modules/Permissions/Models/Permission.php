<?php

namespace App\Modules\Permissions\Models;

use Database\Factories\PermissionFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    /** @use HasFactory<PermissionFactory> */
    use HasFactory;

    public string $resource;

    public string $action;

    public string $resource_group;

    /** @return Attribute<string, void> */
    protected function resourceModel(): Attribute
    {
        return Attribute::make(
            get: fn () => str($this->resource)->explode('\\')->last()
        );
    }

    /** @return Attribute<string, void> */
    protected function formattedName(): Attribute
    {
        return Attribute::make(
            get: fn () => sprintf(
                '%s-%s-%s-%s', $this->resource_group, $this->resource_model, $this->action, $this->name
            )
        );
    }
}
