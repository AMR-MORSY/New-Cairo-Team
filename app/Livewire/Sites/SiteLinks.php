<?php

namespace App\Livewire\Sites;

use App\Models\Site\Site;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;

class SiteLinks extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?Site $site;
    public string $page;

    public function render()
    {
        return view('livewire.sites.site-links');
    }
}
