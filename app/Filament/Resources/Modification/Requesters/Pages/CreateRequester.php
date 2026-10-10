<?php

namespace App\Filament\Resources\Modification\Requesters\Pages;

use App\Filament\Resources\Modification\Requesters\RequesterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRequester extends CreateRecord
{
    protected static string $resource = RequesterResource::class;
}
