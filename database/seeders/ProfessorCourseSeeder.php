<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ProfessorCourseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        /**
         * Mapping: professor identifier => list of course codes (or names)
         *
         * Use staff_number as the professor key (change to 'user_id' or 'full_name' if you prefer).
         * Course identifiers are course 'code' where possible; fallback to 'name' search will be attempted.
         */
        $map = [
            'PROF001' => ['CSE', 'CIVIL'],             // Dr. Ramesh K. Kulkarni
            'PROF002' => ['MTECH-CSE', 'MTECH-VLSI'], // Dr. Anita S. Rao
            'PROF003' => ['ISE', 'CSE'],
            'PROF004' => ['CIVIL', 'INDENG'],
            'PROF005' => ['MECH', 'MTECH-MD'],
            'PROF006' => ['MTECH-THERM', 'MECH'],
            'PROF007' => ['EEE', 'AUTO'],
            'PROF008' => ['MBA-FIN', 'MBA-ANL'],
            'PROF009' => ['CSE', 'ECE'],
            'PROF010' => ['MBA-ANL'],
        ];

        foreach ($map as $profKey => $courseKeys) {
            // find professor by staff_number
            $prof = DB::table('professors')->where('staff_number', $profKey)->first();

            if (! $prof) {
                Log::warning("ProfessorCourseSeeder: professor with staff_number {$profKey} not found. Skipping.");
                continue;
            }

            foreach ($courseKeys as $ck) {
                // try to find course by code first, then by name (case-insensitive)
                $course = DB::table('courses')->where('code', $ck)->first();
                if (! $course) {
                    // fallback to name match
                    $course = DB::table('courses')
                        ->whereRaw('LOWER(name) = ?', [mb_strtolower($ck)])
                        ->first();
                }

                if (! $course) {
                    Log::warning("ProfessorCourseSeeder: course '{$ck}' not found for professor {$profKey}. Skipping.");
                    continue;
                }

                // ensure uniqueness before insert (or rely on DB unique constraint)
                $exists = DB::table('professor_courses')
                    ->where('professor_id', $prof->id)
                    ->where('course_id', $course->id)
                    ->exists();

                if ($exists) {
                    // already mapped
                    continue;
                }

                DB::table('professor_courses')->insert([
                    'professor_id' => $prof->id,
                    'course_id'    => $course->id,
                    'created_at'   => $now,
                ]);
            }
        }
    }
}
