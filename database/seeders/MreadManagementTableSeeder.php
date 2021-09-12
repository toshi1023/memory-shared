<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\data\MreadManagementsTableData;
use Faker\Generator as Faker;

class MreadManagementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        // \App\Models\MreadManagement::factory(10)->create();
        MreadManagementsTableData::run($faker);
    }
}
