<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StudentsRawSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('en_IN');

        foreach (range(1, 50) as $index) {
            DB::table('students_raw')->insert([
                'usn'          => $this->generateUSN(),
                'full_name'    => $faker->name,
                'batch_year'   => $faker->numberBetween(2010, 2025),
                'course'       => $faker->randomElement(['B.E', 'B.Tech', 'M.Tech', 'MBA']),
                'department'   => $faker->randomElement(['Computer Science', 'Information Science', 'Electronics', 'Mechanical', 'Civil']),
                'date_of_birth'=> $faker->dateTimeBetween('-30 years', '-18 years')->format('Y-m-d'),
                'mobile'       => $faker->numerify('+91##########'),
                'email'        => $faker->unique()->safeEmail,
                'gender'       => $faker->randomElement(['Male', 'Female']),
                'address'      => $faker->address,
                'blood_group'  => $faker->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
                'extra'        => json_encode([
                    'hobbies' => $faker->words(3),
                    'skills'  => $faker->words(2),
                ]),
                'user_id'      => null, // can link later
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }

    /**
     * Generate USN like: 413CS14016
     */
    private function generateUSN(): string
    {
        $collegeCode = '413';
        $branchCode  = collect(['CS', 'IS', 'EC', 'ME', 'CE'])->random();
        $year        = str_pad(rand(14, 25), 2, '0', STR_PAD_LEFT); // 2014 - 2025
        $rollNumber  = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

        return $collegeCode . $branchCode . $year . $rollNumber;
    }
}
