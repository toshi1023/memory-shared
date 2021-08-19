<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = [
            '不具合を対応しました。',
            'うれしいニュースです！',
            '本日よりオープン！',
        ];

        $content = [
            'プライベートな画像・動画共有サイトをオープンしました！',
            'ログインが出来ない不具合を対応しました。ご迷惑をおかけしたこと、大変深くお詫び申し上げます。',
            '動画がアップロード出来ない不具合を対応しました。ご迷惑をおかけしたこと、大変深くお詫び申し上げます。',
            'チャットの不具合を対応しました。ご迷惑をおかけしたこと、大変深くお詫び申し上げます。',
            'グループ登録数が100を超えました！いつもご利用ありがとうございます。',
            '当サイトの登録者が1000人突破しました！これからもよろしくお願いいたします。',
        ];

        return [
            'user_id'           => $this->faker->numberBetween(0, 5),
            'news_id'           => $this->faker->numberBetween(1, 200),
            'title'             => $title[$this->faker->numberBetween(0, 2)],
            'content'           =>$content[$this->faker->numberBetween(0, 5)],
            'update_user_id'    => $this->faker->numberBetween(1, 10),
        ];
    }
}
