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
use Exception;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $group;

    private const DIS = '梅田カフェ巡り！ほっと一息つけるカフェタイムを楽しみにでかけるグループです！';

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
            'name'              => 'RoyalBlue',
            'discription'       => self::DIS,
            'host_user_id'      => $this->admin->id,
            'update_user_id'    => $this->admin->id,
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
    public function api_groupsにGETメソッドでアクセス()
    {
        // 認証前
        $response = $this->get('/api/groups');

        $response->assertStatus(302);

        // 認証後
        $this->getActingAs($this->admin);

        $response = $this->get('/api/groups');

        $response->assertOk()
        ->assertJsonFragment([
            'private_flg'  => config('const.Group.PUBLIC'),
            'host_user_id' => $this->admin->id,
        ]);
    }

    /**
     * @test
     */
    public function showアクションにGETメソッドでアクセス()
    {
        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        $response = $this->get('api/groups/'.$this->group->name);

        $response->assertOk()
        ->assertJsonFragment([
            'host_user_id' => $this->admin->id,
        ]);

        // 存在しないグループを検索
        $response = $this->get('api/groups/test');
        
        $response->assertStatus(404)
        ->assertJsonFragment([
            'error_message' => config('const.Group.SEARCH_ERR')
        ]);
    }

    /**
     * @test
     */
    public function グループ作成の動作を確認()
    {
        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        // グループ作成(失敗例: アルバム名が重複する場合)
        $data = [
            'name'              => $this->group->name,
            'discription'       => 'まったり旅をするグループです',
            'host_user_id'      => $this->admin->id,
            'update_user_id'    => $this->admin->id,
        ];

        $response = $this->post('api/groups', $data);

        $response->assertStatus(400)
        ->assertJsonFragment([
            'errors' => [
                'name' => ['このグループ名は既に存在します'],
        ]]);

        // グループ作成(成功例)
        $data = [
            'name'              => 'Group1',
            'discription'       => 'まったり旅をするグループです',
            'host_user_id'      => $this->admin->id,
            'update_user_id'    => $this->admin->id,
        ];

        $response = $this->post('api/groups', $data);

        $response->assertOk()
        ->assertJsonFragment([
            'info_message' => config('const.Group.REGISTER_INFO')
        ]);
    }

    /**
     * @test
     */
    public function グループ更新の動作を確認()
    {
        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        // グループ更新(失敗例: グループの更新権限がない場合)
        $data = [
            'id'                => $this->group->id,
            'name'              => $this->group->name,
            'discription'       => $this->group->discription,
            'private_flg'       => config('const.Group.PRIVATE'),
            'host_user_id'      => $this->user->id,
            'update_user_id'    => $this->user->id,
        ];

        $response = $this->put('api/groups/'.$this->group->name, $data);

        $response->assertStatus(400)
        ->assertJsonFragment([
            'errors' => [
                'host_user_id' => ['グループ作成者以外はグループ情報を更新できません'],
        ]]);

        // グループ作成(成功例)
        $data = [
            'id'                => $this->group->id,
            'name'              => $this->group->name,
            'discription'       => $this->group->discription,
            'private_flg'       => config('const.Group.PRIVATE'),
            'host_user_id'      => $this->admin->id,
            'update_user_id'    => $this->admin->id,
        ];

        $response = $this->put('api/groups/'.$this->group->name, $data);

        $response->assertOk()
        ->assertJsonFragment([
            'info_message' => config('const.Group.REGISTER_INFO')
        ]);
    }

    /**
     * @test
     */
    public function group削除の動作を確認()
    {
        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        $response = $this->delete('api/groups/'.$this->group->name);

        $response->assertOk()
        ->assertJsonFragment([
            'info_message' => config('const.Group.DELETE_INFO'),
        ]);
    }
}
