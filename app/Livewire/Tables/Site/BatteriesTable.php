<?php

namespace App\Livewire\Tables\Site;

use Livewire\Attributes\On;
use App\Models\Site\Battery;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class BatteriesTable extends PowerGridComponent
{
    public string $tableName = 'batteries-table-jrvydm-table';

    public  $batteries;


    // #[On('clickToGoBatteryDetails')]
    // public function batteryDetails($battery_id) {}

    public function actions($row): array
    {
        return [
            Button::add('go-to-battery.show')
                ->icon('default-eye')
                ->class('cursor-pointer text-green-500')
                ->tooltip('view')
                ->route('battery.show', ['battery' => $row->id]),

        ];
    }
    public function datasource(): Collection
    {
        if ($this->batteries) {

            return $this->batteries;
        }
        return collect();
    }

    public function setUp(): array
    {


        return [

            PowerGrid::responsive()->fixedColumns('Brand', 'No.strings')
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('batteries_brand')
            ->add('stock')
            ->add('comment')
            ->add('category')
            ->add('battery_amp_hr')
            ->add('battery_volt')
            ->add('no_strings')
            ->add('installation_date')
            ->add('theft_case')
            ->add('batteries_status');
    }

    public function columns(): array
    {
        return [
            Column::make('Brand', 'batteries_brand'),
            Column::make('No.strings', 'no_strings'),
            Column::make('Stock', 'stock'),
            Column::make('Category', 'category'),
            Column::make('Inst. date', 'installation_date'),
            Column::make('Theft case date', 'theft_case'),
            Column::make('Batteries Status', 'batteries_status'),
            Column::make('Amp_Hr', 'battery_amp_hr'),
            Column::make('battery_volt', 'battery_volt'),
            Column::make('Comment', 'comment'),
            Column::action('Action')
        ];
    }
}
