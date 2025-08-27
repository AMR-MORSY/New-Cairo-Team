<?php

namespace Database\Seeders;

use App\Enums\Requesters;
use Illuminate\Database\Seeder;
use App\Enums\ModificationRequesters;
use App\Models\Modification\Requester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RequestersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requesters =  array_column(ModificationRequesters::cases(), "value");

        foreach ($requesters as $subcontractor) {
            Requester::firstOrCreate(['name' => $subcontractor]);
        }
    }
}
