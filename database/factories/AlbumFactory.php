<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlbumFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Album::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'              => $this->faker->city,
            'group_id'          => $this->faker->numberBetween(1, 10),
            'host_user_id'      => $this->faker->numberBetween(1, 10),
            'update_user_id'    => $this->faker->numberBetween(1, 10),
        ];
    }
}
