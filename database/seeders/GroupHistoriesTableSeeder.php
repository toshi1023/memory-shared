<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\data\GroupHistoriesTableData;
use Faker\Generator as Faker;

class GroupHistoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        // \App\Models\GroupHistory::factory(10)->create();
        GroupHistoriesTableData::run($faker);
    }
}
