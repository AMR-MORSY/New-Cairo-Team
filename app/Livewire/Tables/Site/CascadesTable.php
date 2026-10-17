<?php

namespace App\Livewire\Tables\Site;

use App\Models\Site\Site;
use Livewire\Attributes\On;
use App\Models\Site\Cascade;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Repositories\SiteRepository;
use Illuminate\Database\Eloquent\Builder;
use Pest\Mutate\Mutators\ControlStructures\IfNegated;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use App\Services\Sites\SiteRouteMap;
use App\Support\PowerGrid\Themes\CompactDaisyUI;
final class CascadesTable extends PowerGridComponent
{
    public string $tableName = 'cascades-table-lvjnmv-table';


    // public  $cascades;

    public object $site;

    public string $cascadesType;

    // protected $siteRepository;


    /** A dozen columns wide; the compact theme keeps them on one screen. */
    public function customThemeClass(): ?string
    {
        return CompactDaisyUI::class;
    }

    public function __construct()
    {

        // $this->siteRepository = app(SiteRepository::class);
    }

    public function actions($row): array
    {

        // if ($this->cascadesType == 'direct') {
        //     return [
        //         Button::add('edit-stock')

        //             ->icon('default-eye')
        //             ->class('cursor-pointer')
        //             ->dispatch('clickToGOSiteDetails', ['site_code' => $row->cascade_code]),
        //     ];
        //  } 
         if ($this->cascadesType == 'updateCascades') {
            return [
                Button::add('edit-stock')
                    ->icon('default-trash')
                    ->class('cursor-pointer text-red-500')
                    ->confirm('Are you sure you want to delete this site?')
                    ->tooltip('Delete')
                    ->dispatch('removeCascade', ['site_code' => $row->site_code]),
            ];
        } else {
            return [
                Button::add('edit-stock')
                    ->icon('default-eye')
                    ->class('cursor-pointer')
                    ->dispatch('clickToGOSiteDetails', ['site_code' =>  $row->site_code]),
            ];
        }
    }


    public function datasource(): ?Builder
    {

        // if ($this->cascadesType == 'direct' || $this->cascadesType == 'updateCascades') {

            // return Cascade::query()->where('nodal_code', $this->site->site_code);
            $map = new SiteRouteMap();
            $cascades = $map->cascadedCodes($this->site->site_code);
            return   Site::query()->whereIn('site_code', $cascades);
        // } elseif ($this->cascadesType == 'indirect') {
            $indirectCascades = $this->siteRepository->indirectCascades($this->site->cascades);

            $indirectCascades = collect($indirectCascades)->pluck('site_code')->toArray();


            return Site::query()->whereIn('site_code', $indirectCascades);
        // }

        return Cascade::query()->whereRaw('1 = 0');
    }

    public function setUp(): array
    {


        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),

        ];
    }

    public function fields(): PowerGridFields
    {
        // if ($this->cascadesType == 'direct' || $this->cascadesType == 'updateCascades') {
            // return PowerGrid::fields()
            //     ->add('id')
            //     ->add('site_code', fn($model) => $model ? $model->cascade_code : '')
            //     ->add('site_name', fn($model) => $model ? Site::where('site_code', $model->cascade_code)->first()->site_name  : '')
            //     ->add('count', function ($site) {
            //         return count($this->siteRepository->indirectCascades(Site::where('site_code', $site->cascade_code)->first()->cascades));
            //     });

            // return PowerGrid::fields()
            //     ->add('id')
            //     ->add('site_code')
            //     ->add('site_name');
        // }
        return PowerGrid::fields()
            ->add('id')
            ->add('site_code')
            ->add('site_name');
    }
    // <-- Required for actions


    public function columns(): array
    {
        // if ($this->cascadesType == 'indirect') {
        //     return [


        //         Column::make('Code', 'site_code')
        //             ->sortable(),
        //         Column::make('Name', 'site_name')
        //             ->searchable()
        //             ->sortable(),
        //         Column::action('Action')
        //     ];
        // }
        return [

            Column::make('Code', 'site_code')
                ->searchable()
                ->sortable(),

            Column::make('Name', 'site_name')
                ->searchable()
                ->sortable(),

            // Column::make('Count Cascades', 'count')
            //     ->sortable(),
            Column::action('Action'),




        ];
    }

    #[On('clickToGOSiteDetails')]
    public function clickToGOSiteDetails($site_code)
    {


        return redirect()->route('site.show', ['site' => $site_code]);
    }
}
