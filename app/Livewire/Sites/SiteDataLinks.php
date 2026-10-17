<?php

namespace App\Livewire\Sites;

use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
class SiteDataLinks extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public object $site;
    public object $siteData;

    public string $page;

    
      public function deleteAction(): Action
    {
        return Action::make('delete')
            ->requiresConfirmation()
            ->modalHeading(__('Delete this Site Data?'))
            ->modalDescription(__('This will permanently delete the site data. It cannot be undone.'))
            ->modalSubmitActionLabel(__('Yes, delete it'))
            ->modalIcon('heroicon-o-trash')
            ->modalIconColor('warning')
            ->color('danger')
            // a closure: ->action($this->deleteModification()) would run the
            // delete while the page was still rendering
            ->action(fn() => $this->deleteSiteData());
    }

    public function deleteSiteData()
    {
        $this->authorize('update',$this->site);
        $this->siteData->delete();

        Toaster::info('Deleted Successfully.');

        return redirect()->route('site.show', ['site' => $this->site->site_code]);
    }
    public function render()
    {
        return view('livewire.sites.site-data-links');
    }
}
