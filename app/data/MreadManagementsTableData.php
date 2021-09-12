<?php

namespace App\data;

use App\Models\MreadManagement;
use Faker\Generator as Faker;
use Carbon\Carbon;

class MreadManagementsTableData
{
    public static function run(Faker $faker)
    {
        $dt = new Carbon('now');

        MreadManagement::create([
            'message_id'        => 23,
            'own_id'            => 18,
            'user_id'           => 1,
            'created_at'        => $dt->subDay(5),
            'updated_at'        => $dt->subDay(5)
        ]);
        MreadManagement::create([
            'message_id'        => 24,
            'own_id'            => 18,
            'user_id'           => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        MreadManagement::create([
            'message_id'        => 4,
            'own_id'            => 4,
            'user_id'           => 6,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MreadManagement::create([
            'message_id'        => 31,
            'own_id'            => 9,
            'user_id'           => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        MreadManagement::create([
            'message_id'        => 28,
            'own_id'            => 10,
            'user_id'           => 2,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
    }
}