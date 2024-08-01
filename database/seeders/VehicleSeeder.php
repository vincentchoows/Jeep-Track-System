<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        $vehicles = [];
        for ($i = 0; $i < 5; $i++) { // Generate 50 vehicles
            $vehicles[] = [
                'type' => $faker->numberBetween(1, 4),
                'reg_no' => strtoupper($faker->bothify('???####')), // Generates a string like 'PKH2090'
                'model' => $faker->randomElement(['Toyota Corolla', 'Honda Civic', 'Ford Mustang', 'Chevrolet Camaro']), // Feel free to add more models
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('vehicles')->insert($vehicles);
    }
}
