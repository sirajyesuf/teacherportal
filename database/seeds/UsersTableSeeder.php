<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Quinne',
            'email' => 'quinne@gmail.com',
            'role_type' => 1,
            'password' => bcrypt('12345678'), // Replace 'password' with the actual password you want to use
            'color' => '#2596be',
        ]);
    }
}
