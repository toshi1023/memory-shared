<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MreadManagementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\MreadManagement::factory(10)->create();
    }
}
