<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UsersTableSeeder extends Seeder {

    public function run() {
        $user = User::create([
            'name' => env('FIRST_USER_NAME','Admin'),
            'email' => env('FIRST_USER_EMAIL','admin@localhost.domain'),
            'password' => bcrypt(env('FIRST_USER_PW','p@55w0rd'))
        ]);

        $roles = Role::get();

        $user->roles()->attach($roles);
    }
}


