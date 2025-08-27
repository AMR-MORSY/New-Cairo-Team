<?php

namespace Database\Seeders;

use App\Enums\ModificationProjects;
use App\Models\Modification\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects =  array_column(ModificationProjects::cases(), "value");

        foreach ($projects as $project) {
            Project::firstOrCreate(['name' =>$project]);
        }
    }
}
