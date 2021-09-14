<?php

namespace App\data;

use App\Models\NreadManagement;
use Faker\Generator as Faker;
use Carbon\Carbon;

class NreadManagementsTableData
{
    public static function run(Faker $faker)
    {
        $dt = new Carbon('now');

        NreadManagement::create([
            'news_user_id'      => 1,
            'news_id'           => 4,
            'user_id'           => 1,
            'created_at'        => $dt->subDay(2),
            'updated_at'        => $dt->subDay(2)
        ]);
        NreadManagement::create([
            'news_user_id'      => 1,
            'news_id'           => 5,
            'user_id'           => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        NreadManagement::create([
            'news_user_id'      => 2,
            'news_id'           => 4,
            'user_id'           => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        NreadManagement::create([
            'news_user_id'      => 2,
            'news_id'           => 5,
            'user_id'           => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        NreadManagement::create([
            'news_user_id'      => 0,
            'news_id'           => 2,
            'user_id'           => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        NreadManagement::create([
            'news_user_id'      => 0,
            'news_id'           => 2,
            'user_id'           => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        NreadManagement::create([
            'news_user_id'      => 0,
            'news_id'           => 3,
            'user_id'           => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        NreadManagement::create([
            'news_user_id'      => 0,
            'news_id'           => 3,
            'user_id'           => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
    }
}