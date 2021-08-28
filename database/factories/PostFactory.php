<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $content = [
            '8月31日にBBQしましょう！',
            'BBQのときのアルバム作ったよー！
            みんな好きに投稿してー',
            '誰かBBQのときの動画撮ってる人いるー？
            アルバムに追加してほしいんやけど！',
            'みんな自由に投稿してやー！',
            'グループ登録数が100を祝って、祝賀会やろう！',
            '今度の日曜日暇な人ー！',
        ];

        return [
            'content'           => $content[$this->faker->numberBetween(0, 5)],
            'user_id'           => $this->faker->numberBetween(1, 10),
            'group_id'           => $this->faker->numberBetween(1, 10),
            'update_user_id'    => $this->faker->numberBetween(1, 10),
        ];
    }
}
