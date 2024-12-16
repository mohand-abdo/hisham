<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'f_name' => 'super',
            'l_name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('11111111'),
            'status' => 1,
        ]);
    }
}
