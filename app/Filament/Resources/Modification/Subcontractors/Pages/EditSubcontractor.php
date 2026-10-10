<?php

namespace App\Filament\Resources\Modification\Subcontractors\Pages;

use App\Filament\Resources\Modification\Subcontractors\SubcontractorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubcontractor extends EditRecord
{
    protected static string $resource = SubcontractorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
