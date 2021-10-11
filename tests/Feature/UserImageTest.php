<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupHistory;
use App\Models\Album;

class UserImageTest extends TestCase
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

        // ユーザの作成
        $this->user = User::factory()->create();

        // グループの作成
        $this->group = Group::create([
            'name'              => 'RoyalBlue',
            'description'       => '梅田カフェ巡り！ほっと一息つけるカフェタイムを楽しみにでかけるグループです！',
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
            'name'           => 'album1',
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
    public function 画像保存の動作を確認()
    {
        // // ダミーフォルダを作成
        // Storage::fake('memory');

        // // 新規ユーザを作成
        // $user = User::factory()->create();
        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        // // // 画像作成
        // $file1 = UploadedFile::fake()->image('memory.jpg');
        // $file2 = UploadedFile::fake()->image('share.jpg');

        // // 画像作成
        // $data1 = [
        //     'image_file'        => $file1,
        //     'user_id'           => $this->admin->id,
        //     'album_id'          => $this->album->id,
        //     'black_list[]'      => $this->user->id,
        //     'black_list[]'      => $user->id,
        // ];

        // // 画像作成(成功例)
        // $response = $this->post('/api/groups/'.$this->group->name.'/albums/'.$this->album->name.'/images', $data1);

        // // Storage::disk('memory')->assertExists($file->hashName());

        // // $response->assertStatus(400)
        // // ->assertJsonFragment([
        // //     'errors' => [
        // //         'host_user_id' => ['このグループでアルバムを作成する権限がありません'],
        // // ]]);

        // $response->assertOk()
        // ->assertJsonFragment([
        //     'info_message' => config('const.UserImage.REGISTER_INFO')
        // ]);

        // // ブラックリスト&ホワイトリストの確認
        // $response = $this->get('/api/groups/'.$this->group->name.'/albums/'.$this->album->name);

        // $response->assertOk()
        // ->assertJsonFragment([
        //     'black_list' => json_encode([$this->user->id => $this->user->id, $user->id => $user->id])
        // ]);
    }

}
