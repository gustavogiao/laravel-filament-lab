<?php

declare(strict_types=1);

namespace App\Modules\Task\Filament\Widget;

use App\Modules\Task\Analytics\TasksTrend;
use Filament\Widgets\ChartWidget;

final class TasksChart extends ChartWidget
{
    protected ?string $heading = 'Tasks created (last 30 days)';

    protected static bool $isLazy = true;

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $data = app(TasksTrend::class)->lastDays();

        return [
            'labels' => $data->keys()->toArray(),

            'datasets' => [
                [
                    'label' => 'Tasks',
                    'data' => $data->values()->toArray(),
                    'borderWidth' => 2,
                    'fill' => false,
                    'tension' => 0.3,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
