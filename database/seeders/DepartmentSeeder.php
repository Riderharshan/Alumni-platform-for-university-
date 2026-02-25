<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('departments')->insert([
            [
                'name'       => 'B.Tech',
                'code'       => 'BTECH',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'M.Tech',
                'code'       => 'MTECH',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'BE',
                'code'       => 'BE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'MBA',
                'code'       => 'MBA',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
