<?php

namespace Database\Factories;

use App\Models\GroupHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GroupHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'           => $this->faker->numberBetween(1, 10),
            'group_id'          => $this->faker->numberBetween(1, 10),
            'status'            => $this->faker->numberBetween(1, 2),
            'update_user_id'    => $this->faker->numberBetween(1, 10),
        ];
    }
}
