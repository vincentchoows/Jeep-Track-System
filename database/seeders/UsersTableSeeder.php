<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        for ($i = 0; $i < 10; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name, // Using Faker to generate a name
                'email' => $faker->unique()->safeEmail, // Using Faker to generate a unique email
                'phone_no' => $faker->phoneNumber, 
                'email_verified_at' => now(),
                'password' => '$2y$10$hqs3E8C3PRwfd4wVMh6bTuN0rgcY27qBfQeGJBG1c0NNAxfqukSsy', 
                //$2y$10$hqs3E8C3PRwfd4wVMh6bTuN0rgcY27qBfQeGJBG1c0NNAxfqukSsy = demo12345
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
