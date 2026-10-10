<?php

namespace App\Filament\Resources\Roles\Schemas;

use App\Enums\UserRoles;
use App\Filament\Resources\Roles\PermissionGroups;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        $groups = [];

        foreach (PermissionGroups::all() as $key => $group) {
            $groups[] = Section::make($group['label'])
                ->collapsible()
                ->schema([
                    CheckboxList::make(PermissionGroups::field($key))
                        ->label('')
                        ->options($group['options'])
                        ->bulkToggleable()
                        ->columns(2),
                    /*
                     * Deliberately dehydrated: the page reads these out of
                     * the form data and strips them before the model is
                     * touched. Marking them dehydrated(false) keeps them out
                     * of that data entirely, and the sync then sees nothing.
                     */
                ]);
        }

        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(125)
                            ->unique(ignoreRecord: true)
                            /*
                             * The seven roles the policies reason about come
                             * from an enum. Renaming one here would leave the
                             * enum pointing at a role that no longer exists.
                             */
                            ->disabled(fn (?object $record) => $record && UserRoles::tryFrom($record->name))
                            ->dehydrated(fn (?object $record) => ! ($record && UserRoles::tryFrom($record->name)))
                            ->helperText(fn (?object $record) => $record && UserRoles::tryFrom($record->name)
                                ? 'Defined in App\Enums\UserRoles, so the name is fixed.'
                                : null),

                        TextInput::make('guard_name')
                            ->default('web')
                            ->required()
                            ->maxLength(125),
                    ]),

                ...$groups,
            ]);
    }
}
