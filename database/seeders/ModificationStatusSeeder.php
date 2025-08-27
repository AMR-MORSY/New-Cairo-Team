<?php

namespace Database\Seeders;

use App\Enums\ModificationStatus;
use App\Models\Modification\ModificationStatus as ModificationModificationStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModificationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses =  array_column(ModificationStatus::cases(), "value");

        foreach ($statuses as $status) {
            ModificationModificationStatus::firstOrCreate(['name' =>$status]);
        }
    }
}
