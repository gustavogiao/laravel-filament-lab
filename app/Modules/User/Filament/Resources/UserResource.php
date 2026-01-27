<?php

declare(strict_types=1);

namespace App\Modules\User\Filament\Resources;

use App\Modules\User\Filament\Resources\UserResource\Pages\CreateAdmin;
use App\Modules\User\Filament\Resources\UserResource\Pages\EditUser;
use App\Modules\User\Filament\Resources\UserResource\Pages\ListUsers;
use App\Modules\User\Filament\Resources\UserResource\Pages\ViewUser;
use App\Modules\User\Models\User;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
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
        return $schema
            ->schema([
                Section::make('Admin')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('password')
                            ->password()
                            ->visible(fn($livewire) => $livewire instanceof CreateRecord)
                            ->required(fn($livewire) => $livewire instanceof CreateRecord)
                            ->minLength(8)
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn($state) => $state ? bcrypt($state) : null)
                            ->label('Password'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('roles.name')
                    ->badge()
                    ->label('Roles'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn (User $record) => auth()->id() !== $record->id
                    ),
            ]);
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
