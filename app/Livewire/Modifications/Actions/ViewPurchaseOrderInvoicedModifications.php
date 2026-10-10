<?php

namespace App\Livewire\Modifications\Actions;

use Livewire\Component;
use App\Models\Modification\PurchaseOrder;

class ViewPurchaseOrderInvoicedModifications extends Component
{
    public object $PO;
    public string $status = 'Done';
    public function mount(PurchaseOrder $purchaseOrder)
    {
        $this->PO=$purchaseOrder;
        
    }
    public function render()
    {
        return view('livewire.modifications.actions.view-purchase-order-invoiced-modifications');
    }
}
