<?php

namespace App\Livewire\Tables\Users;

use App\Models\Team;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class PermissionsTable extends PowerGridComponent
{
    public string $tableName = 'permissions-table-ir7lon-table';

    public Collection $data;

    public string $dataType;

    public function actions($row): array
    {
        if ($this->dataType == "roles") {
            return [
                Button::add('edit-stock')

                    ->icon('default-eye')
                    ->tooltip('View Permissions')
                    ->class('cursor-pointer')
                    ->openModal('modals.permissions-modal', ['role_id' => $row->id]),
                Button::add('edit-stock')
                    ->icon('default-trash')
                    ->class('cursor-pointer text-red-500')
                    ->tooltip('delete Role')
                    ->confirm('Are you sure you want to delete the role')
                    ->dispatch('deleteRole', ['role_id' => $row->id])
            ];
        } elseif ($this->dataType == 'permissions') {
            return [
                Button::add('edit-stock')
                    ->icon('default-trash')
                    ->class('cursor-pointer text-red-500')
                    ->tooltip('delete permission')
                    ->confirm('Are you sure you want to delete the permission')
                    ->dispatch('deletePermission', ['permission_id' => $row->id])
            ];
        } elseif ($this->dataType == "rolePermissions") {
            return [
                Button::add('edit-stock')
                    ->icon('default-trash')
                    ->class('cursor-pointer text-red-500')
                    ->tooltip('delete permission')
                    ->confirm('Are you sure you want to delete the permission')
                    ->dispatch('deleteRolePermission', ['permission_id' => $row->id])
            ];
        }

        return [];
    }

    public function datasource(): Collection
    {
        if ($this->dataType == "roles") {
            $this->data = $this->data->map(function ($role) {

                $role->team = Team::find($role->team_id);
                return $role;
            });
             return $this->data;
        }
        // dd($this->data);

        return $this->data;
    }

    public function setUp(): array
    {


        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()

                ->showRecordCount(),
        ];
    }

    public function fields(): PowerGridFields
    {
        if ($this->dataType == "roles") {
            return PowerGrid::fields()
                ->add('id')
                ->add('name')
                ->add('guard_name')
                ->add('team', fn($model) => $model->team ? $model->team->code:'Global');
        }
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('guard_name');
    }

    public function columns(): array
    {
        if ($this->dataType == "roles") {
            return [
                Column::make('ID', 'id'),


                Column::make('Name', 'name')
                    ->searchable()
                    ->sortable(),

                Column::make('Guard_Name', 'guard_name')
                    ->sortable(),
                Column::make('Team', 'team')
                    ->sortable(),

                Column::action('Action')


            ];
        }
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


    #[On('deletePermission')]

    public function deletePermission($permission_id)
    {
        $permission = Permission::find($permission_id);
        $permission->delete();
        return redirect()->route('permission.index');
    }

    #[On('deleteRole')]

    public function deleteRole($role_id)
    {
        $role = Role::find($role_id);
        $role->delete();
        return redirect()->route('role.index');
    }
}
