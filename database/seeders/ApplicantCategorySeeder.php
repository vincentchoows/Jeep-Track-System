<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicantCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Resident',
                'description' => 'Resident of Penang Hill',
                'approval_required' => 1, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vendor',
                'description' => 'Vendor of Penang Hill',
                'approval_required' => 1, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Agency',
                'description' => 'Agency',
                'approval_required' => 1, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Contractor',
                'description' => 'Description for Contractor',
                'approval_required' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Business Operator',
                'description' => 'Description for Business Operator',
                'approval_required' => 1, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        
        ];

        DB::table('applicant_categories')->insert($categories);
    }
}
