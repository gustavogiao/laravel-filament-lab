<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Resources\Devices;

use App\Modules\Telemetry\Filament\Resources\Devices\Pages\CreateDevice;
use App\Modules\Telemetry\Filament\Resources\Devices\Pages\EditDevice;
use App\Modules\Telemetry\Filament\Resources\Devices\Pages\ListDevices;
use App\Modules\Telemetry\Filament\Resources\Devices\Pages\ViewDevice;
use App\Modules\Telemetry\Filament\Resources\Devices\Schemas\DeviceForm;
use App\Modules\Telemetry\Filament\Resources\Devices\Tables\DevicesTable;
use App\Modules\Telemetry\Models\Device;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;

    protected static string|null|\BackedEnum $navigationIcon = Heroicon::DeviceTablet;

    protected static ?string $recordTitleAttribute = 'device_uid';

    public static function getNavigationGroup(): ?string
    {
        return 'Telemetry';
    }

    public static function form(Schema $schema): Schema
    {
        return DeviceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DevicesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDevices::route('/'),
            'create' => CreateDevice::route('/create'),
            'view' => ViewDevice::route('/{record}'),
            'edit' => EditDevice::route('/{record}/edit'),
        ];
    }
}
