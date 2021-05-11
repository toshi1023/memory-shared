<?php

namespace Database\Factories;

use App\Models\UserImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserImage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'image_file'        => Str::random(10).'.jpg',
            'user_id'           => $this->faker->numberBetween(1, 3),
            'album_id'          => $this->faker->numberBetween(1, 10),
        ];
    }
}
