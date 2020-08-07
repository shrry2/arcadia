<?php

use \App\User;
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
        $user = User::create([
            'name' => '初期システム管理者アカウント',
            'username' => 'admin',
            'email' => NULL,
            'password' => Hash::make('password'),
        ]);
        $user->assignRole('システム管理者');
    }
}
