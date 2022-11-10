<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'role' => 'superadmin',
            'active' => 1,
            'access' => '{"*" : "*"}'
        ]);
        
        DB::table('roles')->insert([
            'role' => 'admin',
            'active' => 1,
            'access' => '{"*" : "*"}'
        ]);
        
        DB::table('roles')->insert([
            'role' => 'user',
            'active' => 1,
            'access' => '{"*" : "*"}'
        ]);
    }
}
