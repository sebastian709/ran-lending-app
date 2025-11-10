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
            ['loan_status' => 'Pending for Approval ', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['loan_status' => 'For Interview', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['loan_status' => 'For Revision', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['loan_status' => 'Waiting for disbursement', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['loan_status' => 'Transferred and Processed', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['loan_status' => 'Rejected', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['loan_status' => 'Closed', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['loan_status' => 'Scheduled', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['loan_status' => 'Cancelled', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
