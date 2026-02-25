<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class StaffUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $users = [];

        for ($i = 1; $i <= 10; $i++) {
            $users[] = [
                'role_id'            => 4, // staff role
                'usn'                => 'STF' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name'               => "Staff User {$i}",
                'email'              => "staff{$i}@example.com",
                'password'           => Hash::make('password123'), // default password
                'phone'              => "98765432" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'is_active'          => true,
                'created_at'         => $now,
                'updated_at'         => $now,
                'remember_token'     => Str::random(60),
            ];
        }

        DB::table('users')->insert($users);
    }
}
