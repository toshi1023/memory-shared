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
        $description = [
            '梅田カフェ巡り！ほっと一息つけるカフェタイムを楽しみにでかけるグループです！',
            '予想を超えます！100店近くのお店が軒を連ねる新梅田食道街でハシゴ酒しよう！',
            '大阪駅のランチ会★',
            'いつメン京都旅行★',
            'バスケサークルBASKE★の思い出共有グループです',
            '北海道海の幸満喫旅行サークル！毎年2回企画しています！！',
            '神戸駅の友達作りランチ会！',
            '伏見稲荷山めぐり★参加者いつでも大歓迎です！',
            'Complexファンクラブコミュニティです',
            'ヴィッセル神戸応援コミュニティです'
        ];

        return [
            'name'              => $this->faker->colorName,
            'description'       => $description[$this->faker->numberBetween(0, 9)],
            'private_flg'       => $this->faker->numberBetween(0, 1),
            'welcome_flg'       => $this->faker->numberBetween(0, 1),
            'host_user_id'      => $this->faker->numberBetween(1, 10),
            'update_user_id'    => $this->faker->numberBetween(1, 10),
        ];
    }
}
