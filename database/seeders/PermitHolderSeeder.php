<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PermitHolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        $permitHolders = [];
        
        for ($i = 1; $i <= 15; $i++) {
            $permitHolders[] = [
                'customer_id' => 1, // Assuming you want to assign all to the same customer
                'name' => $faker->name,
                'identification_no' => $faker->numerify('############'),
                'contact_no' => $faker->phoneNumber,
                'address' => $faker->address,
                'status' => 1, // Assuming all permit holders are enabled
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('permit_holder')->insert($permitHolders);
    }
}
