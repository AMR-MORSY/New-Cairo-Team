<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Enums\UserRoles;
use App\Filament\Resources\Roles\Concerns\SyncsPermissionGroups;
use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    use SyncsPermissionGroups;

    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            /*
             * Only a role the enum does not define may be deleted. Removing
             * one of the seven would cascade its assignments away and then be
             * recreated empty by the next seeder run.
             */
            DeleteAction::make()
                ->visible(fn () => ! UserRoles::tryFrom($this->record->name)),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->fillPermissionGroups($data, $this->record);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->takePermissionGroups($data);
    }

    protected function afterSave(): void
    {
        $this->syncPermissionGroups($this->record);
    }
}
