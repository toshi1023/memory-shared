<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Group::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'              => $this->faker->colorName,
            'host_user_id'      => $this->faker->numberBetween(1, 10),
            'update_user_id'    => $this->faker->numberBetween(1, 10),
        ];
    }
}
