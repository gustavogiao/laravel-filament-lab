<?php

declare(strict_types=1);

namespace App\Modules\Project\Filament\Widgets;

use App\Modules\Project\Enums\ProjectStatus;
use App\Modules\Project\Models\Project;
use Filament\Widgets\ChartWidget;

final class ProjectsChart extends ChartWidget
{
    protected ?string $heading = 'Projects Chart';

    protected static bool $isLazy = true;

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $statuses = ProjectStatus::cases();

        return [
            'labels' => collect($statuses)
                ->map(fn (ProjectStatus $status) => $status->getLabel())
                ->toArray(),

            'datasets' => [
                [
                    'label' => 'Projects by Status',
                    'data' => collect($statuses)
                        ->map(fn (ProjectStatus $status) => Project::query()
                            ->where('status', $status->value)
                            ->count()
                        )
                        ->toArray(),

                    'backgroundColor' => collect($statuses)
                        ->map(fn (ProjectStatus $status) => $status->getChartColor())
                        ->toArray(),
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
