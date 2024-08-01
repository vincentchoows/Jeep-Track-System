<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermitApplicationLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Generate 10 entries for the permit_application_log table
        for ($i = 1; $i <= 10; $i++) {
            DB::table('permit_application_log')->insert([
                'user_id' => 1, // Set admin user ID to 1 for all rows
                'description' => 'Description for log entry ' . $i, // Unique description for each row
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
