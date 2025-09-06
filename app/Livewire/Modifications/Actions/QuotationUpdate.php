<?php

namespace App\Livewire\Modifications\Actions;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;
use App\Models\Modification\Quotation;

class QuotationUpdate extends Component
{
    public object $quotation;

    public Collection $quotationItems;

    // public  bool $isLastMailItem = true;
    // public bool   $isLastPriceItem = true;
    public function mount(Quotation $quotation)
    {



        $this->quotation = $quotation->load(['priceListItems', 'mailListItems']);

        $priceListItems = $this->quotation->priceListItems->toArray(); ///////merge both priceListItems+mailListItems collections in one collection to be presented in the table
        $mailListItems = $this->quotation->mailListItems->toArray();

        $combined = array_merge($priceListItems, $mailListItems);

        $this->quotationItems = collect($combined);

        //  dd($this->quotationItems);
    }


    #[On('removeItemFromQuotation')]

    public function removeItemFromQuotation($row)
    {


        $modification_id = $this->quotation->modification->id;


        // dd($row);

        if ($row['item'] == 'Mail') {

            //   dd($this->quotation->mailListItems()->count());
            $this->quotation->mailListItems()->wherePivot('id', $row['pivot']['id'])->detach();

            // dd($countMailItems);

        } else {
            $this->quotation->priceListItems()->wherePivot('id', $row['pivot']['id'])->detach();
        }
        $countMailItems = $this->quotation->mailListItems()->count();
        $countPriceItems = $this->quotation->priceListItems()->count();

        if (!$countMailItems > 0 &&  !$countPriceItems > 0) {

            // dd($this->isLastMailItem);
            $this->quotation->delete();
        }

        redirect()->route("quotation.details", ['modification' => $modification_id]);
    }

    #[On('create_quotation')]

    public function addItem()
    {
        redirect()->route('quotation.create', ['modification' => $this->quotation->modification->id]);
    }

    public function render()
    {
        return view('livewire.modifications.actions.quotation-update', ['quotation' => $this->quotation, 'quotationItems' => $this->quotationItems]);
    }
}
