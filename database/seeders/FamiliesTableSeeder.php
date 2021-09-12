<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\data\FamiliesTableData;
use Faker\Generator as Faker;

class FamiliesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        // \App\Models\Family::factory(10)->create();
        FamiliesTableData::run($faker);
    }
}
