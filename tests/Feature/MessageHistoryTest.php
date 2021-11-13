<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use App\Events\MessageCreated;
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
        $this->message2 = MessageHistory::create([
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
        $response = $this->get('api/users/'.$this->admin->id.'/messages');

        $response->assertStatus(302);

        // 認証後
        $this->getActingAs($this->admin);

        // 受信者IDを設定していない場合
        $response = $this->get('api/users/'.$this->admin->id.'/messages');
        
        $response->assertStatus(500)
        ->assertJsonFragment([
            'error_message' => config('const.Message.GET_ERR')
        ]);

        // 正常なリクエストを実行
        $response = $this->get('api/users/'.$this->admin->id.'/messages?user_id='.$this->user->id);
        
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

        $response = $this->post('api/users/'.$this->admin->id.'/messages', $data);

        $response->assertStatus(400)
        ->assertJsonFragment([
            'errors' => [
                'content' => ['メッセージが入力されていません'],
        ]]);

        // イベントをコントローラからdispatchしないように設定
        Event::fake();

        // メッセージ作成(成功例)
        $data['content'] = $this->content[array_rand($this->content)];

        $response = $this->post('api/users/'.$this->admin->id.'/messages', $data);

        $response->assertOk()
        ->assertJsonFragment([
            'content'           => $data['content'],
            'own' => [
                'id'            => $this->admin->id,
                'image_file'    => $this->admin->image_file,
                'image_url'     => $this->admin->image_url,
                'name'          => $this->admin->name
            ],
            'own_id'            => $this->admin->id,
            'user_id'           => $this->user->id,
            'update_user_id'    => $this->admin->id
        ]);

        // イベントのdispatchを確認し、受け取った値の内容が上記で作成したメッセージと一致するかどうかを確認
        Event::assertDispatched(function (MessageCreated $event) use ($response) {
            return $event->talk->content === $response->json()['talk']['content'];
        });

        // ユーザを認証済みに書き換え
        $this->getActingAs($this->user);

        // adminとのメッセージを取得
        $response = $this->get('api/users/'.$this->user->id.'/messages?user_id='.$this->admin->id);

        // 先ほど保存されたメッセージが確認できることを確認
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

    /**
     * @test
     */
    public function メッセージ削除の動作を確認()
    {
        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        // 自身以外のメッセージを削除する場合はエラーを返す
        $response = $this->delete('api/users/'.$this->admin->id.'/messages/'.$this->message2->id);

        $response->assertStatus(500)
        ->assertJsonFragment([
            'error_message' => config('const.Message.DELETE_ERR')
        ]);

        // 削除エラーとなったデータは引き続きDBに存在することを確認
        $this->assertDatabaseHas('message_histories', [
            'id'                => $this->message2->id,
            'content'           => $this->message2->content,
            'own_id'            => $this->message2->own_id,
            'user_id'           => $this->message2->user_id,
            'update_user_id'    => $this->message2->update_user_id
        ]);

        // 自身のメッセージを削除する場合は正常動作を実行
        $response = $this->delete('api/users/'.$this->admin->id.'/messages/'.$this->message1->id);

        $response->assertOk()
        ->assertJsonFragment([
            'info_message' => config('const.Message.DELETE_INFO'),
        ]);

        // 削除されたデータがDBにないことを確認
        $this->assertDatabaseMissing('message_histories', [
            'id'                => $this->message1->id,
            'content'           => $this->message1->content,
            'own_id'            => $this->message1->own_id,
            'user_id'           => $this->message1->user_id,
            'update_user_id'    => $this->message1->update_user_id,
            'created_at'        => $this->message1->created_at,
            'updated_at'        => $this->message1->updated_at,
            'deleted_at'        => $this->message1->deleted_at
        ]);
    }
}
