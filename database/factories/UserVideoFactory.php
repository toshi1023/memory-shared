<?php

namespace Database\Factories;

use App\Models\UserVideo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserVideoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserVideo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = [
            '2014年忘年会★',
            'rootさん誕生日サプライズ★',
            'NoTitle',
            '同窓会乾杯ムービー★',
            '新入社員歓迎会！',
            'PHP講習会',
            'サロン交流会',
            '阪神戦なう★',
            'ソフトバンク戦なう★',
            '卒業旅行2020★'
        ];

        return [
            'image_file'        => Str::random(10).'mp4',
            'title'             => $title[$this->faker->numberBetween(0, 9)],
            'user_id'           => $this->faker->numberBetween(1, 3),
            'album_id'          => $this->faker->numberBetween(1, 10),
        ];
    }
}
