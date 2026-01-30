<?php

namespace App\Modules\Permissions\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum Roles: string implements HasColor, HasIcon, HasLabel
{
    case SuperAdmin = 'super_admin';
    case User = 'user';

    public function getLabel(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::User => 'User',
        };
    }

    public function getColor(): array
    {
        return match ($this) {
            self::SuperAdmin => Color::Red,
            self::User => Color::Blue,
        };
    }

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::SuperAdmin => Heroicon::ShieldCheck,
            self::User => Heroicon::User,
        };
    }
}
