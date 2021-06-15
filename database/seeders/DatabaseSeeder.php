<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(GroupsTableSeeder::class);
        $this->call(AlbumsTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        $this->call(GroupHistoriesTableSeeder::class);
        $this->call(UserImagesTableSeeder::class);
        $this->call(UserVideosTableSeeder::class);
        $this->call(FamiliesTableSeeder::class);
        $this->call(MessageRelationsTableSeeder::class);
        $this->call(MessageHistoriesTableSeeder::class);
        $this->call(PushHistoriesTableSeeder::class);
    }
}
