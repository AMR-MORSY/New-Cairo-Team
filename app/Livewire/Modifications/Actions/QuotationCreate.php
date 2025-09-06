<?php

namespace App\Livewire\Modifications\Actions;

use Livewire\Component;
use Illuminate\Support\Collection;
use App\Models\Modification\Modification;

class QuotationCreate extends Component
{
    public object|null $modification=null;
    public object|null $quotation = null;

    public Collection|null $quotationItems = null;

    public function mount(Modification $modification)
    {
        $this->modification = $modification;


        if ($this->modification->quotation) {
            $this->quotation = $modification->quotation->load(['priceListItems', 'mailListItems']);

            $priceListItems = $this->quotation->priceListItems->toArray(); ///////merge both priceListItems+mailListItems collections in one collection to be presented in the table
            $mailListItems = $this->quotation->mailListItems->toArray();

            $combined = array_merge($priceListItems, $mailListItems);

            $this->quotationItems = collect($combined);
        }
    }
    public function render()
    {
        return view('livewire.modifications.actions.quotation-create', ["modification" => $this->modification,"quotationItems" => $this->quotationItems]);
    }
}
