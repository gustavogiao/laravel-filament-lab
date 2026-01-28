<?php

declare(strict_types=1);

namespace App\Modules\Permissions\Filament\Resources;

use App\Modules\Permissions\Filament\Resources\PermissionResource\Schemas\PermissionForm;
use App\Modules\Permissions\Filament\Resources\PermissionResource\Tables\PermissionTable;
use App\Modules\Permissions\Models\Permission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationLabel = 'Permissions';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::LockClosed;

    protected static ?string $slug = 'permissions';

    public static function getNavigationGroup(): ?string
    {
        return 'Security';
    }

    public static function form(Schema $schema): Schema
    {
        return PermissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermissionTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => PermissionResource\Pages\ListPermissions::route('/'),
            'create' => PermissionResource\Pages\CreatePermission::route('/create'),
            'view' => PermissionResource\Pages\ViewPermission::route('/{record}'),
            'edit' => PermissionResource\Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
