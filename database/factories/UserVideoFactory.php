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
        return [
            'image_file'        => Str::random(10).'.mp4',
            'user_id'           => $this->faker->numberBetween(1, 3),
            'album_id'          => $this->faker->numberBetween(1, 10),
        ];
    }
}
