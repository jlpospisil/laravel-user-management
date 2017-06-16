<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UsersTableSeeder extends Seeder {

    public function run() {
        $first_user = (object) [
            'name' => env('FIRST_USER_NAME','Admin'),
            'email' => env('FIRST_USER_EMAIL','admin@localhost.domain'),
            'password' => env('FIRST_USER_PW','p@55w0rd')
        ];

        $user = User::create([
            'name' => $first_user->name ? $first_user->name : 'Admin',
            'email' => $first_user->email ? $first_user->email : 'admin@localhost.domain',
            'password' => bcrypt($first_user->password ? $first_user->password : 'p@55w0rd')
        ]);

        $roles = Role::get();

        $user->roles()->attach($roles);
    }
}


