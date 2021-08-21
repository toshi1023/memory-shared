<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NreadManagementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\NreadManagement::factory(10)->create();
    }
}
