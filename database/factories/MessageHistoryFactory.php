<?php

namespace Database\Factories;

use App\Models\MessageHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MessageHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
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

        return [
            'content'           => $content[$this->faker->numberBetween(0, 9)],
            'own_id'            => $this->faker->numberBetween(1, 10),
            'user_id'           => $this->faker->numberBetween(1, 10),
            'update_user_id'    => $this->faker->numberBetween(1, 10),
        ];
    }
}
