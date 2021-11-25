<?php

namespace App\data;

use App\Models\Family;
use Faker\Generator as Faker;
use Carbon\Carbon;

class FamiliesTableData
{
    public static function run(Faker $faker)
    {
        $dt = new Carbon('now');

        Family::create([
            'user_id1'          => 1,
            'user_id2'          => 2,
            'created_at'        => $dt->subDay(80),
            'updated_at'        => $dt->subDay(80)
        ]);
        Family::create([
            'user_id1'          => 1,
            'user_id2'          => 3,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 1,
            'user_id2'          => 11,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 1,
            'user_id2'          => 34,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 1,
            'user_id2'          => 42,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 1,
            'user_id2'          => 50,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 2,
            'user_id2'          => 4,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 2,
            'user_id2'          => 21,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 2,
            'user_id2'          => 28,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 2,
            'user_id2'          => 33,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 2,
            'user_id2'          => 34,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 2,
            'user_id2'          => 50,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 3,
            'user_id2'          => 2,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 3,
            'user_id2'          => 28,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 4,
            'user_id2'          => 1,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 4,
            'user_id2'          => 6,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 4,
            'user_id2'          => 12,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 5,
            'user_id2'          => 1,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 5,
            'user_id2'          => 2,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 5,
            'user_id2'          => 46,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 7,
            'user_id2'          => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 7,
            'user_id2'          => 2,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 7,
            'user_id2'          => 3,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 7,
            'user_id2'          => 5,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 8,
            'user_id2'          => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 8,
            'user_id2'          => 2,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 8,
            'user_id2'          => 50,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 9,
            'user_id2'          => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 9,
            'user_id2'          => 2,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 9,
            'user_id2'          => 7,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 9,
            'user_id2'          => 22,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 10,
            'user_id2'          => 4,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 10,
            'user_id2'          => 2,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 12,
            'user_id2'          => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 12,
            'user_id2'          => 2,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 12,
            'user_id2'          => 34,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 13,
            'user_id2'          => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 13,
            'user_id2'          => 2,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 13,
            'user_id2'          => 7,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 13,
            'user_id2'          => 22,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 13,
            'user_id2'          => 9,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 14,
            'user_id2'          => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 14,
            'user_id2'          => 2,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 14,
            'user_id2'          => 8,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 14,
            'user_id2'          => 36,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 14,
            'user_id2'          => 37,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 14,
            'user_id2'          => 50,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 16,
            'user_id2'          => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 16,
            'user_id2'          => 2,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 16,
            'user_id2'          => 11,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 16,
            'user_id2'          => 12,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 16,
            'user_id2'          => 34,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 17,
            'user_id2'          => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 17,
            'user_id2'          => 2,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 17,
            'user_id2'          => 8,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 17,
            'user_id2'          => 14,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 17,
            'user_id2'          => 36,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 17,
            'user_id2'          => 37,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 17,
            'user_id2'          => 50,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 18,
            'user_id2'          => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 18,
            'user_id2'          => 4,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 18,
            'user_id2'          => 11,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 18,
            'user_id2'          => 16,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 18,
            'user_id2'          => 42,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 22,
            'user_id2'          => 1,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 22,
            'user_id2'          => 2,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 22,
            'user_id2'          => 7,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 23,
            'user_id2'          => 1,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 23,
            'user_id2'          => 2,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 23,
            'user_id2'          => 5,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 23,
            'user_id2'          => 41,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 25,
            'user_id2'          => 1,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 29,
            'user_id2'          => 2,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 29,
            'user_id2'          => 3,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 29,
            'user_id2'          => 28,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 30,
            'user_id2'          => 1,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 30,
            'user_id2'          => 4,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 30,
            'user_id2'          => 18,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 30,
            'user_id2'          => 42,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Family::create([
            'user_id1'          => 36,
            'user_id2'          => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 36,
            'user_id2'          => 2,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 36,
            'user_id2'          => 8,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 36,
            'user_id2'          => 37,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 36,
            'user_id2'          => 50,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 37,
            'user_id2'          => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 37,
            'user_id2'          => 2,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 37,
            'user_id2'          => 8,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 37,
            'user_id2'          => 50,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 40,
            'user_id2'          => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 40,
            'user_id2'          => 3,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 40,
            'user_id2'          => 7,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 41,
            'user_id2'          => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 41,
            'user_id2'          => 2,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 41,
            'user_id2'          => 3,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 41,
            'user_id2'          => 28,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 41,
            'user_id2'          => 29,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 47,
            'user_id2'          => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 47,
            'user_id2'          => 2,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 47,
            'user_id2'          => 8,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 47,
            'user_id2'          => 14,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 47,
            'user_id2'          => 17,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 47,
            'user_id2'          => 36,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 47,
            'user_id2'          => 37,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 47,
            'user_id2'          => 50,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 48,
            'user_id2'          => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 48,
            'user_id2'          => 2,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 48,
            'user_id2'          => 12,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 48,
            'user_id2'          => 16,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 48,
            'user_id2'          => 34,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 54,
            'user_id2'          => 1,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 54,
            'user_id2'          => 2,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 54,
            'user_id2'          => 5,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 54,
            'user_id2'          => 23,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
        Family::create([
            'user_id1'          => 54,
            'user_id2'          => 41,
            'created_at'        => $dt->addSecond(),
            'updated_at'        => $dt->addSecond()
        ]);
    }
}