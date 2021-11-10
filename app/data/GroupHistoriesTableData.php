<?php

namespace App\data;

use App\Models\GroupHistory;
use Faker\Generator as Faker;
use Carbon\Carbon;

class GroupHistoriesTableData
{
    public static function run(Faker $faker)
    {
        $dt = new Carbon('now');

        GroupHistory::create([
            'user_id'           => 2,
            'group_id'          => 1,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 2,
            'created_at'        => $dt->subDay(90),
            'updated_at'        => $dt->subDay(90)
        ]);
        GroupHistory::create([
            'user_id'           => 1,
            'group_id'          => 1,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 4,
            'group_id'          => 2,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 4,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        GroupHistory::create([
            'user_id'           => 6,
            'group_id'          => 2,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 4,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 41,
            'group_id'          => 3,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 41,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        GroupHistory::create([
            'user_id'           => 11,
            'group_id'          => 3,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 11,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 7,
            'group_id'          => 1,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 2,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        GroupHistory::create([
            'user_id'           => 22,
            'group_id'          => 1,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 31,
            'group_id'          => 1,
            'status'            => config('const.GroupHistory.APPLY'),
            'update_user_id'    => 31,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 4,
            'group_id'          => 1,
            'status'            => config('const.GroupHistory.APPLY'),
            'update_user_id'    => 4,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 9,
            'group_id'          => 1,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 9,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 13,
            'group_id'          => 1,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 13,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 1,
            'group_id'          => 5,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 1,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        GroupHistory::create([
            'user_id'           => 11,
            'group_id'          => 5,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 16,
            'group_id'          => 5,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 18,
            'group_id'          => 5,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 1,
            'group_id'          => 6,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 1,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        GroupHistory::create([
            'user_id'           => 3,
            'group_id'          => 6,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 7,
            'group_id'          => 6,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 24,
            'group_id'          => 6,
            'status'            => config('const.GroupHistory.APPLY'),
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 28,
            'group_id'          => 6,
            'status'            => config('const.GroupHistory.APPLY'),
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 40,
            'group_id'          => 6,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 3,
            'group_id'          => 7,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 3,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        GroupHistory::create([
            'user_id'           => 2,
            'group_id'          => 7,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 3,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 2,
            'group_id'          => 8,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 2,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        GroupHistory::create([
            'user_id'           => 1,
            'group_id'          => 8,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 21,
            'group_id'          => 8,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 33,
            'group_id'          => 8,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 1,
            'group_id'          => 9,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 1,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        GroupHistory::create([
            'user_id'           => 25,
            'group_id'          => 9,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 2,
            'group_id'          => 9,
            'status'            => config('const.GroupHistory.APPLY'),
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 4,
            'group_id'          => 10,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 4,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        GroupHistory::create([
            'user_id'           => 1,
            'group_id'          => 10,
            'status'            => config('const.GroupHistory.APPLY'),
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 10,
            'group_id'          => 10,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 2,
            'group_id'          => 10,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 5,
            'group_id'          => 11,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 5,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 7,
            'group_id'          => 11,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 5,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 29,
            'group_id'          => 11,
            'status'            => config('const.GroupHistory.APPLY'),
            'update_user_id'    => 29,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 42,
            'group_id'          => 12,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 42,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 1,
            'group_id'          => 12,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 42,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 4,
            'group_id'          => 12,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 42,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 18,
            'group_id'          => 12,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 42,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 30,
            'group_id'          => 12,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 42,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 15,
            'group_id'          => 12,
            'status'            => config('const.GroupHistory.APPLY'),
            'update_user_id'    => 15,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 21,
            'group_id'          => 12,
            'status'            => config('const.GroupHistory.APPLY'),
            'update_user_id'    => 21,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 22,
            'group_id'          => 12,
            'status'            => config('const.GroupHistory.APPLY'),
            'update_user_id'    => 22,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 34,
            'group_id'          => 13,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 34,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 1,
            'group_id'          => 13,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 34,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 2,
            'group_id'          => 13,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 34,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 12,
            'group_id'          => 13,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 34,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 16,
            'group_id'          => 13,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 34,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 48,
            'group_id'          => 13,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 34,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 8,
            'group_id'          => 13,
            'status'            => config('const.GroupHistory.APPLY'),
            'update_user_id'    => 8,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 43,
            'group_id'          => 13,
            'status'            => config('const.GroupHistory.APPLY'),
            'update_user_id'    => 43,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 28,
            'group_id'          => 14,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 28,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 2,
            'group_id'          => 14,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 28,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 3,
            'group_id'          => 14,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 28,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 29,
            'group_id'          => 14,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 28,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 41,
            'group_id'          => 14,
            'status'            => config('const.GroupHistory.APPROVAL'),
            'update_user_id'    => 28,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        GroupHistory::create([
            'user_id'           => 26,
            'group_id'          => 14,
            'status'            => config('const.GroupHistory.APPLY'),
            'update_user_id'    => 26,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
    }
}