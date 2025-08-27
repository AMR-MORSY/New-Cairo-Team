<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Enums\ModificationActions;
use App\Models\Modification\Action;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ModificationActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $actions =  array_column(ModificationActions::cases(), "value");

        foreach ($actions as $action) {
            Action::firstOrCreate(['name' =>$action]);
        }
    }
}
