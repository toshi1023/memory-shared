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
use App\Models\Album;
use Carbon\Carbon;

class AlbumTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $group;
    const ALBUM = 'album1';

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
        $response = $this->get('/api/albums?group_id='.$this->group->id);

        $response->assertStatus(302);

        // 認証後
        $this->getActingAs($this->admin);

        $response = $this->get('/api/albums?group_id='.$this->group->id);

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
            'name'              => 'album2',
            'group_id'          => $this->group->id,
            'host_user_id'      => $this->admin->id,
            'update_user_id'    => $this->admin->id,
            'created_at'        => $datetime,
            'updated_at'        => $datetime
        ]);

        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        // created_atの正常な検索動作を確認
        $response = $this->get('api/albums/?group_id='.$this->group->id.'&created_at@>equal='.$today);

        $response->assertOk()
        ->assertJsonFragment([
            'name' => $this->album->name,
        ]);

        $response = $this->get('api/albums/?group_id='.$this->group->id.'&created_at@<equal='.$today);

        $response->assertOk()
        ->assertJsonFragment([
            'name' => $album->name,
        ]);
    }
}
