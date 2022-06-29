<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'Completed',
            'Processing',
            'Cancelled',
            'On Hold',
        ];

        foreach ($statuses as $status)
        {
            DB::table('statuses')->insert([
                'name' => $status,
            ]);
        }
    }
}
