<?php

namespace App\Modules\User\Filament\Resources\UserResource\Tables;

use App\Modules\Permissions\Enums\Roles;
use App\Modules\User\Models\User;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Roles::from($state)->getLabel())
                    ->color(fn (string $state) => Roles::from($state)->getColor())
                    ->icon(fn (string $state) => Roles::from($state)->getIcon())
                    ->searchable(),

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
}
