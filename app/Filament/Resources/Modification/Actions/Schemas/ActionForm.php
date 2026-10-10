<?php

namespace App\Filament\Resources\Modification\Actions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ActionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                /*
                 * A free text field, not a picker. The generator guessed a
                 * Select seeded with the values already in the table, which
                 * made it impossible to add the new ones this screen exists
                 * for.
                 */
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true)
                    ->autofocus(),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText(
                        'Inactive entries stay on the records that already use them, '
                        . 'but are not offered when creating new ones.'
                    ),
            ]);
    }
}
