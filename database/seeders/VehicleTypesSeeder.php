<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VehicleType;

class VehicleTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Car', 'description' => 'A motor vehicle with four wheels; usually propelled by an internal combustion engine.'],
            ['name' => 'Lorry', 'description' => 'A large motor vehicle for transporting goods.'],
            ['name' => 'Motorcycle', 'description' => 'A two-wheeled vehicle that is powered by an engine and has no pedals.'],
            ['name' => 'Buggy', 'description' => 'A light, horse-drawn carriage with four wheels.'],
        ];

        foreach ($data as $item) {
            VehicleType::create($item);
        }
    }
}
