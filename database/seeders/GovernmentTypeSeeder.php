<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GovernmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $idTypes = [
            "Driver’s License",
            "PhilSys",
            "Philippine Passport",
            "Unified Multi-Purpose ID (UMID)",
            "Postal ID",
            "SSS (Social Security System) ID",
            "Work ID",
        ];

        foreach ($idTypes as $type) {
            DB::table('government_type')->insert([
                'government_id_type' => $type,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
