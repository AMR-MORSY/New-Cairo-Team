<?php

namespace App\Filament\Resources\Modification\Requesters\Pages;

use App\Filament\Resources\Modification\Requesters\RequesterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRequester extends EditRecord
{
    protected static string $resource = RequesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
