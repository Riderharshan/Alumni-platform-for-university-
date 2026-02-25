<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ProfessorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Map department names -> ids (works with your existing departments table)
        $deptMap = DB::table('departments')
            ->whereIn('name', ['B.Tech', 'M.Tech', 'BE', 'MBA'])
            ->pluck('id', 'name')
            ->toArray();

        // User IDs you gave (8 to 17)
        $userIds = range(8, 17);

        // Predefined professor entries (10). 
        // Make sure department names used below exist in your departments table.
        $professors = [
            [
                'staff_number' => 'PROF' . str_pad(1, 3, '0', STR_PAD_LEFT),
                'full_name'    => 'Dr. Ramesh K. Kulkarni',
                'display_name' => 'Ramesh Kulkarni',
                'title'        => 'Associate Professor',
                'photo_url'    => null,
                'email'        => 'r.kulkarni@college.edu',
                'phone'        => 'HN-080-23450101',
                'department'   => 'B.Tech',
            ],
            [
                'staff_number' => 'PROF' . str_pad(2, 3, '0', STR_PAD_LEFT),
                'full_name'    => 'Dr. Anita S. Rao',
                'display_name' => 'Anita Rao',
                'title'        => 'Professor',
                'photo_url'    => null,
                'email'        => 'a.rao@college.edu',
                'phone'        => 'HN-080-23450102',
                'department'   => 'M.Tech',
            ],
            [
                'staff_number' => 'PROF' . str_pad(3, 3, '0', STR_PAD_LEFT),
                'full_name'    => 'Prof. Vijay P. Naik',
                'display_name' => 'Vijay Naik',
                'title'        => 'Assistant Professor',
                'photo_url'    => null,
                'email'        => 'v.naik@college.edu',
                'phone'        => 'HN-080-23450103',
                'department'   => 'B.Tech',
            ],
            [
                'staff_number' => 'PROF' . str_pad(4, 3, '0', STR_PAD_LEFT),
                'full_name'    => 'Dr. Sangeeta M. Shetty',
                'display_name' => 'Sangeeta Shetty',
                'title'        => 'Professor',
                'photo_url'    => null,
                'email'        => 's.shetty@college.edu',
                'phone'        => 'HN-080-23450104',
                'department'   => 'BE',
            ],
            [
                'staff_number' => 'PROF' . str_pad(5, 3, '0', STR_PAD_LEFT),
                'full_name'    => 'Prof. Arjun R. Patil',
                'display_name' => 'Arjun Patil',
                'title'        => 'Associate Professor',
                'photo_url'    => null,
                'email'        => 'a.patil@college.edu',
                'phone'        => 'HN-080-23450105',
                'department'   => 'B.Tech',
            ],
            [
                'staff_number' => 'PROF' . str_pad(6, 3, '0', STR_PAD_LEFT),
                'full_name'    => 'Dr. Meera V. Shenoy',
                'display_name' => 'Meera Shenoy',
                'title'        => 'Associate Professor',
                'photo_url'    => null,
                'email'        => 'm.shenoy@college.edu',
                'phone'        => 'HN-080-23450106',
                'department'   => 'M.Tech',
            ],
            [
                'staff_number' => 'PROF' . str_pad(7, 3, '0', STR_PAD_LEFT),
                'full_name'    => 'Prof. Karthik H. Nambiar',
                'display_name' => 'Karthik Nambiar',
                'title'        => 'Assistant Professor',
                'photo_url'    => null,
                'email'        => 'k.nambiar@college.edu',
                'phone'        => 'HN-080-23450107',
                'department'   => 'BE',
            ],
            [
                'staff_number' => 'PROF' . str_pad(8, 3, '0', STR_PAD_LEFT),
                'full_name'    => 'Dr. Leela B. Reddy',
                'display_name' => 'Leela Reddy',
                'title'        => 'Professor',
                'photo_url'    => null,
                'email'        => 'l.reddy@college.edu',
                'phone'        => 'HN-080-23450108',
                'department'   => 'MBA',
            ],
            [
                'staff_number' => 'PROF' . str_pad(9, 3, '0', STR_PAD_LEFT),
                'full_name'    => 'Prof. Manoj C. Desai',
                'display_name' => 'Manoj Desai',
                'title'        => 'Associate Professor',
                'photo_url'    => null,
                'email'        => 'm.desai@college.edu',
                'phone'        => 'HN-080-23450109',
                'department'   => 'B.Tech',
            ],
            [
                'staff_number' => 'PROF' . str_pad(10, 3, '0', STR_PAD_LEFT),
                'full_name'    => 'Dr. Priya N. Kulkar',
                'display_name' => 'Priya Kulkar',
                'title'        => 'Assistant Professor',
                'photo_url'    => null,
                'email'        => 'p.kulkar@college.edu',
                'phone'        => 'HN-080-23450110',
                'department'   => 'MBA',
            ],
        ];

        $insert = [];
        foreach ($professors as $index => $prof) {
            // assign user_id from provided list in order 8..17
            $userId = $userIds[$index] ?? null;

            $deptName = $prof['department'] ?? null;
            $departmentId = $deptMap[$deptName] ?? null;

            $insert[] = [
                'staff_number' => $prof['staff_number'],
                'full_name'    => $prof['full_name'],
                'display_name' => $prof['display_name'],
                'title'        => $prof['title'],
                'photo_url'    => $prof['photo_url'],
                'email'        => $prof['email'],
                'phone'        => $prof['phone'],
                'user_id'      => $userId,
                'department_id'=> $departmentId,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        DB::table('professors')->insert($insert);
    }
}
