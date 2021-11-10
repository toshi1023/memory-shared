<?php

namespace App\data;

use App\Models\News;
use Faker\Generator as Faker;
use Carbon\Carbon;

class NewsTableData
{
    public static function run(Faker $faker)
    {
        $dt = new Carbon('now');
        $now = new Carbon('now');

        News::create([
            'user_id'           => 0,
            'news_id'           => 1,
            'title'             => '本日よりオープン！',
            'content'           => 'プライベートな画像・動画共有サイトをオープンしました！',
            'update_user_id'    => 1,
            'created_at'        => $dt->subDay(30),
            'updated_at'        => $dt->subDay(30)
        ]);
        News::create([
            'user_id'           => 1,
            'news_id'           => 1,
            'title'             => 'MemoryShareAppへようこそ',
            'content'           => 'MemoryShareAppの会員登録が完了しました。素敵な仲間と一緒に写真や動画を投稿し合って、思い出を共有しましょう！',
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 2,
            'news_id'           => 1,
            'title'             => 'MemoryShareAppへようこそ',
            'content'           => 'MemoryShareAppの会員登録が完了しました。素敵な仲間と一緒に写真や動画を投稿し合って、思い出を共有しましょう！',
            'update_user_id'    => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 3,
            'news_id'           => 1,
            'title'             => 'MemoryShareAppへようこそ',
            'content'           => 'MemoryShareAppの会員登録が完了しました。素敵な仲間と一緒に写真や動画を投稿し合って、思い出を共有しましょう！',
            'update_user_id'    => 3,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 4,
            'news_id'           => 1,
            'title'             => 'MemoryShareAppへようこそ',
            'content'           => 'MemoryShareAppの会員登録が完了しました。素敵な仲間と一緒に写真や動画を投稿し合って、思い出を共有しましょう！',
            'update_user_id'    => 4,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 1,
            'news_id'           => 2,
            'title'             => 'CafeOsakaClubの参加申請について',
            'content'           => 'CafeOsakaClubの参加が承認されました。Home画面の参加グループ一覧よりご確認ください。',
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 2,
            'news_id'           => 2,
            'title'             => 'vissel communityの参加申請について',
            'content'           => 'vissel communityの参加が承認されました。Home画面の参加グループ一覧よりご確認ください。',
            'update_user_id'    => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 1,
            'news_id'           => 3,
            'title'             => 'OsakaLunchの掲示板が新規投稿されました',
            'content'           => 'testさんがOsakaLunchの掲示板に新たな投稿を追加しました。掲示板にて内容を確認することが出来ます',
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 1,
            'news_id'           => 4,
            'title'             => 'いつメン★の掲示板が新規投稿されました',
            'content'           => 'reikaさんがいつメン★の掲示板に新たな投稿を追加しました。掲示板にて内容を確認することが出来ます',
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 1,
            'news_id'           => 5,
            'title'             => 'complexCummunityの掲示板が新規投稿されました',
            'content'           => 'yoshiさんがcomplexCummunityの掲示板に新たな投稿を追加しました。掲示板にて内容を確認することが出来ます',
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 2,
            'news_id'           => 3,
            'title'             => 'bask_circleの参加申請について',
            'content'           => 'bask_circleの参加が承認されました。Home画面の参加グループ一覧よりご確認ください。',
            'update_user_id'    => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 2,
            'news_id'           => 4,
            'title'             => 'CafeOsakaClubの掲示板が新規投稿されました',
            'content'           => 'rootさんがCafeOsakaClubの掲示板に新たな投稿を追加しました。掲示板にて内容を確認することが出来ます',
            'update_user_id'    => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 2,
            'news_id'           => 5,
            'title'             => 'CafeOsakaClubの掲示板が新規投稿されました',
            'content'           => 'meruさんがCafeOsakaClubの掲示板に新たな投稿を追加しました。掲示板にて内容を確認することが出来ます',
            'update_user_id'    => 2,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 0,
            'news_id'           => 2,
            'title'             => '不具合を対応しました。',
            'content'           => 'ログインが出来ない不具合を対応しました。ご迷惑をおかけしたこと、大変深くお詫び申し上げます。',
            'update_user_id'    => 1,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 0,
            'news_id'           => 3,
            'title'             => 'うれしいニュースです！',
            'content'           => '当サイトの登録者が1000人突破しました！これからもよろしくお願いいたします。',
            'update_user_id'    => 1,
            'created_at'        => $now,
            'updated_at'        => $now
        ]);
        News::create([
            'user_id'           => 5,
            'news_id'           => 1,
            'title'             => 'MemoryShareAppへようこそ',
            'content'           => 'MemoryShareAppの会員登録が完了しました。素敵な仲間と一緒に写真や動画を投稿し合って、思い出を共有しましょう！',
            'update_user_id'    => 5,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 6,
            'news_id'           => 1,
            'title'             => 'MemoryShareAppへようこそ',
            'content'           => 'MemoryShareAppの会員登録が完了しました。素敵な仲間と一緒に写真や動画を投稿し合って、思い出を共有しましょう！',
            'update_user_id'    => 6,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 7,
            'news_id'           => 1,
            'title'             => 'MemoryShareAppへようこそ',
            'content'           => 'MemoryShareAppの会員登録が完了しました。素敵な仲間と一緒に写真や動画を投稿し合って、思い出を共有しましょう！',
            'update_user_id'    => 7,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 8,
            'news_id'           => 1,
            'title'             => 'MemoryShareAppへようこそ',
            'content'           => 'MemoryShareAppの会員登録が完了しました。素敵な仲間と一緒に写真や動画を投稿し合って、思い出を共有しましょう！',
            'update_user_id'    => 8,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 9,
            'news_id'           => 1,
            'title'             => 'MemoryShareAppへようこそ',
            'content'           => 'MemoryShareAppの会員登録が完了しました。素敵な仲間と一緒に写真や動画を投稿し合って、思い出を共有しましょう！',
            'update_user_id'    => 9,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
        News::create([
            'user_id'           => 10,
            'news_id'           => 1,
            'title'             => 'MemoryShareAppへようこそ',
            'content'           => 'MemoryShareAppの会員登録が完了しました。素敵な仲間と一緒に写真や動画を投稿し合って、思い出を共有しましょう！',
            'update_user_id'    => 10,
            'created_at'        => $dt->addHour(),
            'updated_at'        => $dt->addHour()
        ]);
    }
}