<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupHistory;
use App\Models\Album;
use Carbon\Carbon;

class AlbumTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $group;
    const ALBUM = 'album1';
    const ALBUM2 = 'album2';
    const ALBUM3 = 'album3';

    /**
     * テストの前処理を実行
     */
    public function setUp(): void{
        parent::setUp();


        // 管理ユーザの作成
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

        // ユーザの作成
        $this->user = User::factory()->create();

        // グループの作成
        $this->group = Group::create([
            'name'              => 'RoyalBlue',
            'discription'       => '梅田カフェ巡り！ほっと一息つけるカフェタイムを楽しみにでかけるグループです！',
            'host_user_id'      => $this->admin->id,
            'update_user_id'    => $this->admin->id,
        ]);

        // グループ履歴の作成
        GroupHistory::create([
            'user_id'       => $this->admin->id,
            'group_id'      => $this->group->id,
            'status'        => config('const.GroupHistory.APPROVAL')
        ]);

        // アルバムの作成
        $this->album = Album::create([
            'name'           => self::ALBUM,
            'group_id'       => $this->group->id,
            'host_user_id'   => $this->admin->id,
            'update_user_id' => $this->admin->id
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
    public function api_albumsにGETメソッドでアクセス()
    {
        // 認証前
        $response = $this->get('/api/groups/'.$this->group->name.'/albums');

        $response->assertStatus(302);

        // 認証後
        $this->getActingAs($this->admin);

        $response = $this->get('/api/groups/'.$this->group->name.'/albums');

        $response->assertOk()
        ->assertJsonFragment([
            'group_id'     => $this->group->id,
            'host_user_id' => $this->admin->id,
        ]);
    }

    /**
     * @test
     */
    public function クエリストリングの動作を確認()
    {
        $datetime = new Carbon('yesterday', 'Asia/Tokyo');
        $today = new Carbon('today', 'Asia/Tokyo');

        // アルバムの作成
        $album = Album::create([
            'name'              => self::ALBUM2,
            'group_id'          => $this->group->id,
            'host_user_id'      => $this->admin->id,
            'update_user_id'    => $this->admin->id,
            'created_at'        => $datetime,
            'updated_at'        => $datetime
        ]);

        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        // created_atの正常な検索動作を確認
        $response = $this->get('/api/groups/'.$this->group->name.'/albums?created_at@>equal='.$today);

        $response->assertOk()
        ->assertJsonFragment([
            'name' => $this->album->name,
        ]);

        $response = $this->get('/api/groups/'.$this->group->name.'/albums?created_at@<equal='.$today);

        $response->assertOk()
        ->assertJsonFragment([
            'name' => $album->name,
        ]);
    }

    /**
     * @test
     */
    public function アルバム作成の動作を確認()
    {
        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        // アルバム作成(失敗例: アルバム作成権限がない場合)
        // ※自作ルールの動作を確認
        $data = [
            'name'           => self::ALBUM3,
            'group_id'       => $this->group->id,
            'host_user_id'   => $this->user->id,
            'update_user_id' => $this->user->id
        ];

        $response = $this->post('/api/groups/'.$this->group->name.'/albums', $data);

        $response->assertStatus(400)
        ->assertJsonFragment([
            'errors' => [
                'host_user_id' => ['このグループでアルバムを作成する権限がありません'],
        ]]);

        // アルバム作成(成功例)
        $data = [
            'name'           => self::ALBUM3,
            'group_id'       => $this->group->id,
            'host_user_id'   => $this->admin->id,
            'update_user_id' => $this->admin->id
        ];

        $response = $this->post('/api/groups/'.$this->group->name.'/albums', $data);

        $response->assertOk()
        ->assertJsonFragment([
            'info_message' => config('const.Album.REGISTER_INFO')
        ]);
    }

    /**
     * @test
     */
    public function アルバム更新の動作を確認()
    {
        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        // アルバム更新(失敗例: アルバム更新権限がない場合)
        // ※自作ルールの動作を確認
        $data = [
            'id'             => $this->album->id,
            'name'           => self::ALBUM3,
            'group_id'       => $this->group->id,
            'host_user_id'   => $this->user->id,
            'update_user_id' => $this->user->id
        ];

        $response = $this->put('/api/groups/'.$this->group->name.'/albums/'.$this->album->name, $data);

        $response->assertStatus(400)
        ->assertJsonFragment([
            'errors' => [
                'host_user_id' => ['このグループでアルバムを作成する権限がありません', 'アルバム作成者以外はアルバム情報を更新できません'],
        ]]);

        // アルバム作成(成功例)
        $data = [
            'name'           => self::ALBUM3,
            'group_id'       => $this->group->id,
            'host_user_id'   => $this->admin->id,
            'update_user_id' => $this->admin->id
        ];

        $response = $this->put('/api/groups/'.$this->group->name.'/albums/'.$this->album->name, $data);

        $response->assertOk()
        ->assertJsonFragment([
            'info_message' => config('const.Album.REGISTER_INFO')
        ]);
    }

    /**
     * @test
     */
    public function album削除の動作を確認()
    {
        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        $response = $this->delete('/api/groups/'.$this->group->name.'/albums/'.$this->album->name);

        $response->assertOk()
        ->assertJsonFragment([
            'info_message' => config('const.Album.DELETE_INFO'),
        ]);
    }
}
