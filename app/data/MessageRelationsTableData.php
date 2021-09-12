<?php

namespace App\data;

use App\Models\MessageRelation;
use Faker\Generator as Faker;
use Carbon\Carbon;

class MessageRelationsTableData
{
    public static function run(Faker $faker)
    {
        $dt = new Carbon('now');

        MessageRelation::create([
            'user_id1'          => 2,
            'user_id2'          => 1,
            'created_at'        => $dt->subDay(30),
            'updated_at'        => $dt->subDay(30)
        ]);
        MessageRelation::create([
            'user_id1'          => 1,
            'user_id2'          => 11,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageRelation::create([
            'user_id1'          => 18,
            'user_id2'          => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageRelation::create([
            'user_id1'          => 16,
            'user_id2'          => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageRelation::create([
            'user_id1'          => 4,
            'user_id2'          => 6,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageRelation::create([
            'user_id1'          => 2,
            'user_id2'          => 33,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageRelation::create([
            'user_id1'          => 2,
            'user_id2'          => 10,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageRelation::create([
            'user_id1'          => 1,
            'user_id2'          => 9,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
    }
}