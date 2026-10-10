<?php

namespace App\Filament\Resources\Modification\Requesters\Pages;

use App\Filament\Resources\Modification\Requesters\RequesterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRequesters extends ListRecords
{
    protected static string $resource = RequesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
