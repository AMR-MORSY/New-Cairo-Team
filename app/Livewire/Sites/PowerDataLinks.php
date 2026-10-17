<?php

namespace App\Livewire\Sites;

use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;

class PowerDataLinks extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;


    public object $site;
    public ?object $powerData;

    public string $page;

      public function deleteAction(): Action
    {
        return Action::make('delete')
            ->requiresConfirmation()
            ->modalHeading(__('Delete this Power Data?'))
            ->modalDescription(__('This will permanently delete the power data. It cannot be undone.'))
            ->modalSubmitActionLabel(__('Yes, delete it'))
            ->modalIcon('heroicon-o-trash')
            ->modalIconColor('warning')
            ->color('danger')
            // a closure: ->action($this->deleteModification()) would run the
            // delete while the page was still rendering
            ->action(fn() => $this->deletePowerData());
    }

    public function deletePowerData()
    {
        $this->authorize('update', $this->site);
        $this->powerData->delete();

        Toaster::info('Deleted Successfully.');

        return redirect()->route('site.show', ['site' => $this->site->site_code]);
    }
    public function render()
    {
        return view('livewire.sites.power-data-links');
    }
}
