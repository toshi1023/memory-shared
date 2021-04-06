<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GroupHistoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\GroupHistory::factory(10)->create();
    }
}
