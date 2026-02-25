<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('roles')->insertOrIgnore([
            [
                'id' => 1,
                'name' => 'super_admin',
                'description' => 'Full system access',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'alumni',
                'description' => 'Registered alumni user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'moderator',
                'description' => 'Content moderator / reviewer',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'staff',
                'description' => 'College staff member',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
