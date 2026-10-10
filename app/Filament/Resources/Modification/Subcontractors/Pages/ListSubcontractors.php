<?php

namespace App\Filament\Resources\Modification\Subcontractors\Pages;

use App\Filament\Resources\Modification\Subcontractors\SubcontractorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubcontractors extends ListRecords
{
    protected static string $resource = SubcontractorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
