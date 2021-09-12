<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\data\GroupsTableData;
use Faker\Generator as Faker;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        // \App\Models\Group::factory(10)->create();
        GroupsTableData::run($faker);
    }
}
