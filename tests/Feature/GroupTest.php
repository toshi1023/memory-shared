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

    /**
     * テストの前処理を実行
     */
    public function setUp(): void{
        parent::setUp();

        // 認証済みユーザの作成
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
            'discription'       => '梅田カフェ巡り！ほっと一息つけるカフェタイムを楽しみにでかけるグループです！',
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
            'host_user_id' => $this->admin->id,
        ]);

        // 存在しないグループを検索
        $response = $this->get('api/groups/test');
        
        $response->assertOk()
        ->assertJsonFragment([
            'error_message' => config('const.Group.SEARCH_ERR')
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
        
        $response->assertOk()
        ->assertJsonFragment([
            'error_message' => config('const.Group.SEARCH_ERR')
        ]);
    }
}
