<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Teams;
use App\Enums\Zones;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        // A password is only ever set by its owner. Hidden on create (the
                        // invitee sets theirs from the invitation email) and hidden when
                        // editing somebody else, so an administrator never learns or
                        // chooses a colleague's credentials — they send an invitation
                        // instead. Visible only when you are editing your own account.
                        // TextInput::make('password')
                        //     ->label('New password')
                        //     ->password()
                        //     ->revealable()
                        //     ->rule(PasswordRule::min(8)->letters()->numbers()->mixedCase()->symbols())
                        //     ->visible(false)
                        //     ->dehydrated(fn(?string $state): bool => filled($state))
                        //     ->helperText('Leave blank to keep your current password.'),
                    ]),

                Section::make('Where they work')
                    ->description('Roles say what someone may do; these two say where.')
                    ->columns(2)
                    ->schema([
                        /*
                         * A zone belongs to exactly one team, so picking a
                         * zone fills the team in. A seeder once set these
                         * independently and left eight zone managers holding
                         * the wrong zone's role.
                         */
                        Select::make('zone')
                            ->label('Operation zone')
                            ->options(fn() => collect(Zones::cases())
                                ->mapWithKeys(fn(Zones $z) => [$z->value => $z->label() . ' (' . $z->team()->value . ')'])
                                ->all())
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('team', Zones::from($state)->team()->value);
                                }
                            })
                            // an untouched select posts '', and the Teams /
                            // Zones casts throw on '' where they accept null
                            ->dehydrateStateUsing(fn ($state) => $state ?: null)
                            ->helperText('Sets the team automatically.'),

                        Select::make('team')
                            ->label('Team')
                            ->options(fn() => collect(Teams::cases())
                                ->mapWithKeys(fn(Teams $t) => [$t->value => $t->value])
                                ->all())
                            ->dehydrateStateUsing(fn ($state) => $state ?: null)
                            ->helperText('Only set this on its own for someone with no single zone.'),
                    ]),

                Section::make('Roles')
                    ->schema([
                        CheckboxList::make('roles')
                            ->relationship('roles', 'name')
                            ->searchable()
                            ->bulkToggleable()
                            ->columns(2)
                            ->helperText(
                                'A user with no team and no global role cannot get past the '
                                    . 'team check, so they will not be able to use the app.'
                            ),
                    ]),
            ]);
    }
}
