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
            'user_id1'            => $this->faker->numberBetween(1, 20),
            'user_id2'            => $this->faker->numberBetween(1, 20)
        ];
    }
}
