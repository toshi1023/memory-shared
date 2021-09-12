<?php

namespace App\data;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;
use Carbon\Carbon;

class UsersTableData
{
    public static function run(Faker $faker)
    {
        $dt = new Carbon('now');

        $hobby = [
            '映画鑑賞',
            'サッカー観戦',
            'FPSゲーム',
            'プログラミング',
            '釣り',
            'ドライブ',
            'ラーメン巡り',
            '海外旅行',
            'ペット飼育',
            '料理'
        ];

        $description = [
            'サッカーが大好きなので、サッカーの写真や動画を共有する仲間を募集中です！',
            '現在大学生です！水・土・日でカフェめぐりするので、参加したい方はメッセージください★',
            'サークルではフリーペーパーを作成しており、広告営業チームのリーダーをしています！興味ある方はメッセージください！',
            'いろいろなグループに参加して、アクティブな人生を歩みたい22歳★',
            '4月に就職したばかりのエンジニア|Javascript大好きマンです',
            'ジャニーズ大好きオタクです★年間20ライブ参加してます！',
            'ノマドワーカーで日本横断中|仕事欲しい方は気軽にメッセージよろ',
            'NFL大好きマン|スポーツバーで定期的に集会実施中|参加希望者は気軽にメッセージよろ',
            '日本酒に最近はまっている26歳OL★大阪でハシゴ満喫ツアー開催中',
            '地方雑誌でモデル活動しています！Twitterやインスタフォローお願いします★',
            '料理大好き★|フレンチに挑戦中',
            '週に5本は映画を見る自称映画ソムリエ|最近のトレンドはインド映画★',
            'PHP | React | Ruby 書けます<< 仕事相談募集中 >>',
            '社会人向けサッカーサークル会長兼フリーランスエンジニアです',
            '自称ハイボールソムリエ|自宅にバーカウンターを特設した酒大好きマン',
            'ミナミでバーを運営しています★Twitterやインスタフォローお願いします！',
            '就職活動中の地方大学生！都会に憧れて都内で就職を目指しています★',
            '一期一会のヒッチハイクが好きな24歳です',
            '京都のライブハウスで定期ライブ実施中★毎月第2木曜日にライブしてますので遊びに来てください！',
            '自営業で休日に釣りばかりしています！釣りが好きな方向けにグループを作っていますので、気軽に参加申請よろしく★'
        ];

        User::create([
            'name'              => 'root',
            'email'             => 'root@xxx.co.jp',
            'email_verified_at' => $dt->subDay(100),
            'password'          => Hash::make('root1234'),
            'gender'            => config('const.User.MAN'),
            'status'            => config('const.User.ADMIN'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 1,
            'created_at'        => $dt->subDay(100),
            'updated_at'        => $dt->subDay(100)
        ]);

        User::create([
            'name'              => 'test',
            'email'             => 'test@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 2,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'ryuken',
            'email'             => 'ryuken@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 3,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'mayu',
            'email'             => 'mayu@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 4,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'yuri',
            'email'             => 'yuri@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 5,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'daiki',
            'email'             => 'daiki@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 6,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'yuto',
            'email'             => 'yuto@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 7,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'naomi',
            'email'             => 'naomi@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 8,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'meru',
            'email'             => 'meru@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 9,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'aiko',
            'email'             => 'aiko@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 10,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'kento',
            'email'             => 'kento@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 11,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'toki',
            'email'             => 'toki@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 12,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'kuro',
            'email'             => 'kuro@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 13,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'shinya',
            'email'             => 'shinya@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 14,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'ruri',
            'email'             => 'ruri@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 15,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'nao',
            'email'             => 'nao@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 16,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'toru',
            'email'             => 'toru@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 17,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'reika',
            'email'             => 'reika@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 18,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'miku',
            'email'             => 'miku@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 19,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'rio',
            'email'             => 'rio@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 20,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'yoko',
            'email'             => 'yoko@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 21,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'wataru',
            'email'             => 'wataru@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 22,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'kento23',
            'email'             => 'kento23@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 23,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'yamato',
            'email'             => 'yamato@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 24,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'kaito',
            'email'             => 'kaito@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 25,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'nami',
            'email'             => 'nami@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 26,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'sae',
            'email'             => 'sae@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 27,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'manami',
            'email'             => 'manami@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 28,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'kumi',
            'email'             => 'kumi@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 29,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'ai',
            'email'             => 'ai@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 30,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'azusa',
            'email'             => 'azusa@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 31,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'gai',
            'email'             => 'gai@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 32,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'shogo',
            'email'             => 'shogo@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 33,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'gakuto',
            'email'             => 'gakuto@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 34,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'shun',
            'email'             => 'shun@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 35,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'kei',
            'email'             => 'kei@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 36,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'tetsu',
            'email'             => 'tetsu@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 37,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'eito',
            'email'             => 'eito@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 38,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'yoshi',
            'email'             => 'yoshi@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 39,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'riku',
            'email'             => 'riku@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 40,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'takuya',
            'email'             => 'takuya@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.MAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 41,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
        User::create([
            'name'              => 'mia',
            'email'             => 'mia@xxx.co.jp',
            'email_verified_at' => $dt->addDay(1),
            'password'          => Hash::make('test1234'),
            'gender'            => config('const.User.WOMAN'),
            'hobby'             => $hobby[$faker->numberBetween(0, 9)],
            'description'       => $description[$faker->numberBetween(0, 19)],
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => 42,
            'created_at'        => $dt->addDay(1),
            'updated_at'        => $dt->addDay(1)
        ]);
    }
}
