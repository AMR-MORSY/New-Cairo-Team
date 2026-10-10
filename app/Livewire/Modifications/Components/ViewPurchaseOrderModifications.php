<?php

namespace App\Livewire\Modifications\Components;

use App\Enums\ModificationStatus;

use Livewire\Component;
use App\Models\Modification\PurchaseOrder;

class ViewPurchaseOrderModifications extends Component
{
    public  $modifications;
    public $status;
    public  $purchase_order_id;
    public  $PO;
    public function mount($purchase_order_id = null, $status = null)
    {
        $this->purchase_order_id =  $purchase_order_id;
        // dd($this->purchase_order_id);

        $this->PO = PurchaseOrder::find($this->purchase_order_id);

        if (ModificationStatus::tryFrom($this->status)?->isInProgress()) {
            /////This method will load all reservations belonging to the purchase order.
            /// For reservations that do not have a modification with a status of "in progress", the modification relation will be null. 
            ///If your goal is to get only the reservations that have an "in progress" modification, you should use whereHas combined with with
            $reservations = $this->PO->reservations()->whereHas('modification', function ($query) {
                $query->where('status', ModificationStatus::IN_PROGRESS->value);
            })->with('modification', function ($query) {

                $query->where('status', ModificationStatus::IN_PROGRESS->value);
            })->get();
            $modifications = [];
            foreach ($reservations as $reservation) {
                array_push($modifications, $reservation->modification);
            }
            $this->modifications = collect($modifications);
        } else {
            $reservations = $this->PO->reservations()->whereHas('modification', function ($query) {
                $query->whereIn('status', ModificationStatus::quotedValues());
            })->with('modification', function ($query) {

                $query->whereIn('status', ModificationStatus::quotedValues());
            })->get();
            $modifications = [];
            foreach ($reservations as $reservation) {
                array_push($modifications, $reservation->modification);
            }
            $this->modifications = collect($modifications);
        }
    }
    public function render()
    {
        return view('livewire.modifications.components.view-purchase-order-modifications');
    }
}
