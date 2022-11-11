<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('users')->insert([
            'email' => Str::random(10).'@gmail.com',
            'username' => 'superadmin',
            'password' => Hash::make('password'),
            'firstname' => Str::random(10),
            'lastname' => Str::random(10),
            'location' => 'Al Wathba',
            'emirates_id' => 'XXX12340',
            'dob' => date('Y-m-d H:i:s'),
            'status' => 'A',
            'role' => 1
        ]);
    }
}
