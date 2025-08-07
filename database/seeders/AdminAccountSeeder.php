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
        $superAdmin = DB::table('users')->insertGetId([
            'firstname' => 'Ran',
            'username' => 'ran.serenity25',
            'lastname' => 'Serenity',
            'middlename' => 'Admin',
            'contactno' => '0639691898835',
            'referral_source_id' => 0,
            'referral_id' => 0,
            'is_admin' => 1,
            'is_super_admin' => 1,
            'email' => 'ranserenityhub@gmail.com',
            'password' => bcrypt('asdf'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $almira = DB::table('users')->insertGetId([
            'firstname' => 'Admin',
            'username' => 'admin.almira',
            'lastname' => 'almira',
            'middlename' => 'Admin',
            'contactno' => '0639691898835',
            'referral_source_id' => 0,
            'referral_id' => 0,
            'is_admin' => 1,
            'is_super_admin' => 0,
            'email' => 'almiramint0830@gmail.com',
            'password' => bcrypt('asdf'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $nc = DB::table('users')->insertGetId([
            'firstname' => 'Admin',
            'username' => 'admin.nc',
            'lastname' => 'nc',
            'middlename' => 'Admin',
            'contactno' => '0639691898835',
            'referral_source_id' => 0,
            'referral_id' => 0,
            'is_admin' => 1,
            'is_super_admin' => 0,
            'email' => 'nc@gmail.com',
            'password' => bcrypt('asdf'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $riki = DB::table('users')->insertGetId([
            'firstname' => 'Admin',
            'username' => 'admin.riki',
            'lastname' => 'riki',
            'middlename' => 'Admin',
            'contactno' => '0639691898835',
            'referral_source_id' => 0,
            'referral_id' => 0,
            'is_admin' => 1,
            'is_super_admin' => 0,
            'email' => 'rikivillaranda@gmail.com',
            'password' => bcrypt('asdf'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $testborrower = DB::table('users')->insertGetId([
            'firstname' => 'Sebastian',
            'username' => 'sebastian709',
            'lastname' => 'Jabson',
            'middlename' => 'Cestona',
            'contactno' => '099770224547',
            'referral_source_id' => 0,
            'referral_id' => 0,
            'is_admin' => 0,
            'email' => 'sebastianjabson07@gmail.com',
            'password' => bcrypt('asdf'),
            'created_at' => now(),
            'updated_at' => now()
        ]);


        DB::table('admin_loan_request_access')->insert([
            [
                'user_id' => $superAdmin, // super admin
                'pending' => '1',
                'for_interview' => '1',
                'for_revision' => '1',
                'waiting' => '1',
                'created_at' => now()
            ],
            [
                'user_id' => $almira,
                'pending' => '1',
                'for_interview' => '1',
                'for_revision' => '1',
                'waiting' => '0',
                'created_at' => now()
            ],
            [
                'user_id' => $nc,
                'pending' => '1',
                'for_interview' => '0',
                'for_revision' => '0',
                'waiting' => '1', // waiting for disbursement
                'created_at' => now()
            ],
            [
                'user_id' => $riki,
                'pending' => '1',
                'for_interview' => '0',
                'for_revision' => '0',
                'waiting' => '0',
                'created_at' => now()
            ]
        ]);

        DB::table('user_details')->insert([
            [
                'user_id' => $superAdmin,
                'house_no' => 'please update',
                'street' => 'please update',
                'barangay' => 'please update',
                'city' => 'please update',
                'province' => 'please update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => $almira,
                'house_no' => 'please update',
                'street' => 'please update',
                'barangay' => 'please update',
                'city' => 'please update',
                'province' => 'please update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => $nc,
                'house_no' => 'please update',
                'street' => 'please update',
                'barangay' => 'please update',
                'city' => 'please update',
                'province' => 'please update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => $riki,
                'house_no' => 'please update',
                'street' => 'please update',
                'barangay' => 'please update',
                'city' => 'please update',
                'province' => 'please update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => $testborrower,
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
                'user_id' => $superAdmin,
                'occupation' => 'update if needed',
                'income' => 100,
                'employment_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => $almira,
                'occupation' => 'update if needed',
                'income' => 100,
                'employment_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => $nc,
                'occupation' => 'update if needed',
                'income' => 100,
                'employment_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => $riki,
                'occupation' => 'update if needed',
                'income' => 100,
                'employment_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => $testborrower,
                'occupation' => 'dev',
                'income' => 100.00,
                'employment_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
