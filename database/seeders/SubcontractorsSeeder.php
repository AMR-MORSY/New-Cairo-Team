<?php

namespace Database\Seeders;

use App\Enums\Subcontractors;
use App\Models\Modification\Subcontractor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubcontractorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $subcontractors =  array_column(Subcontractors::cases(), "value");

        foreach ($subcontractors as $subcontractor) {
            Subcontractor::firstOrCreate(['name' => $subcontractor]);
        }
    }
}
