<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\data\MessageHistoriesTableData;
use Faker\Generator as Faker;

class MessageHistoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        // \App\Models\MessageHistory::factory(50)->create();
        MessageHistoriesTableData::run($faker);
    }
}
