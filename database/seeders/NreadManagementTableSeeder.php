<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\data\NreadManagementsTableData;
use Faker\Generator as Faker;

class NreadManagementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        // \App\Models\NreadManagement::factory(10)->create();
        NreadManagementsTableData::run($faker);
    }
}
