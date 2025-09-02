<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmploymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $idTypes = [
            "Employed",
            "Self Employed",
            "None",
            "Others"
        ];

        foreach ($idTypes as $type) {
            DB::table('employment_status')->insert([
                'employment_status' => $type,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
