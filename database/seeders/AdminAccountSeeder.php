<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->insertGetId([
            'firstname' => 'Ran',
            'username' => 'ran.serenity25',
            'lastname' => 'Serenity',
            'middlename' => 'Admin',
            'contactno' => '0639691898835',
            'referral_source_id' => 0,
            'referral_id' => 0,
            'is_admin' => 1,
            'email' => 'ranserenityhub@gmail.com',
            'password' => bcrypt('Ranserenity@25'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('user_details')->insert([
            [
                'user_id' => $userId,
                'house_no' => 'please update',
                'street' => 'please update',
                'barangay' => 'please update',
                'city' => 'please update',
                'province' => 'please update',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
        DB::table('user_incomes')->insert([
            [
                'user_id' => $userId,
                'occupation' => 'update if needed',
                'income' => 100,
                'employment_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
