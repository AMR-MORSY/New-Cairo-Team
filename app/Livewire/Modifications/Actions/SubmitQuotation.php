<?php

namespace App\Livewire\Modifications\Actions;

use App\Models\Modification\MailListItem;
use Livewire\Component;
use Livewire\Attributes\On;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use App\Models\Modification\Modification;
use Illuminate\Support\Facades\Validator;
use App\Models\Modification\PriceListItem;
use App\Models\Modification\Quotation;
use Illuminate\Database\Eloquent\Collection;

class SubmitQuotation extends Component
{
    public object|null $quotation = null;

    protected object $item;

    public object $modification;


    public array $selectedItems = [];


    #[Validate(['required_if:search_option,item,description', 'regex:/^.{1,50}$/', 'max:50'])]
    public $search = '';
    #[Validate(['required', "in:item,description,mail"])]
    public $search_option = 'item';



    // public function mount(Modification $modification)
    // {

    //      $this->modification = $modification;

    //     $this->quotation = $modification->quotation;


    // }
    public function searchPriceList()
    {

        $this->validate();

        if ($this->search_option == "item") {
            $this->item = PriceListItem::where('item', $this->search)->get();
        } else if ($this->search_option == "description") {
            $this->item = PriceListItem::where('description', 'like', "%$this->search%")->get();
        } else {
            $this->item = MailListItem::where('description', 'like', "%$this->search%")->get();
        }



        if (count($this->item) > 0) {
            $this->item = $this->item->map(function ($item) {
                $item->scope = "";
                $item->quantity = 0;
                return $item;
            });
            // dd($this->item);

            $props = [
                'title' => "Price List items",
                'data' => $this->item,

            ];

            //  dd($props);

            $this->dispatch('openDynamicModal', 'tables.modification.searched-price-list-item-table', $props);
        } else {
            Toaster::info('No data found');
        }
    }


    #[On("removeSelectedItem")]
    public function removeSelectedItem($id)
    {
        $this->selectedItems = array_filter($this->selectedItems, function ($item) use ($id) {
            return $item['id'] != $id;
        });

        $selectedItems = collect($this->selectedItems);

        $this->dispatch('selectedItemsUpdated', selectedItems: $selectedItems);
    }

    #[On('selectedItemEdited')]
    public function selectedItemEdited($selectedItem)
    {
        $this->dispatch('modalClosed');

        $result = array_filter($this->selectedItems, function ($item) use ($selectedItem) {
            return $item['id'] === $selectedItem['id'];
        });

        if (count($result) > 0) {
            Toaster::error('Item already exists in the quotation table');
        } else {
            array_push($this->selectedItems, $selectedItem);

            $selectedItems = collect($this->selectedItems);

            $this->dispatch('selectedItemsUpdated', selectedItems: $selectedItems);
            Toaster::success('Item Added successfully to quotation table');
        }
    }

    protected function getItemPrice(float $quantity, string $scope, float $install_price, float $supply_price) //////code repetition here as this function exists in quotation import class
    {
        if ($scope == 'supply') {
            // if ($supply_price != null) {
            return $quantity * $supply_price;
            // } else {
            //     return 0;
            // }
        } elseif ($scope == 'install') {
            // if ($install_price != null) {
            return $quantity * $install_price;
            // } else {
            //     return 0;
            // }
        } elseif ($scope == 'S&I') {
            return $quantity * ($supply_price + $install_price);
        }
    }

    private function createPivotTable($selectedItems)
    {
        foreach ($selectedItems as $item) {
            $pivotData = [
                'supply_price' => $item['supply'],
                'install_price' => $item['installation'],
                'item_price' => $this->getItemPrice(floatval($item['quantity']), $item['scope'], floatval($item['installation']), floatval($item['supply'])),
                'quantity' => floatval($item['quantity']),
                'scope' => $item['scope']

            ];
            if ($item['item'] == 'Mail') {
                $this->quotation->mailListItems()->attach(
                    $item['id'],
                    $pivotData
                );
            } else {
                $this->quotation->priceListItems()->attach(
                    $item['id'],
                    $pivotData
                );
            }
        }

        return redirect()->route('quotation.details', ['modification' => $this->modification->id]);
    }

    // private function getSelectedItemsTotalCost($selectedItems)
    // {
    //     $newSelected = array_map(function ($item) {
    //         return  $item['item_price'] = $this->getItemPrice(floatval($item['quantity']), $item['scope'], floatval($item['installation']), floatval($item['supply']));
    //     }, $selectedItems,);

    //     $totalCost=array_sum($newSelected);
    //     return $totalCost;
    // }

    #[On("insert_Quotation")]
    public function uploadQuotation($selectedItems)
    {


        if (!$this->modification->quotation) {
            // $totalCost=$this->getSelectedItemsTotalCost($selectedItems);

            $this->quotation = Quotation::create([
                'modification_id' =>  $this->modification->id
            ]);
            $this->createPivotTable($selectedItems);
        } else {


            $errors = [];

            foreach ($selectedItems as $item) {
                $priceListItem = $this->modification->quotation->priceListItems()->wherePivot('price_list_item_id', $item['id'])->first();

                if ($priceListItem) {
                    array_push($errors, $priceListItem);
                }
                $mailListItem = $this->modification->quotation->mailListItems()->wherePivot('mail_list_item_id', $item['id'])->first();
                if ($mailListItem) {
                    array_push($errors, $mailListItem);
                }
            }
            if (count($errors) > 0) {
                Toaster::error('Item already exists');
            } else {
                $this->quotation = $this->modification->quotation;
                $this->createPivotTable($selectedItems);
            }
        }
    }
    public function render()
    {
        return view('livewire.modifications.actions.submit-quotation', [
            'selectedItems' => $this->selectedItems
        ]);
    }
}
