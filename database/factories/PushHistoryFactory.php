<?php

namespace Database\Factories;

use App\Models\PushHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class PushHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PushHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = [
            'グループの申請について',
            'グループの招待について',
            '新着メッセージ！',
            'レコード更新！',
        ];

        $content = [
            'グループの参加が承認されました。参加グループ一覧よりご確認ください。',
            '招待したユーザがグループを参加されました。',
            'グループの参加人数が100人を超えました。',
            'グループの投稿数が100を超えました。',
            'グループの投稿数が1000を超えました。',
            'チャットに新着メッセージが届いております。'
        ];

        return [
            'title'             => $title[$this->faker->numberBetween(0, 2)],
            'content'           => $content[$this->faker->numberBetween(0, 5)],
            'type'              => $this->faker->numberBetween(1, 2),
            'send_count'        => $this->faker->numberBetween(1, 100),
            'reservation_date'  => date_format($this->faker->dateTimeBetween($startDate = 'now', $endDate = '20 days'), 'Y-m-d H:i'), // 本日から20日後までで設定
            'status'            => $this->faker->numberBetween(1, 4),
            'update_user_id'    => $this->faker->numberBetween(1, 10),
        ];
    }
}
