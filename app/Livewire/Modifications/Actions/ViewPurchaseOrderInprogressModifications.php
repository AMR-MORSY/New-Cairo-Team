<?php

namespace App\Livewire\Modifications\Actions;

use Livewire\Component;
use App\Models\Modification\PurchaseOrder;


class ViewPurchaseOrderInprogressModifications extends Component
{
   
    public  $purchase_order_id;
    public $status = 'In Progress';
    public function mount(PurchaseOrder $purchaseOrder)
    {
        
        $this->purchase_order_id=$purchaseOrder->id;
        // dd($this->PO);
        
    }
    public function render()
    {
       
        return view('livewire.modifications.actions.view-purchase-order-inprogress-modifications');
    }
}
