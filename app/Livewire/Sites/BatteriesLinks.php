<?php

namespace App\Livewire\Sites;

use Livewire\Component;
use Toaster;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
class BatteriesLinks extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public object $site;
    public object $battery;

    public string $page;


      public function deleteAction(): Action
    {
        return Action::make('delete')
            ->requiresConfirmation()
            ->modalHeading(__('Delete this Battery?'))
            ->modalDescription(__('This will permanently delete the battery. It cannot be undone.'))
            ->modalSubmitActionLabel(__('Yes, delete it'))
            ->modalIcon('heroicon-o-trash')
            ->modalIconColor('warning')
            ->color('danger')
            // a closure: ->action($this->deleteModification()) would run the
            // delete while the page was still rendering
            ->action(fn() => $this->deleteBattery());
    }

    public function deleteBattery()
    {
        $this->authorize('update', $this->site);
        $this->battery->delete();

        Toaster::info('Deleted Successfully.');

        return redirect()->route('site.batteries', ['site' => $this->site->site_code]);
    }
    public function render()
    {
        return view('livewire.sites.batteries-links');
    }
}
