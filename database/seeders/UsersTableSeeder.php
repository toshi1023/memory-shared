<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;
use Carbon\Carbon;
use App\data\UsersTableData;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $dt = new Carbon('now');

        // \App\Models\User::create([
        //     'name'              => 'root',
        //     'email'             => 'root@xxx.co.jp',
        //     'email_verified_at' => $dt->subMonth(5),
        //     'password'          => Hash::make('root1234'),
        //     'gender'            => config('const.User.MAN'),
        //     'status'            => config('const.User.ADMIN'),
        //     'user_agent'        => $faker->userAgent,
        //     'remember_token'    => Str::random(10),
        //     'update_user_id'    => 1,
        //     'created_at'        => $dt->subMonth(5),
        //     'updated_at'        => $dt->subMonth(5)
        // ]);

        // \App\Models\User::factory(19)->create();
        UsersTableData::run($faker);
    }
}
