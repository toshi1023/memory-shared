<?php

namespace Database\Factories;

use App\Models\MessageRelation;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageRelationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MessageRelation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'own_id'            => $this->faker->numberBetween(1, 10),
            'user_id'           => $this->faker->numberBetween(1, 10),
            'update_user_id'    => $this->faker->numberBetween(1, 10),
        ];
    }
}
