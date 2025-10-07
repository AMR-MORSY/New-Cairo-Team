<?php

namespace App\Livewire\Modifications\Components;

use Livewire\Component;
use App\Models\Modification\PurchaseOrder;

class ViewPurchaseOrderModifications extends Component
{
    public  $modifications;
    public  $modification_status_id;
    public  $purchase_order_id;
    public  $PO;
    public function mount($purchase_order_id = null, $modification_status_id = null)
    {
        $this->purchase_order_id =  $purchase_order_id;
        // dd($this->purchase_order_id);

        $this->PO = PurchaseOrder::find($this->purchase_order_id);

        if ($this->modification_status_id == 2) {
            /////This method will load all reservations belonging to the purchase order.
            /// For reservations that do not have a modification with a status of "in progress", the modification relation will be null. 
            ///If your goal is to get only the reservations that have an "in progress" modification, you should use whereHas combined with with
            $reservations = $this->PO->reservations()->whereHas('modification', function ($query) {
                $query->where('modification_status_id', 2);
            })->with('modification', function ($query) {

                $query->where('modification_status_id', 2);
            })->get();
            $modifications = [];
            foreach ($reservations as $reservation) {
                array_push($modifications, $reservation->modification);
            }
            $this->modifications = collect($modifications);
        } else {
            $reservations = $this->PO->reservations()->whereHas('modification', function ($query) {
                $query->where('modification_status_id', 1)->orWhere('modification_status_id', 3);
            })->with('modification', function ($query) {

                $query->where('modification_status_id', 1)->orWhere('modification_status_id', 3);
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
