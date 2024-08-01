<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\PermitApplication; // Adjust with your actual model

class SerialNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        PermitApplication::chunk(100, function ($models) use ($faker) {
            foreach ($models as $model) {
                // Generate serial number with format: 3 digits - 4 alphabets - 3 digits
                $serialNumber = sprintf(
                    '%03d-%s-%03d',
                    $faker->numberBetween(100, 999), // 3 digits
                    strtoupper($faker->lexify('???')), // 4 uppercase alphabets
                    $faker->numberBetween(100, 999) // 3 digits
                );

                $model->serial_no = $serialNumber;
                $model->save();
            }
        });
    }
}
