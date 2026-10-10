<?php

namespace App\Filament\Resources\Roles\Tables;

use App\Enums\UserRoles;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state) => UserRoles::tryFrom($state) ? 'primary' : 'gray')
                    ->description(fn (string $state) => UserRoles::tryFrom($state)
                        ? 'defined in code'
                        : 'added here'),

                TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->counts('permissions')
                    ->badge(),

                TextColumn::make('users_count')
                    ->label('Users')
                    ->counts('users')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([]);
    }
}
