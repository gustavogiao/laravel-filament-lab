<?php

declare(strict_types=1);

namespace App\Modules\Project\Filament\Widgets;

use App\Modules\Project\Enums\ProjectStatus;
use App\Modules\Project\Models\Project;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class ProjectsStats extends StatsOverviewWidget
{
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Projects', Project::query()->count())
                ->icon(Heroicon::Folder),

            Stat::make('Total Active Projects', Project::query()->where('status', ProjectStatus::InProgress)->count())
                ->icon(Heroicon::Briefcase),

            Stat::make('Inactive Projects', Project::query()->where('status', ProjectStatus::Finished)->count())
                ->icon(Heroicon::ArchiveBox),
        ];
    }
}
