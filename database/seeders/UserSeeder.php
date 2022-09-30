<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Arr;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()
            ->count(20)
            ->has(Project::factory(mt_rand(1, 5))
                ->has(Task::factory(mt_rand(5, 10))))
            ->create();

        foreach ($users as $user) {
            $user->assignRole(
                Arr::random([
                    'user',
                    'manager',
                    'admin',
                ], 1)
            );

            if ($user->hasRole('manager')) {
                $user
                    ->clients()
                    ->saveMany(
                        Client::factory()
                            ->count(
                                mt_rand(1, 8)
                            )
                            ->make()
                    );
            }
        }
    }
}
