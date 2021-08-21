<?php

namespace Database\Factories;

use App\Models\MreadManagement;
use Illuminate\Database\Eloquent\Factories\Factory;

class MreadManagementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MreadManagement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'message_id'  => $this->faker->numberBetween(1, 20),
            'own_id'      => $this->faker->numberBetween(1, 20),
            'user_id'     => $this->faker->numberBetween(1, 20),
        ];
    }
}
