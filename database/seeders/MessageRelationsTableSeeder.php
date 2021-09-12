<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\data\MessageRelationsTableData;
use Faker\Generator as Faker;

class MessageRelationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        // \App\Models\MessageRelation::factory(10)->create();
        MessageRelationsTableData::run($faker);
    }
}
