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

        $userId2 = DB::table('users')->insertGetId([
            'firstname' => 'Sebastian',
            'username' => 'sebastian709',
            'lastname' => 'Jabson',
            'middlename' => 'Cestona',
            'contactno' => '099770224547',
            'referral_source_id' => 0,
            'referral_id' => 0,
            'is_admin' => 0,
            'email' => 'sebastianjabson07@gmail.com',
            'password' => '$2y$12$TKmg0ooXNf0Z0z/BMPyS2u1KxHk2whC7ohFAzM3jdS3mUA5SBRZ1O',
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
            ],
            [
                'user_id' => $userId2,
                'house_no' => '30',
                'street' => 'senorita',
                'barangay' => 'saguin',
                'city' => 'san fernando',
                'province' => 'pampanga',
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
            ],
            [
                'user_id' => $userId2,
                'occupation' => 'dev',
                'income' => 100.00,
                'employment_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
