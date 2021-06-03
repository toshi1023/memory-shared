<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $hobby = [
            '映画鑑賞',
            'サッカー観戦',
            'FPSゲーム',
            'プログラミング',
            '釣り',
            'ドライブ',
            'ラーメン巡り',
            '海外旅行',
            'ペット飼育',
            '料理'
        ];


        return [
            'name'              => $this->faker->name,
            'hobby'             => $hobby[$this->faker->numberBetween(0, 9)],
            'gender'            => $this->faker->numberBetween(0, 1),
            'email'             => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password'          => Hash::make('test1234'),
            'status'            => config('const.User.MEMBER'),
            'user_agent'        => $this->faker->userAgent,
            'remember_token'    => Str::random(10),
            'update_user_id'    => $this->faker->numberBetween(1, 10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     * 訳)モデルのメールアドレスを未確認にする必要があることを示します。
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
