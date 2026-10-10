<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(125)
                    ->unique(ignoreRecord: true)
                    ->helperText(
                        'Nothing enforces a permission until some policy or route asks for it. '
                        . 'Adding one here does not by itself restrict anything.'
                    ),

                TextInput::make('guard_name')
                    ->default('web')
                    ->required()
                    ->maxLength(125),
            ]);
    }
}
