<?php

namespace App\Modules\Permissions\Enums;

enum Roles: string
{
    case SuperAdmin = 'super_admin';
    case User = 'user';
}
