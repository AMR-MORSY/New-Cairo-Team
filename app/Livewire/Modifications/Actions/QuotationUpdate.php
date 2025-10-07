<?php

namespace App\Livewire\Modifications\Actions;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;
use App\Models\Modification\Invoice;
use App\Models\Modification\Quotation;
use App\Models\Modification\OverPoInvoice;

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

    private function manipulatePO($po, $invoiceAmount, $totalCost, $target)
    {
        if ($target == 'invoice') {
            $po->decrement('invoiced', $invoiceAmount);
            $po->increment('on_hand', $invoiceAmount);
            $onHand = $po->getAvailableAmount();
            if ($totalCost <= $onHand) {
                $po->increment('invoiced', $totalCost);
                $po->decrement('on_hand', $totalCost);
                $this->quotation->modification->reservation->invoice->update(['amount' => $totalCost]);
            } else {
                $overPo = OverPoInvoice::create([
                    'modification_reservation_id' => $this->quotation->modification->reservation->id,
                    'amount' => $totalCost,
                    'reserved_at' => now(),
                ]);

                $this->quotation->modification->reservation->invoice->delete();
            }
        } else {
            $onHand = $po->getAvailableAmount();
            if ($totalCost <= $onHand) {
                $po->increment('invoiced', $totalCost);
                $po->decrement('on_hand', $totalCost);
                $invoice = Invoice::create([
                    'modification_reservation_id' => $this->quotation->modification->reservation->id,
                    'amount' => $totalCost,
                    'reserved_at' => now(),
                ]);
                $this->quotation->modification->reservation->overPo->delete();
            } else {

                $this->quotation->modification->reservation->overPo->update(['amount' => $totalCost]);
            }
        }
    }

    #[On('removeItemFromQuotation')]

    public function removeItemFromQuotation($row)
    {

        // $this->selectedItems = array_filter($this->selectedItems, function ($item) use ($id) {
        //     return $item['id'] != $id;
        // });

        // if ($this->modification->quotation->is_active == 0) { ////////quotation is not invoiced yet 
        //     $selectedItems = collect($this->selectedItems);

        //     $this->dispatch('selectedItemsUpdated', selectedItems: $selectedItems);
        // } else { ///////////////////////invoiced quotation

        //     $selectedItems = $this->selectedItems;

        //     $selectedItemsCost = $this->getSelectedItemsTotalCost($selectedItems);
        //     $totalCost = ($this->modification->quotation->sumPriceListItems() + $this->modification->quotation->sumMailListItems())- $selectedItemsCost ;
        //     $po = $this->modification->reservation->po;

        //     if ($this->modification->reservation->invoice) {
        //         $invoiceAmount = $this->modification->reservation->invoice->amount;
        //         $this->manipulatePO($po, $invoiceAmount, $totalCost, 'invoice');
        //     } elseif ($this->modification->reservation->overPo) {
        //         $invoiceAmount = $this->modification->reservation->overPo->amount;
        //         $this->manipulatePO($po, $invoiceAmount, $totalCost, 'overPo');
        //     }
        //     $this->modification->update(['final_cost' => $totalCost]);
        //     $this->createPivotTable($selectedItems);
        // }


        $modification_id = $this->quotation->modification->id;


        // dd($row);
        if ($this->quotation->is_active == 0) {

            if ($row['item'] == 'Mail') {



                $this->quotation->mailListItems()->wherePivot('id', $row['pivot']['id'])->detach();

                // dd($countMailItems);

            } else {
                $this->quotation->priceListItems()->wherePivot('id', $row['pivot']['id'])->detach();
            }
        } elseif ($this->quotation->is_active == 1) {
            $selectedItemCost = floatval($row['pivot']['item_price']);
            $totalCost = ($this->quotation->sumPriceListItems() + $this->quotation->sumMailListItems()) - $selectedItemCost;
            $po = $this->quotation->modification->reservation->po;
            if ($this->quotation->modification->reservation->invoice) {
                $invoiceAmount = $this->quotation->modification->reservation->invoice->amount;
                $this->manipulatePO($po, $invoiceAmount, $totalCost, 'invoice');
            } elseif ($this->quotation->modification->reservation->overPo) {
                $invoiceAmount = $this->quotation->modification->reservation->overPo->amount;
                $this->manipulatePO($po, $invoiceAmount, $totalCost, 'overPo');
            }

            if ($row['item'] == 'Mail') {


                //   dd($this->quotation->mailListItems()->count());
                $this->quotation->mailListItems()->wherePivot('id', $row['pivot']['id'])->detach();

                // dd($countMailItems);

            } else {
                $this->quotation->priceListItems()->wherePivot('id', $row['pivot']['id'])->detach();
            }
            $this->quotation->modification->calculateFinalCost();
        }



        $countMailItems = $this->quotation->mailListItems()->count();
        $countPriceItems = $this->quotation->priceListItems()->count();

        if (!$countMailItems > 0 &&  !$countPriceItems > 0) {

            if ($this->quotation->modification->reservation->invoice) {
                $this->quotation->modification->reservation->invoice->delete();
            } elseif ($this->quotation->modification->reservation->overPo) {
                $this->quotation->modification->reservation->overPo->delete();
            }
            $this->quotation->modification->reservation->update(['status'=>"cancelled"]);
            $this->quotation->modification->update(['modification_status_id'=>2]);
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
