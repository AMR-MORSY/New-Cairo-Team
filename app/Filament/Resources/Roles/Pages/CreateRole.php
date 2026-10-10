<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\Concerns\SyncsPermissionGroups;
use App\Filament\Resources\Roles\RoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    use SyncsPermissionGroups;

    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->takePermissionGroups($data);
    }

    protected function afterCreate(): void
    {
        $this->syncPermissionGroups($this->record);
    }
}
