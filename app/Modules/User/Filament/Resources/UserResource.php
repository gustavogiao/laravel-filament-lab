<?php

declare(strict_types=1);

namespace App\Modules\User\Filament\Resources;

use App\Modules\User\Filament\Resources\UserResource\Pages\CreateAdmin;
use App\Modules\User\Filament\Resources\UserResource\Pages\EditUser;
use App\Modules\User\Filament\Resources\UserResource\Pages\ListUsers;
use App\Modules\User\Filament\Resources\UserResource\Pages\ViewUser;
use App\Modules\User\Filament\Resources\UserResource\Schemas\UserForm;
use App\Modules\User\Filament\Resources\UserResource\Tables\UserTable;
use App\Modules\User\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Users';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;

    public static function getNavigationGroup(): ?string
    {
        return 'Security';
    }

    protected static ?string $slug = 'users';

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateAdmin::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
