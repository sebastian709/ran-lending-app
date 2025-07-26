<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferralSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('referral_source')->insert([
            ['name' => 'Social Media', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Referral', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
