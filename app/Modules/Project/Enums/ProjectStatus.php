<?php

namespace App\Modules\Project\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum ProjectStatus: string implements HasColor, HasIcon, HasLabel
{
    case Finished = 'finished';
    case InProgress = 'in_progress';
    case Draft = 'draft';

    public function getLabel(): string
    {
        return match ($this) {
            self::Finished => 'Finished',
            self::InProgress => 'In Progress',
            self::Draft => 'Draft',
        };
    }

    public function getColor(): array
    {
        return match ($this) {
            self::Finished => Color::Green,
            self::InProgress => Color::Yellow,
            self::Draft => Color::Gray,
        };
    }

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::Finished => Heroicon::CheckCircle,
            self::InProgress => Heroicon::Clock,
            self::Draft => Heroicon::DocumentText
        };
    }
}
