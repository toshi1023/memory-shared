<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\News;
use App\Models\MessageHistory;
use App\Models\MreadManagement;
use App\Models\NreadManagement;

class ReadManagementsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $news1;
    protected $news2;
    protected $message1;
    protected $message2;
    protected $mread;
    protected $nread;

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

    private const TITLE = 'うれしいニュースです！';
    private const CONTENT = 'グループ登録数が100を超えました！いつもご利用ありがとうございます。';
    private const ERRMESSAGE1 = 'ニュースを作成するには管理者権限が必要です';
    private const ERRMESSAGE2 = 'ワンタイムパスワード は必須です';
    private const ERRMESSAGE3 = 'ワンタイムパスワードが一致しません';

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
                'onetime_password'  => 'A123B456C789',
            ]);

        // 認証済みでないユーザの作成
        $this->user = User::factory()->create();

        // ニュースの作成
        $this->news1 = News::create([
            'user_id'           => 0,
            'news_id'           => 1,
            'title'             => '本日よりオープン！',
            'content'           => 'プライベートな画像・動画共有サイトをオープンしました！',
            'update_user_id'    => $this->admin->id
        ]);
        $this->news2 = News::create([
            'user_id'           => $this->admin->id,
            'news_id'           => 1,
            'title'             => '不具合を対応しました。',
            'content'           => 'ログインが出来ない不具合を対応しました。ご迷惑をおかけしたこと、大変深くお詫び申し上げます。',
            'update_user_id'    => $this->admin->id
        ]);

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

        // ニュースの未読データを生成
        $this->nread = NreadManagement::create([
            'news_user_id'      => $this->admin->id,
            'news_id'           => $this->news2->news_id,
            'user_id'           => $this->admin->id,
        ]);
        // メッセージの未読データを生成
        $this->nread = MreadManagement::create([
            'message_id'        => $this->message2->id,
            'own_id'            => $this->user->id,
            'user_id'           => $this->admin->id,
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
    public function api_nreadにGETメソッドでアクセス()
    {
        // 認証前
        $response = $this->get('/api/nread');

        $response->assertStatus(302);

        // 認証後(user) ニュース未読数を確認
        $this->getActingAs($this->user);

        $response = $this->get('/api/nread');

        $response->assertOk()
        ->assertJsonFragment([
            'nread_count'       => 0,
        ]);

        // 認証後(admin) ニュース未読数を確認
        $this->getActingAs($this->admin);

        $response = $this->get('/api/nread');

        $response->assertOk()
        ->assertJsonFragment([
            'nread_count'       => 1,
        ]);

        // ニュースを新規生成
        $data = [
            'user_id'           => 0,
            'title'             => self::TITLE,
            'content'           => self::CONTENT,
            'update_user_id'    => $this->admin->id,
            'onetime_password'  => $this->admin->onetime_password
        ];
        
        $response = $this->post('api/news', $data);

        $response->assertOk()
        ->assertJsonFragment([
            'info_message' => config('const.News.REGISTER_INFO')
        ]);

        // adminのニュース未読数を確認
        $response = $this->get('/api/nread');

        $response->assertOk()
        ->assertJsonFragment([
            'nread_count'       => 2,
        ]);

        // userのニュース未読数を確認
        $this->getActingAs($this->user);

        $response = $this->get('/api/nread');

        $response->assertOk()
        ->assertJsonFragment([
            'nread_count'       => 1,
        ]);
    }

    /**
     * @test
     */
    public function ニュースの未読データを削除()
    {
        // 認証前
        $response = $this->post('api/news/'.$this->news2->news_id.'/nread?news_user_id='.$this->admin->id.'&user_id='.$this->admin->id);

        $response->assertStatus(302);

        // 認証後(admin)
        $this->getActingAs($this->admin);

        // 現在のニュース未読数を確認
        $response = $this->get('/api/nread');

        $response->assertOk()
        ->assertJsonFragment([
            'nread_count'       => 1,
        ]);

        // ニュース未読データの削除
        $response = $this->post('api/news/'.$this->news2->news_id.'/nread?news_user_id='.$this->admin->id.'&user_id='.$this->admin->id);

        $response->assertOk()
        ->assertJsonFragment([
            'user_id'           => $this->admin->id,
            'news_id'           => 1,
            'title'             => '不具合を対応しました。',
            'content'           => 'ログインが出来ない不具合を対応しました。ご迷惑をおかけしたこと、大変深くお詫び申し上げます。',
            'update_user_id'    => $this->admin->id
        ]);

        // 現在のニュース未読数を確認
        $response = $this->get('/api/nread');

        $response->assertOk()
        ->assertJsonFragment([
            'nread_count'       => 0,
        ]);

        // 削除されたデータがDBにないことを確認
        $this->assertDatabaseMissing('nread_managements', [
            'news_user_id'      => $this->admin->id,
            'news_id'           => $this->news2->news_id,
            'user_id'           => $this->admin->id,
        ]);
    }

    /**
     * @test
     */
    public function ログインユーザのトークリストにある未読数を確認()
    {
        // 認証後(admin) メッセージ未読数を確認
        $this->getActingAs($this->admin);

        $response = $this->get('/api/users/'.$this->admin->id.'/messagelists');

        $response->assertOk()
        ->assertJsonFragment([
            'own_id'       => $this->user->id,
            'user_id'      => $this->admin->id,
            'mcount'       => 1,
        ]);
    }

    /**
     * @test
     */
    public function メッセージの未読データを削除()
    {
        // 認証前
        $response = $this->post('api/users/'.$this->user->id.'/mread');

        $response->assertStatus(302);

        // 認証後(admin)
        $this->getActingAs($this->admin);

        // 現在のメッセージ未読数を確認
        $response = $this->get('/api/users/'.$this->admin->id.'/messagelists');

        $response->assertOk()
        ->assertJsonFragment([
            'own_id'       => $this->user->id,
            'user_id'      => $this->admin->id,
            'mcount'       => 1,
        ]);

        // メッセージ未読データの削除
        $response = $this->post('api/users/'.$this->user->id.'/mread');

        $response->assertOk();

        // 現在のメッセージ未読数を確認
        $response = $this->get('/api/users/'.$this->admin->id.'/messagelists');

        $response->assertOk()
        ->assertJsonFragment([
            'own_id'       => $this->user->id,
            'user_id'      => $this->admin->id,
            'mcount'       => null,
        ]);

        // 削除されたデータがDBにないことを確認
        $this->assertDatabaseMissing('mread_managements', [
            'message_id'        => $this->message2->id,
            'own_id'            => $this->user->id,
            'user_id'           => $this->admin->id,
        ]);
    }
}
