<?php

namespace App\data;

use App\Models\MessageHistory;
use Faker\Generator as Faker;
use Carbon\Carbon;

class MessageHistoriesTableData
{
    public static function run(Faker $faker)
    {
        $dt = new Carbon('now');

        $content = [
            'この前の旅行楽しかったね！',
            '元気してるかー？',
            'この前の飲み会の写真アップロードしといて～',
            'めちゃくちゃ楽しかったね！',
            '京都に来たんだ！一緒にご飯食べたかったよ！',
            'この辺でおすすめの観光スポットってある？',
            '今度の休み福岡いこうよ！',
            'だいぶ人数増えたね！',
            '楽しそうな動画だったよ！',
            '久しぶり！元気してるよ！',
        ];

        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 2,
            'user_id'           => 1,
            'update_user_id'    => 2,
            'created_at'        => $dt->subDay(10),
            'updated_at'        => $dt->subDay(10)
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 4,
            'user_id'           => 6,
            'update_user_id'    => 4,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 1,
            'user_id'           => 2,
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 4,
            'user_id'           => 6,
            'update_user_id'    => 4,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 1,
            'user_id'           => 11,
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 11,
            'user_id'           => 1,
            'update_user_id'    => 11,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 11,
            'user_id'           => 1,
            'update_user_id'    => 11,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 6,
            'user_id'           => 4,
            'update_user_id'    => 6,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 1,
            'user_id'           => 2,
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 2,
            'user_id'           => 33,
            'update_user_id'    => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 2,
            'user_id'           => 33,
            'update_user_id'    => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 33,
            'user_id'           => 2,
            'update_user_id'    => 33,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 2,
            'user_id'           => 33,
            'update_user_id'    => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 33,
            'user_id'           => 2,
            'update_user_id'    => 33,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 1,
            'user_id'           => 11,
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 1,
            'user_id'           => 11,
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 11,
            'user_id'           => 1,
            'update_user_id'    => 11,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 18,
            'user_id'           => 1,
            'update_user_id'    => 18,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 18,
            'user_id'           => 1,
            'update_user_id'    => 18,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 1,
            'user_id'           => 18,
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 18,
            'user_id'           => 1,
            'update_user_id'    => 18,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 1,
            'user_id'           => 18,
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 18,
            'user_id'           => 1,
            'update_user_id'    => 18,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 18,
            'user_id'           => 1,
            'update_user_id'    => 18,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 16,
            'user_id'           => 1,
            'update_user_id'    => 16,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 1,
            'user_id'           => 16,
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 2,
            'user_id'           => 10,
            'update_user_id'    => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 10,
            'user_id'           => 2,
            'update_user_id'    => 10,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 1,
            'user_id'           => 9,
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 1,
            'user_id'           => 9,
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        MessageHistory::create([
            'content'           => $content[$faker->numberBetween(0, 9)],
            'own_id'            => 9,
            'user_id'           => 1,
            'update_user_id'    => 9,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
    }
}