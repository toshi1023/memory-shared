<?php

namespace Database\Factories;

use App\Models\NreadManagement;
use Illuminate\Database\Eloquent\Factories\Factory;

class NreadManagementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NreadManagement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'news_user_id'      => $this->faker->numberBetween(0, 20),
            'news_id'           => $this->faker->numberBetween(1, 10),
            'user_id'           => $this->faker->numberBetween(1, 10),
        ];
    }
}
