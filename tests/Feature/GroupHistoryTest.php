<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupHistory;

class GroupHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $group;

    private const DIS = 'グループ申請の動作を確認するテスト用グループです！';

    /**
     * テストの前処理を実行
     */
    public function setUp(): void{
        parent::setUp();

        // 管理者ユーザの作成
        $this->admin = 
            User::create([
                'name'              => 'root',
                'email'             => 'root@xxx.co.jp',
                'email_verified_at' => now(),
                'password'          => Hash::make('root1234'),
                'status'            => config('const.User.ADMIN'),
                'user_agent'        => '',
                'remember_token'    => Str::random(10),
                'update_user_id'    => 1,
            ]);

        // 認証済みでないユーザの作成
        $this->user = User::factory()->create();

        // グループの作成
        $this->group = Group::create([
            'name'              => 'GroupHistoryTest',
            'description'       => self::DIS,
            'private_flg'       => config('const.Group.PUBLIC'),
            'host_user_id'      => $this->admin->id,
            'update_user_id'    => $this->admin->id,
        ]);
        // グループ参加履歴の作成
        GroupHistory::create([
            'user_id'              => $this->admin->id,
            'group_id'             => $this->group->id,
            'status'               => config('const.GroupHistory.APPROVAL'),
            'update_user_id'       => $this->admin->id
        ]);
        GroupHistory::create([
            'user_id'              => $this->user->id,
            'group_id'             => $this->group->id,
            'status'               => config('const.GroupHistory.APPLY'),
            'update_user_id'       => $this->user->id
        ]);
    }

    /**
     * ユーザを認証済みにしてリターン
     * 引数: ユーザ情報
     */
    private function getActingAs($user)
    {
        return Sanctum::actingAs($user, ['*']);
    }

    /**
     * @test
     */
    public function api_historyにGETメソッドでアクセス()
    {
        // 認証前
        $response = $this->get('/api/history');

        $response->assertStatus(302);

        // 認証後(グループ申請中ユーザ情報をメインで取得)
        $this->getActingAs($this->admin);

        $response = $this->get('/api/history?group_id='.$this->group->id.'&status='.config('const.GroupHistory.APPLY').'&sort_created_at=desc');

        $response->assertOk()
        ->assertJsonFragment([
            'user_id'       => $this->user->id,
            'group_id'      => $this->group->id,
            'name'          => $this->user->name
        ]);

        // 認証後(グループ申請中と申請済みグループ情報をメインで取得)
        $this->getActingAs($this->admin);

        $response = $this->get('/api/history?@not_equalstatus='.config('const.GroupHistory.REJECT'));

        $response->assertOk()
        ->assertJsonFragment([
            'user_id'       => $this->admin->id,
            'group_id'      => $this->group->id,
            'name'          => $this->group->name
        ])
        ->assertJsonMissing([
            'user_id'       => $this->user->id,
            'name'          => $this->user->name
        ]);
    }

    /**
     * @test
     */
    public function グループ履歴作成の動作を確認()
    {
        // グループの作成
        $group = Group::create([
            'name'              => 'RedRock',
            'description'       => '神戸駅の友達作りランチ会！',
            'host_user_id'      => $this->admin->id,
            'update_user_id'    => $this->admin->id,
        ]);
        // グループ参加履歴の作成
        GroupHistory::create([
            'user_id'              => $this->admin->id,
            'group_id'             => $group->id,
            'status'               => config('const.GroupHistory.APPROVAL'),
            'update_user_id'       => $this->admin->id
        ]);

        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        // 履歴の作成(失敗例: 既存データが存在するのに、新規作成を実行しようとした場合)
        $data = [
            'user_id'              => $this->admin->id,
            'group_id'             => $group->id,
            'status'               => config('const.GroupHistory.APPLY'),
        ];
        
        $response = $this->post('api/groups/'.$group->id.'/history', $data);

        // 申請用のエラーメッセージを返す
        $response->assertStatus(500)
        ->assertJsonFragment([
            'error_message' => config('const.GroupHistory.APPLY_ERR')
        ]);

        // 招待時にエラーが発生した場合は招待時用のエラーメッセージを返す
        $data['status'] = config('const.GroupHistory.APPROVAL');
        
        $response = $this->post('api/groups/'.$group->id.'/history', $data);

        $response->assertStatus(500)
        ->assertJsonFragment([
            'error_message' => config('const.GroupHistory.INVITE_ERR')
        ]);

        // ユーザを認証済みに書き換え
        $this->getActingAs($this->user);
        // 申請時の履歴の作成(成功)
        $data = [
            'group_id'             => $group->id,
            'status'               => config('const.GroupHistory.APPLY'),
        ];
        
        $response = $this->post('api/groups/'.$group->id.'/history', $data);

        $response->assertStatus(200)
        ->assertJsonFragment([
            'info_message' => config('const.GroupHistory.APPLY_INFO'),
            'id'           => $group->id,
            'name'         => $group->name,
            'id'           => $this->user->id,
            'name'         => $this->user->name,
            'user_id'      => $this->user->id
        ]);

        // 招待時の履歴の作成(成功)
        $user = User::factory()->create();
        $data = [
            'user_id'              => $user->id,
            'group_id'             => $this->group->id,
            'status'               => config('const.GroupHistory.APPROVAL'),
        ];
        
        $response = $this->post('api/groups/'.$group->id.'/history', $data);

        $response->assertStatus(200)
        ->assertJsonFragment([
            'info_message' => config('const.GroupHistory.INVITE_INFO'),
            'id'           => $this->group->id,
            'name'         => $this->group->name
        ]);
        // // グループ作成(成功例)
        // $data = [
        //     'name'              => 'Group1',
        //     'description'       => 'まったり旅をするグループです',
        //     'host_user_id'      => $this->admin->id,
        //     'update_user_id'    => $this->admin->id,
        // ];

        // $response = $this->post('api/groups', $data);

        // // レスポンスのチェック
        // $response->assertOk()
        //          ->assertJsonFragment([
        //             'info_message' => config('const.Group.REGISTER_INFO')
        //          ]);
        // // グループ作成と同時にgroup_historiesテーブルにも作成者のデータが登録されているか確認
        // $this->assertDatabaseHas('group_histories', [
        //     'user_id' => $this->admin->id,
        // ]);
    }
}
