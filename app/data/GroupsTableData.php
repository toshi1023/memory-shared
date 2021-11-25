<?php

namespace App\data;

use App\Models\Group;
use Faker\Generator as Faker;
use Carbon\Carbon;

class GroupsTableData
{
    public static function run(Faker $faker)
    {
        $dt = new Carbon('now');

        $description = [
            '梅田カフェ巡り！ほっと一息つけるカフェタイムを楽しみにでかけるグループです！',
            '予想を超えます！100店近くのお店が軒を連ねる新梅田食道街でハシゴ酒しよう！',
            '大阪駅のランチ会★',
            'いつメン京都旅行★',
            'バスケサークルBASKE★の思い出共有グループです',
            '北海道海の幸満喫旅行サークル！毎年2回企画しています！！',
            '神戸駅の友達作りランチ会！',
            '伏見稲荷山めぐり★参加者いつでも大歓迎です！',
            'Complexファンクラブコミュニティです',
            'ヴィッセル神戸応援コミュニティです',
            'ライブファンクラブです',
            'カフェ大好きグループ★月に2回活動'
        ];

        Group::create([
            'name'              => 'CafeOsakaClub',
            'description'       => '梅田カフェ巡り！ほっと一息つけるカフェタイムを楽しみにでかけるグループです！',
            'private_flg'       => 0,
            'welcome_flg'       => 1,
            'host_user_id'      => 2,
            'update_user_id'    => 2,
            'created_at'        => $dt->subDay(90),
            'updated_at'        => $dt->subDay(90)
        ]);
        Group::create([
            'name'              => 'daiki&mayu',
            'description'       => null,
            'private_flg'       => 1,
            'host_user_id'      => 4,
            'update_user_id'    => 4,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Group::create([
            'name'              => '8月11日ドライブ',
            'description'       => null,
            'private_flg'       => 1,
            'host_user_id'      => 41,
            'update_user_id'    => 41,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Group::create([
            'name'              => 'riku',
            'description'       => null,
            'private_flg'       => 1,
            'host_user_id'      => 41,
            'update_user_id'    => 41,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Group::create([
            'name'              => 'いつメン★',
            'description'       => 'いつメン京都旅行★',
            'private_flg'       => 1,
            'host_user_id'      => 1,
            'update_user_id'    => 1,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Group::create([
            'name'              => 'OsakaLunch',
            'description'       => '大阪駅のランチ会★',
            'private_flg'       => 0,
            'welcome_flg'       => 1,
            'host_user_id'      => 1,
            'update_user_id'    => 1,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Group::create([
            'name'              => 'bask_circle',
            'description'       => 'バスケサークルBASKE★の思い出共有グループです',
            'private_flg'       => 0,
            'welcome_flg'       => 1,
            'host_user_id'      => 3,
            'update_user_id'    => 3,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Group::create([
            'name'              => 'umeda_walks',
            'description'       => '予想を超えます！100店近くのお店が軒を連ねる新梅田食道街でハシゴ酒しよう！',
            'private_flg'       => 0,
            'welcome_flg'       => 1,
            'host_user_id'      => 2,
            'update_user_id'    => 2,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Group::create([
            'name'              => 'complexCummunity',
            'description'       => 'Complexファンクラブコミュニティです',
            'private_flg'       => 0,
            'welcome_flg'       => 1,
            'host_user_id'      => 1,
            'update_user_id'    => 1,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Group::create([
            'name'              => 'vissel community',
            'description'       => 'ヴィッセル神戸応援コミュニティです',
            'private_flg'       => 0,
            'welcome_flg'       => 1,
            'host_user_id'      => 4,
            'update_user_id'    => 4,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Group::create([
            'name'              => 'sunshine community',
            'description'       => 'ピクニックを楽しむコミュニティです',
            'private_flg'       => 0,
            'welcome_flg'       => 1,
            'host_user_id'      => 5,
            'update_user_id'    => 5,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Group::create([
            'name'              => 'nfl watchers',
            'description'       => 'スポーツバーでNFLの観戦を企画しています！気軽に参加してください',
            'private_flg'       => 0,
            'welcome_flg'       => 1,
            'host_user_id'      => 42,
            'update_user_id'    => 42,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Group::create([
            'name'              => 'nba watchers',
            'description'       => 'スポーツバーでNBAの観戦を企画しています！気軽に参加してください',
            'private_flg'       => 0,
            'welcome_flg'       => 1,
            'host_user_id'      => 34,
            'update_user_id'    => 34,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Group::create([
            'name'              => 'mlb watchers',
            'description'       => 'スポーツバーでMLBの観戦を企画しています！気軽に参加してください',
            'private_flg'       => 0,
            'welcome_flg'       => 1,
            'host_user_id'      => 28,
            'update_user_id'    => 28,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Group::create([
            'name'              => 'premier league watchers',
            'description'       => 'スポーツバーでプレミアリーグの観戦を企画しています！気軽に参加してください',
            'private_flg'       => 0,
            'welcome_flg'       => 1,
            'host_user_id'      => 50,
            'update_user_id'    => 50,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        Group::create([
            'name'              => 'lega watchers',
            'description'       => 'スポーツバーでリーガの観戦を企画しています！気軽に参加してください',
            'private_flg'       => 0,
            'welcome_flg'       => 1,
            'host_user_id'      => 48,
            'update_user_id'    => 48,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
    }
}