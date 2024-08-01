<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermitRenewalLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define an empty array to store the data for insertion
        $data = [];

        // Loop to create 11 records
        for ($i = 1; $i <= 11; $i++) {
            $data[] = [
                'admin_user_id' => 1,
                'description' => 'Description for log entry ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert the data into the permit_renewal_logs table
        DB::table('permit_renewal_logs')->insert($data);
    }
}
