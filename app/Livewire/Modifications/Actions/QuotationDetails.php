<?php

namespace App\Livewire\Modifications\Actions;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;
use App\Models\Modification\Modification;


class QuotationDetails extends Component
{

    public object|null $modification = null;
    public object|null $quotation = null;

    public Collection|null $quotationItems = null;

    public $site;

    public string |null $subHeading='';

    public function mount(Modification $modification)
    {

        $this->modification = $modification;

        $this->site = $modification->site;

        if ($this->modification->quotation) {
            $this->quotation = $modification->quotation->load(['priceListItems', 'mailListItems']);

            $priceListItems = $this->quotation->priceListItems->toArray(); ///////merge both priceListItems+mailListItems collections in one collection to be presented in the table
            $mailListItems = $this->quotation->mailListItems->toArray();

            $combined = array_merge($priceListItems, $mailListItems);

            $this->quotationItems = collect($combined);

            if($this->quotation->is_active==0)
            {
                $this->subHeading="This quotation is not active; to activate, update the modification status to waiting D6 or Done";
            }
            else{
                 $this->subHeading="This quotation is active.";

            }
        }



        //  dd( $this->quotationItems);
    }


    #[On("update_quotation")]

    public function goToQuotationUpdate()
    {

        return redirect()->route("quotation.update", ['quotation' => $this->quotation->id]);
    }
    public function render()
    {
        return view('livewire.modifications.actions.quotation-details', ["modification" => $this->modification, "quotation" => $this->quotation, "quotationItems" => $this->quotationItems]);
    }
}
