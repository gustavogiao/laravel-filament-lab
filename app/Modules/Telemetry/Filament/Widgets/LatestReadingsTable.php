<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Widgets;

use App\Modules\Telemetry\Filament\Queries\LatestReadingsQuery;
use App\Modules\Telemetry\Filament\Tables\LatestReadingsTableSchema;
use App\Modules\Telemetry\Traits\InteractsWithPatient;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

final class LatestReadingsTable extends BaseWidget
{
    use InteractsWithPatient;

    protected static ?int $sort = 5;

    protected static ?string $heading = 'Latest Readings';

    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return LatestReadingsQuery::forPatient($this->patientId);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns(LatestReadingsTableSchema::columns())
            ->emptyStateHeading('Without Vital Sign Readings')
            ->emptyStateDescription('No vital sign readings have been recorded for this patient yet.')
            ->emptyStateIcon(Heroicon::ExclamationCircle)
            ->paginated(false);
    }
}
