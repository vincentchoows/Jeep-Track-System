<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PermitApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            DB::table('permit_application')->insert([
                'customer_id' => $faker->numberBetween(1, 10), // Assuming you have 10 customers
                'permit_charge_id' => null,
                'status' => 0, 
                'holder_id' => rand(1,5),
                'company_name' => $faker->company(),
                'company_address' => $faker->address(),
                'purpose' => $faker->sentence(),
                'applicant_category_id' => rand(1, 3),
                'vehicle_id' => rand(1, 5),
                'surat_permohonan' => '[]',
                'surat_indemnity' => '[]',
                'salinan_kad_pengenalan' => '[]',
                'salinan_lesen_memandu' => '[]',
                'salinan_geran_kenderaan' => '[]',
                'salinan_insurans_kenderaan' => '[]',
                'salinan_road_tax' => '[]',
                'gambar_kenderaan' => '[]',
                'surat_sokongan' => '[]',
                'phc_check' => 0,
                'phc_check_date' => $faker->dateTime(),
                'phc_check_id' => 1,
                'phc_approve' => 0,
                'phc_approve_date' => $faker->dateTime(),
                'phc_approve_id' => 1,
                'phc_second_approve' => 0,
                'phc_second_approve_date' => $faker->dateTime(),
                'phc_second_approve_id' => 1,
                'jkr_check' => 0,
                'jkr_check_date' => $faker->dateTime(),
                'jkr_check_id' => 1,
                'jkr_approve' => 0,
                'jkr_approve_date' => $faker->dateTime(),
                'jkr_approve_id' => 1,
                'finance_check' => 0,
                'finance_check_date' => $faker->dateTime(),
                'finance_check_id' => 1,
                'finance_approve' => 0,
                'finance_approve_date' => $faker->dateTime(),
                'finance_approve_id' => 1,
                'transaction_id' => $faker->randomNumber(),
                'transaction_status' => 0,
                'permit_renewal_id' => null,
                'start_date' => null, 
                'end_date' => null, 

                'created_at' => now(),
                'updated_at' => now(),

            ]);
        }
    }
}
