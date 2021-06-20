<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\MessageHistory;
use Exception;

class MessageHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $message1;
    protected $message2;
    protected $content = [
        'この前の旅行楽しかったね！',
        '元気してるかー？',
        'この前の飲み会の写真アップロードしといて～',
        'めちゃくちゃ楽しかったね！',
        '京都に来たんだ！一緒にご飯食べたかったよ！',
        'この辺でおすすめの観光スポットってある？',
        '今度の休み福岡いこうよ！',
        'だいぶ人数増えたね！',
        '楽しそうな動画だったよ！',
        '久しぶり！元気してるよ！'
    ];
    
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

        // メッセージ履歴の作成(admin -> user)
        $this->message1 = MessageHistory::create([
            'content'           => $this->content[array_rand($this->content)],
            'own_id'            => $this->admin->id,
            'user_id'           => $this->user->id,
            'update_user_id'    => $this->admin->id
        ]);
        // メッセージ履歴の作成(user -> admin)
        $this->message1 = MessageHistory::create([
            'content'           => $this->content[array_rand($this->content)],
            'own_id'            => $this->user->id,
            'user_id'           => $this->admin->id,
            'update_user_id'    => $this->user->id
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
    public function api_messagesにGETメソッドでアクセス()
    {
        // 認証前
        $response = $this->get('/api/messages');

        $response->assertStatus(302);

        // 認証後
        $this->getActingAs($this->admin);

        // 受信者IDを設定していない場合
        $response = $this->get('/api/messages');

        $response->assertStatus(500)
        ->assertJsonFragment([
            'error_message' => config('const.Message.GET_ERR')
        ]);

        // 正常なリクエストを実行
        $response = $this->get('/api/messages?user_id='.$this->user->id);

        $response->assertOk()
        ->assertJsonFragment([
            'own_id'       => $this->admin->id,
            'user_id'      => $this->user->id,
        ]);
    }

    /**
     * @test
     */
    public function メッセージ作成の動作を確認()
    {
        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        // メッセージ作成(失敗例: 内容が入力されていない場合)
        $data = [
            'own_id'            => $this->admin->id,
            'user_id'           => $this->user->id,
            'update_user_id'    => $this->admin->id
        ];

        $response = $this->post('api/messages', $data);

        $response->assertStatus(400)
        ->assertJsonFragment([
            'errors' => [
                'content' => ['メッセージが入力されていません'],
        ]]);

        // メッセージ作成(成功例)
        $data['content'] = $this->content[array_rand($this->content)];

        $response = $this->post('api/messages', $data);

        $response->assertOk()
        ->assertJsonFragment([
            'content'           => $data['content'],
            'own_id'            => $this->admin->id,
            'user_id'           => $this->user->id,
            'update_user_id'    => $this->admin->id
        ]);

        // ユーザを認証済みに書き換え
        $this->getActingAs($this->user);

        // adminとのメッセージを取得
        $response = $this->get('/api/messages?user_id='.$this->admin->id);

        $response->assertOk()
        ->assertJsonFragment([
            'content'           => $data['content'],
            'own_id'            => $this->admin->id,
            'user_id'           => $this->user->id,
            'update_user_id'    => $this->admin->id
        ]);

        // メッセージ保存と同時にmessage_relationsテーブルにもデータが登録されているか確認
        $this->assertDatabaseHas('message_relations', [
            'user_id1' => $this->admin->id,
            'user_id2' => $this->user->id
        ]);
        $exists = DB::table('message_relations')->where('user_id1', '=', $this->admin->id)->where('user_id2', '=', $this->user->id)->exists();
        if(!$exists) throw new Exception();
    }
}
