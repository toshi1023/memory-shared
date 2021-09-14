<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\data\NewsTableData;
use Faker\Generator as Faker;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        // \App\Models\News::factory(20)->create();
        NewsTableData::run($faker);
    }
}
