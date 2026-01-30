<?php

namespace App\Modules\Task\Analytics;

use App\Modules\Task\Models\Task;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Collection;

final class TasksTrend
{
    /**
     * @return Collection<string,int>
     */
    public function lastDays(int $days = 30): Collection
    {
        return Trend::model(Task::class)
            ->between(
                start: now()->subDays($days - 1)->startOfDay(),
                end: now()->endOfDay(),
            )
            ->perDay()
            ->count()
            ->mapWithKeys(fn (TrendValue $value) => [
                $value->date => $value->aggregate,
            ]);
    }
}
