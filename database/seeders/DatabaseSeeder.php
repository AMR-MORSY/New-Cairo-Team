<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\ZoneSeder;
use Illuminate\Database\Seeder;
use Database\Seeders\UsersSeeder;
use Database\Seeders\ProjectsSeeder;
use Database\Seeders\RequestersSeeder;
use Database\Seeders\ModificationSeeder;
use Database\Seeders\AreasAndZonesSeeder;
use Database\Seeders\TeamsAndZonesSeeder;
use Database\Seeders\SubcontractorsSeeder;
use Database\Seeders\ModificationStatusSeeder;
use Database\Seeders\ModificationActionsSeeder;
use Database\Seeders\ModificationRolesAndPermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // TeamsAndZonesSeeder::class,
            // ModificationRolesAndPermissionsSeeder::class,
            // UsersSeeder::class,
            // ProjectsSeeder::class,
            // ModificationActionsSeeder::class,
            // SubcontractorsSeeder::class,
            // RequestersSeeder::class,
            // ModificationStatusSeeder::class

             ModificationSeeder::class
            
           

        ]);
        
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
