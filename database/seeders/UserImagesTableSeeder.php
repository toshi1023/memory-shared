<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserImage;

class UserImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserImage::create([
            'image_file'        => 'furano.jpg',
            'user_id'           => 2,
            'album_id'          => 1,
        ]);
        UserImage::create([
            'image_file'        => 'garo_green.jpg',
            'user_id'           => 2,
            'album_id'          => 1,
        ]);
        UserImage::create([
            'image_file'        => 'sappolo.jpg',
            'user_id'           => 2,
            'album_id'          => 1,
        ]);
        UserImage::create([
            'image_file'        => 'クアラルンプール.jpg',
            'user_id'           => 2,
            'album_id'          => 1,
        ]);
        \App\Models\UserImage::factory(20)->create();
    }
}
