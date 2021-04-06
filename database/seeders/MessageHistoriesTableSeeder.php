<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MessageHistoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\MessageHistory::factory(10)->create();
    }
}
