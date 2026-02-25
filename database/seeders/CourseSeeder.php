<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Pull department ids by name so the seeder works regardless of numeric IDs.
        $deptMap = DB::table('departments')
            ->whereIn('name', [
                'B.Tech', 'M.Tech', 'BE', 'MBA'
            ])
            ->pluck('id', 'name')
            ->toArray();

        $courses = [
            // B.Tech (5)
            [
                'name' => 'Computer Science and Engineering',
                'code' => 'CSE',
                'department_id' => $deptMap['B.Tech'] ?? null,
            ],
            [
                'name' => 'Information Science and Engineering',
                'code' => 'ISE',
                'department_id' => $deptMap['B.Tech'] ?? null,
            ],
            [
                'name' => 'Electronics and Communication Engineering',
                'code' => 'ECE',
                'department_id' => $deptMap['B.Tech'] ?? null,
            ],
            [
                'name' => 'Mechanical Engineering',
                'code' => 'MECH',
                'department_id' => $deptMap['B.Tech'] ?? null,
            ],
            [
                'name' => 'Civil Engineering',
                'code' => 'CIVIL',
                'department_id' => $deptMap['B.Tech'] ?? null,
            ],

            // M.Tech (5)
            [
                'name' => 'M.Tech — Computer Science',
                'code' => 'MTECH-CSE',
                'department_id' => $deptMap['M.Tech'] ?? null,
            ],
            [
                'name' => 'M.Tech — VLSI Design',
                'code' => 'MTECH-VLSI',
                'department_id' => $deptMap['M.Tech'] ?? null,
            ],
            [
                'name' => 'M.Tech — Structural Engineering',
                'code' => 'MTECH-STRUCT',
                'department_id' => $deptMap['M.Tech'] ?? null,
            ],
            [
                'name' => 'M.Tech — Thermal Engineering',
                'code' => 'MTECH-THERM',
                'department_id' => $deptMap['M.Tech'] ?? null,
            ],
            [
                'name' => 'M.Tech — Machine Design',
                'code' => 'MTECH-MD',
                'department_id' => $deptMap['M.Tech'] ?? null,
            ],

            // BE (3)
            [
                'name' => 'Electrical and Electronics Engineering',
                'code' => 'EEE',
                'department_id' => $deptMap['BE'] ?? null,
            ],
            [
                'name' => 'Automobile Engineering',
                'code' => 'AUTO',
                'department_id' => $deptMap['BE'] ?? null,
            ],
            [
                'name' => 'Industrial Engineering',
                'code' => 'INDENG',
                'department_id' => $deptMap['BE'] ?? null,
            ],

            // MBA (2)
            [
                'name' => 'MBA — Finance',
                'code' => 'MBA-FIN',
                'department_id' => $deptMap['MBA'] ?? null,
            ],
            [
                'name' => 'MBA — Business Analytics',
                'code' => 'MBA-ANL',
                'department_id' => $deptMap['MBA'] ?? null,
            ],
        ];

        // attach timestamps and insert
        $now = Carbon::now();
        foreach ($courses as &$c) {
            $c['created_at'] = $now;
            $c['updated_at'] = $now;
        }
        DB::table('courses')->insert($courses);
    }
}
