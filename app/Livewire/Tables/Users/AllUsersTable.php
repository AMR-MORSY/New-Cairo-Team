<?php

namespace App\Livewire\Tables\Users;

use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class AllUsersTable extends PowerGridComponent
{
    public string $tableName = 'all-users-table-hy8kug-table';

    public Collection $users;


    public function actions($row): array
    {
        return [
            Button::add('edit-stock')

                ->icon('default-eye')
                ->class('cursor-pointer')
                ->dispatch('clickToGoUserDetail', ['id' => $row->id]),
        ];
    }

    public function datasource(): Collection
    {

        return $this->users;
    }

    public function setUp(): array
    {
        $this->showRadioButton();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
            // PowerGrid::responsive()
            //     ->fixedColumns('name', 'email'),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('email');
        // ->add('created_at_formatted', function ($entry) {
        //     return Carbon::parse($entry->created_at)->format('d/m/Y');
        // });
    }

    public function columns(): array
    {
        return [


            Column::make('Name', 'name')
                ->searchable()
                ->sortable(),

            Column::make('Email', 'email')
                ->sortable(),

            Column::action('View')

            // Column::make('Created', 'created_at_formatted'),


        ];
    }
    #[On('clickToGoUserDetail')]
    public function clickToGoUserDetail(int $id)
    {
        return redirect()->route('user.show', ['user' => $id]);
    }
}
