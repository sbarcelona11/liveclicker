<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Jhon Doe',
                'email' => 'test@test.com',
                'username' => 'test',
                'password' => Hash::make('password')
            ],
            [
                'name' => 'Liveclicker',
                'email' => 'dev@liveclicker.com​',
                'username' => 'liveclicker',
                'password' => Hash::make('liveclicker')
            ]
        ];

        DB::table('user')->insert($users);
    }
}
