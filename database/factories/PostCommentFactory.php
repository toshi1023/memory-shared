<?php

namespace Database\Factories;

use App\Models\PostComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $content = [
            'BBQ楽しかったねー！',
            'またやろう！',
            '日曜日空いてるよー！',
            '撮っていた写真とか動画あったらアップするねー！',
            '祝賀会参加するー！',
            '楽しみー！',
        ];

        return [
            'content'           => $content[$this->faker->numberBetween(0, 5)],
            'user_id'           => $this->faker->numberBetween(1, 10),
            'post_id'           => $this->faker->numberBetween(1, 10),
            'update_user_id'    => $this->faker->numberBetween(1, 10),
        ];
    }
}
