<?php

namespace App\Livewire\Tables\Site;

use App\Models\Site\Site;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use Illuminate\Contracts\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class SearchedSitesTable extends PowerGridComponent
{
    public string $tableName = 'searched-sites-table-w6g9gq-table';

    #[Locked]
    public  $props;

    public int $perPage = 0;

    public function actions($row): array
    {
        return [
            Button::add('edit-stock')

                ->icon('default-eye')
                ->class('cursor-pointer')
                ->dispatch('clickToGOSiteDetails', ['site_code' => $row->site_code]),
        ];
    }
    public function datasource(): ?Builder
    {
        if ($this->props && isset($this->props['data']) && count($this->props['data']) > 0) {
            // Get the IDs from props
            $ids = collect($this->props['data'])->pluck('site_code')->toArray();

            // Return a query builder filtered by those IDs
            return Site::query()->whereIn('site_code', $ids);
        }

        // Return empty query
        return Site::query()->whereRaw('1 = 0');
    }
    // public function datasource(): Collection
    // {



    // First try props
    // if ($this->props && isset($this->props['data'])) {
    //     return collect($this->props['data']);
    // }

    // // Fallback to session
    // if (session()->has('last_searched_sites')) {
    //     return collect(session('last_searched_sites'));
    // }
    // return collect();
    //    if($this->props && isset($this->props['data'])) {
    //     $collection = collect($this->props['data']);

    //     \Log::info('Datasource being returned', [
    //         'count' => $collection->count(),
    //         'perPage_property' => $this->perPage ?? 'not set',
    //         'page_property' => $this->page ?? 'not set',
    //         'totalCurrentPage_property' => $this->totalCurrentPage ?? 'not set',
    //     ]);

    //     return $collection;
    // }

    // if(session()->has('last_searched_sites')) {
    //     return collect(session('last_searched_sites'));
    // }

    // return collect();
    // }


    public function setUp(): array
    {


        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->pageName('searchedSitesPage')
                ->showRecordCount(),

        ];  
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('site_code')
            ->add('site_name')
            ->add('zone_name');
    }

    public function columns(): array
    {
        return [
            Column::make('Code', 'site_code')
                ->searchable()
                ->sortable(),

            Column::make('Name', 'site_name')
                ->searchable()
                ->sortable(),

            Column::make('OZ', 'zone_name')
                ->sortable(),

            Column::action('View')
        ];
    }


    #[On('clickToGOSiteDetails')]
    public function clickToGOSiteDetails(string $site_code)
    {
        return redirect()->route('site.show', ['site' => $site_code]);
    }
}
