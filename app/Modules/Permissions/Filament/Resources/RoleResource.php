<?php

declare(strict_types=1);

namespace App\Modules\Permissions\Filament\Resources;

use App\Modules\Permissions\Filament\Resources\RoleResource\Schemas\RoleForm;
use App\Modules\Permissions\Filament\Resources\RoleResource\Tables\RoleTable;
use App\Modules\Permissions\Models\Role;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationLabel = 'Roles';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShieldCheck;

    protected static ?string $slug = 'roles';

    public static function getNavigationGroup(): ?string
    {
        return 'Security';
    }

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoleTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => RoleResource\Pages\ListRoles::route('/'),
            'create' => RoleResource\Pages\CreateRole::route('/create'),
            'view' => RoleResource\Pages\ViewRole::route('/{record}'),
            'edit' => RoleResource\Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
