<?php

namespace App\Filament\Resources\Site\SiteNoticeTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SiteNoticeTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true)
                    ->autofocus()
                    ->helperText('Existing notes store this name as text, so renaming does not update them.'),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText(
                        'Inactive types stay on the notes that already use them, '
                        . 'but are not offered when creating new ones.'
                    ),
            ]);
    }
}
