<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loan_status')->insert([
            ['loan_status' => 'Pending', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['loan_status' => 'Draft', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['loan_status' => 'On Review', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['loan_status' => 'Completed', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
