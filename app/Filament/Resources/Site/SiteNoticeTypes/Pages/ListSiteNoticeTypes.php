<?php

namespace App\Filament\Resources\Site\SiteNoticeTypes\Pages;

use App\Filament\Resources\Site\SiteNoticeTypes\SiteNoticeTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSiteNoticeTypes extends ListRecords
{
    protected static string $resource = SiteNoticeTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
