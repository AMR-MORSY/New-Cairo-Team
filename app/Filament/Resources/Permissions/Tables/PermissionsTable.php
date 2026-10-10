<?php

namespace App\Filament\Resources\Permissions\Tables;

use App\Enums\ModificationPermissions;
use App\Enums\SitePermissions;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PermissionsTable
{
    /** Permission names the enums define, which the seeder will recreate. */
    protected static function fromCode(): array
    {
        return array_merge(
            array_column(ModificationPermissions::cases(), 'value'),
            array_column(SitePermissions::cases(), 'value'),
        );
    }

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state) => in_array($state, static::fromCode(), true) ? 'primary' : 'gray')
                    ->description(fn (string $state) => in_array($state, static::fromCode(), true)
                        ? 'defined in code'
                        : 'added here'),

                TextColumn::make('roles_count')
                    ->label('Roles')
                    ->counts('roles')
                    ->badge(),

                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->recordActions([
                EditAction::make(),

                // one the enums define would simply come back on the next seed
                DeleteAction::make()
                    ->visible(fn ($record) => ! in_array($record->name, static::fromCode(), true)),
            ])
            ->toolbarActions([]);
    }
}
