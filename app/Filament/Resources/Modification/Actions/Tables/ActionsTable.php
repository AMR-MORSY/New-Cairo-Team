<?php

namespace App\Filament\Resources\Modification\Actions\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ActionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                // edit in place: retiring an entry is the common operation
                ToggleColumn::make('is_active')
                    ->label('Active'),

                TextColumn::make('updated_at')
                    ->label('Changed')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            /*
             * Deliberately no delete action, single or bulk.
             * modifications.subcontractor_id and .requester_id are ON DELETE
             * CASCADE, so removing one row here would take every modification
             * that referenced it with no warning. Retiring an entry is what
             * the is_active flag is for.
             */
            ->toolbarActions([]);
    }
}
