<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\UserImage::factory(20)->create();
    }
}
