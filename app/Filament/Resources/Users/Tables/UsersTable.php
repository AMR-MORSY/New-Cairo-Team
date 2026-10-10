<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\Teams;
use App\Enums\Zones;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Password;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('zone')
                    ->label('Zone')
                    ->badge()
                    ->formatStateUsing(fn (?Zones $state) => $state?->label() ?? '—')
                    ->sortable(),

                TextColumn::make('team')
                    ->label('Team')
                    ->badge()
                    ->formatStateUsing(fn (?Teams $state) => $state?->value ?? '—')
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->separator(',')
                    ->placeholder('none'),

                TextColumn::make('login_at')
                    ->label('Last seen')
                    ->since()
                    ->placeholder('never')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('name')
            ->filters([
                SelectFilter::make('team')
                    ->options(fn () => collect(Teams::cases())
                        ->mapWithKeys(fn (Teams $t) => [$t->value => $t->value])->all()),

                SelectFilter::make('zone')
                    ->options(fn () => collect(Zones::cases())
                        ->mapWithKeys(fn (Zones $z) => [$z->value => $z->label()])->all()),

                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),

                /*
                 * The list that matters during cutover: nobody without a team
                 * or a global role can get past CheckTeamMembership.
                 */
                Filter::make('cannot_sign_in')
                    ->label('Cannot use the app yet')
                    ->query(fn (Builder $query) => $query
                        ->whereNull('team')
                        ->whereDoesntHave('roles', fn (Builder $r) => $r->whereIn('name', [
                            'super_admin', 'modification_admin', 'site_admin',
                        ]))),
            ])
            ->recordActions([
                EditAction::make(),

                /*
                 * The way to hand someone a working link again. Reset tokens
                 * expire after 60 minutes (config/auth.php) and are consumed on
                 * use, so an invitation left in an inbox overnight is dead.
                 *
                 * This is the only correct remedy: deleting and recreating the
                 * account would take every modification the user owns with it,
                 * because modifications.action_owner cascades.
                 */
                Action::make('resendInvitation')
                    ->label('Resend invitation')
                    ->icon('heroicon-o-envelope')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalHeading('Send a new invitation?')
                    ->modalDescription(fn (User $record) => "A fresh link will be emailed to {$record->email}. Any earlier link stops working straight away.")
                    ->modalSubmitActionLabel('Send it')
                    ->action(function (User $record) {
                        $status = $record->sendStaffInvitation();

                        if ($status === Password::RESET_LINK_SENT) {
                            Notification::make()
                                ->title('Invitation sent')
                                ->body("{$record->name} can set a password using the link emailed to {$record->email}.")
                                ->success()
                                ->send();

                            return;
                        }

                        // most often the 60-second throttle, which says so itself
                        Notification::make()
                            ->title('Invitation not sent')
                            ->body(__($status))
                            ->warning()
                            ->persistent()
                            ->send();
                    }),
            ])
            /*
             * No delete. modifications.action_owner is ON DELETE CASCADE, so
             * removing a user would take every modification they own with
             * them -- and the import deliberately preserved those ids.
             */
            ->toolbarActions([]);
    }
}
