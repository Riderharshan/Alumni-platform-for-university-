<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class AlumniProfessorSelectionSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $userIds = range(8, 17); // the user_id values you asked for
        $inserted = 0;

        // demo branch pool
        $branches = ['CSE','ECE','MECH','CIVIL','EEE','MBA'];

        foreach ($userIds as $uid) {
            // ensure user exists (create a minimal demo user if missing)
            $userExists = DB::table('users')->where('id', $uid)->exists();
            if (! $userExists) {
                // Insert minimal user record so FK won't fail.
                // Adjust fields as per your users table if you require.
                DB::table('users')->insert([
                    'id' => $uid,
                    'name' => "Demo User {$uid}",
                    'email' => "user{$uid}@example.com",
                    'password' => Hash::make('password123'),
                    'role_id' => 2,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $this->command->info("Created demo user id={$uid}");
            } else {
                $this->command->info("User id={$uid} exists");
            }

            // find professor record for this user
            $prof = DB::table('professors')->where('user_id', $uid)->first();

            if (! $prof) {
                $this->command->warn("No professor found for user_id={$uid} — skipping.");
                continue;
            }

            // skip if mapping already exists
            $exists = DB::table('alumni_professor_selections')
                ->where('user_id', $uid)
                ->where('professor_id', $prof->id)
                ->exists();

            if ($exists) {
                $this->command->info("Mapping exists: user_id={$uid} -> professor_id={$prof->id} (skipping)");
                continue;
            }

            // demo values for informational fields
            $joinedYear = rand(2005, 2015);
            $batchYear = $joinedYear + 4;
            $passedOutYear = $batchYear;
            $branch = $branches[array_rand($branches)];

            DB::table('alumni_professor_selections')->insert([
                'user_id'          => $uid,
                'professor_id'     => $prof->id,
                'batch_year'       => $batchYear,
                'branch'           => $branch,
                'joined_year'      => $joinedYear,
                'passed_out_year'  => $passedOutYear,
                'context'          => 'profile_selection',
                'selected_at'      => $now,
                'extra'            => json_encode([
                    'seed' => true,
                    'assigned_via_user_id' => $uid,
                    'professor_full_name' => $prof->full_name ?? null,
                ]),
                'created_at' => $now, // if your table has timestamps
                'updated_at' => $now,
            ]);

            $this->command->info("Inserted mapping: user_id={$uid} -> professor_id={$prof->id}");
            $inserted++;
        }

        $this->command->info("AlumniProfessorSelectionSeeder finished. Inserted {$inserted} row(s).");
    }
}
