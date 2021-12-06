<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Album;

class AlbumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Album::create([
            'name'              => 'CafeOsakaClub Album',
            'group_id'          => 1,
            'host_user_id'      => 2,
            'update_user_id'    => 2,
        ]);
        \App\Models\Album::factory(20)->create();
    }
}
