<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PushHistoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\PushHistory::factory(10)->create();
    }
}
