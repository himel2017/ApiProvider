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
        $user = new User();
        $user->name = 'Admin';
        $user->username = 'admin';
        $user->email = 'admin@akij.net';
        $user->phone_no = '01711000000';
        $user->password = bcrypt('password');
        $user->save();

        $user = new User();
        $user->name = 'Md. Nazmus Shakib';
        $user->username = 'shakib';
        $user->email = 'shakib.corp@akij.net';
        $user->phone_no = '01747867585';
        $user->password = bcrypt('123456');
        $user->api_token = '$2y$10$ePZ1PkU7dyH1i.EEk7V.y.7Wpe5cghfhgfdfP7zBYaGkF1Jhr2bZHGoq';
        $user->save();
    }
}
