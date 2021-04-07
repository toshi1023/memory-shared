<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        \App\Models\User::create([
            'name'              => 'root',
            'email'             => 'root@xxx.co.jp',
            'email_verified_at' => now(),
            'password'          => Hash::make('root1234'),
            'status'            => config('const.User.ADMIN'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 1,
        ]);

        \App\Models\User::factory(10)->create();
    }
}
