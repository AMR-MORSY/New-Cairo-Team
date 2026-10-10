<?php

namespace App\Livewire\Tables\Users;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class RolesTable extends PowerGridComponent
{
    public string $tableName = 'roles-table-17nh3h-table';


    public Collection|null $data;



    public function actions($row): array
    {

        return [
            Button::add('edit-stock')

                ->icon('default-eye')
                ->tooltip('View Permissions')
                ->class('cursor-pointer')
                ->dispatch('openPermissionsModal', ['role_id' => $row->id]),
            Button::add('edit-stock')
                ->icon('default-trash')
                ->class('cursor-pointer text-red-500')
                ->tooltip('delete permission')
                ->confirm('Are you sure you want to delete the role')
                ->dispatch('deleteRole', ['role_id' => $row->id])
        ];
    }

    public function datasource(): Collection
    {
        if ($this->data) {

            return $this->data;
        };
        return collect();
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
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('guard_name');
    }

    public function columns(): array
    {
        return [

            Column::make('ID', 'id'),


            Column::make('Name', 'name')
                ->searchable()
                ->sortable(),

            Column::make('Guard_Name', 'guard_name')
                ->sortable(),

            Column::action('Action')



        ];
    }

    #[On('deleteRole')]

    public function deleteRole($role_id)
    {
        $role = Role::find($role_id);
        $role->delete();
        return redirect()->route('role.index');
    }
    #[On("openPermissionsModal")]

    public function openPermissionsModal($role_id)
    {
        return redirect()->route('role.permissions', $role_id);
    }
}
