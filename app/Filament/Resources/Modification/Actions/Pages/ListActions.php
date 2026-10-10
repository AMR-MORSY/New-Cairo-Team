<?php

namespace App\Filament\Resources\Modification\Actions\Pages;

use App\Filament\Resources\Modification\Actions\ActionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListActions extends ListRecords
{
    protected static string $resource = ActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
