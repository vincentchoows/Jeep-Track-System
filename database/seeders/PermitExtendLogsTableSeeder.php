<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PermitExtendLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 5; $i++) {
            $adminUserId = $faker->numberBetween(1,5); 
            $description = $faker->sentence();
            $days = $faker->numberBetween(1, 10); 

            DB::table('permit_extend_logs')->insert([
                'admin_user' => $adminUserId,
                'permit_application_id' => $adminUserId,
                'description' => $description,
                'days' => $days,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
