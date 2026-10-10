<?php

namespace App\Livewire\Modifications;

use App\Models\Modification\Modification;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;

// HasSchemas is not optional even with no form in the modal: mounting an
// action reads $isCachingSchemas, which InteractsWithSchemas declares
class ModificationLinks extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;


    public $site;


    public ?Modification $modification;


    /**
     * Which page this panel is sitting on, passed in by the parent view.
     *
     * It used to be read with request()->routeIs() inside the blade, but that
     * only holds on the initial page load: every Livewire round trip posts to
     * /livewire/update, where all three branches are false and the panel
     * re-rendered empty -- visible as the links vanishing when the delete
     * modal was cancelled.
     */
    public string $page = '';


    /**
     * Filament resolves this by name: mountAction('delete') looks for
     * deleteAction() (or delete()) and requires the Action return type.
     */
    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->requiresConfirmation()
            ->modalHeading(__('Delete this modification?'))
            ->modalDescription(__('This releases the reserved amount back to the purchase order. It cannot be undone.'))
            ->modalSubmitActionLabel(__('Yes, delete it'))
            ->modalIcon('heroicon-o-trash')
            ->modalIconColor('warning')
            ->color('danger')
            // a closure: ->action($this->deleteModification()) would run the
            // delete while the page was still rendering
            ->action(fn() => $this->deleteModification());
    }


    public function deleteModification()

    {
        // $this->modification->reservation->update(['status' => 'cancelled']);

        // if ($this->modification->status?->isInProgress()) { /////////modification still in progress
        //     // $this->modification->reservation->update(['status' => 'cancelled']); ////////cancel the reservation
        //     $this->modification->reservation->po->decrement('in_progress', $this->modification->reservation->amount); ///////decrement the po in progress amount by the reserved amount
        //     $this->modification->reservation->po->increment('on_hand', $this->modification->reservation->amount); //////increment the po on hand amount to be used by other users
        //     $this->modification->delete();
        // } elseif ($this->modification->status?->isQuoted()) {
        //     $quotation = $this->modification->quotation;
        //     $mailListItems =  $quotation->mailListItems();
        //     $priceListItems =  $quotation->priceListItems(); ////////delete quotation's price & mail list items
        //     if (count($mailListItems) > 0) {
        //         foreach ($mailListItems as $item) {
        //             $item->delete();
        //         }
        //     }
        //     if (count($priceListItems) > 0) {
        //         foreach ($priceListItems as $item) {
        //             $item->delete();
        //         }
        //     }

        //     if ($this->modification->reservation->invoice) {
        //         $this->modification->reservation->po->decrement('invoiced',  $this->modification->reservation->invoice->amount); ///////decrement the po in progress amount by the reserved amount
        //         $this->modification->reservation->po->increment('on_hand',  $this->modification->reservation->invoice->amount); //////increment the po on hand amount to be used by other users
        //         $this->modification->reservation->invoice->delete();
        //     } elseif ($this->modification->reservation->overPo) {
        //         $this->modification->reservation->po->decrement('invoiced',  $this->modification->reservation->overPo->amount); ///////decrement the po in progress amount by the reserved amount
        //         $this->modification->reservation->po->increment('on_hand',  $this->modification->reservation->overPo->amount); //////increment the po on hand amount to be used by other users
        //         $this->modification->reservation->overPo->delete();
        //     }
        //     $quotation->delete();
        //     $this->modification->delete();
        // }

        $this->modification->delete();
        Toaster::success('Modification deleted Successfully');

        $this->redirect(route('site.modifications', ['site' => $this->site->site_code]));
    }
    public function render()
    {
        return view('livewire.modifications.modification-links');
    }
}
