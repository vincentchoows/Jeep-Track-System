<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Faker\Factory as Faker;

class TransactionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 5; $i++) {
            Transaction::create([
                'ipay_id' => $faker->unique()->randomNumber(),
                'auth_code' => $faker->unique()->randomNumber(),
                'error_description' => $faker->sentence(),
                'signature' => $faker->word(),
                'ref_no' => $faker->unique()->randomNumber(),
                'currency' => $faker->currencyCode(),
                'product_description' => $faker->sentence(),
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'contact_no' => $faker->phoneNumber(),
                'total' => $faker->randomFloat(2, 10, 1000),
                'status' => $faker->randomElement([0, 1]),
                'remark' => $faker->sentence(),
                'ccname' => $faker->name(),
                's_bankname' => $faker->word(),
                's_country' => $faker->country(),
                'bank_mid' => $faker->word(),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }
    }
}
