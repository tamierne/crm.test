<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CreateSuperAdminSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
//            ProjectSeeder::class,
//            TaskSeeder::class,
            StatusListSeeder::class,
        ]);
    }
}
