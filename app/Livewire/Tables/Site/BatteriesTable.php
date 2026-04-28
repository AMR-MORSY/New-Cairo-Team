<?php

namespace App\Livewire\Tables\Site;

use App\Models\Zone;
use App\Models\Site\Site;
use Livewire\Attributes\On;
use App\Models\Site\Battery;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use Illuminate\Contracts\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

final class BatteriesTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'batteries-table-jrvydm-table';

    public  $site;

    public $oz;




    public function actions($row): array
    {
        if ($this->site) {
            return [
                Button::add('go-to-battery.show')
                    ->icon('default-eye')
                    ->class('cursor-pointer text-green-500')
                    ->tooltip('view')
                    ->route('battery.show', ['battery' => $row->id]),

            ];
        }
        return [];
    }
    public function datasource(): ?Builder
    {


        if ($this->site) {
            return Battery::query()
                ->with(['site'])
                ->whereHas('site', function ($query) {
                    $query->where('site_code', $this->site->site_code);
                });
        } elseif ($this->oz) {
            return  Battery::query()
                ->with(['site'])
                ->whereHas('site', function ($query) {
                    $query->where('zone_id', $this->oz);
                });
        }
        return Battery::query();
    }

    public function setUp(): array
    {

        $setup = [PowerGrid::header()
            ->showSearchInput(), 
            PowerGrid::responsive()->fixedColumns('Brand', 'No.strings')];
        if ($this->oz) {
            $setup[] =   PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount();
            $setup[]  =   PowerGrid::exportable(fileName: 'CE Batteries')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV);
            return $setup;
        }

        return $setup;
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('site_code', fn($model) => $model ? $model->site->site_code : '')
            ->add('site_name', fn($model) => $model ? $model->site->site_name : '')
            ->add('zone', fn($model) => $model ? Zone::find($model->site->zone_id)->name : '')
            ->add('batteries_brand')
            ->add('stock')
            ->add('comment')
            ->add('category')
            ->add('battery_amp_hr')
            ->add('battery_volt')
            ->add('no_strings', fn($model) => $model ? number_format($model->no_strings) : '')
            ->add('installation_date')
            ->add('theft_case')
            ->add('batteries_status');
    }

    public function columns(): array
    {
        $columns = [
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

        if ($this->site) {

            return $columns;
        } elseif ($this->oz) {
            array_unshift(    ///////////////to be added to the beginning of the array 
                $columns,


                Column::make('Code', 'site_code')->searchable(),
                Column::make('Name', 'site_name'),
                Column::make('Zone', 'zone')
            );

            return $columns;
        }
        return [];
    }
}
