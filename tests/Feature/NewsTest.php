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

class NewsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $news1;
    protected $news2;

    private const TITLE = 'うれしいニュースです！';
    private const CONTENT = 'グループ登録数が100を超えました！いつもご利用ありがとうございます。';
    private const ERRMESSAGE = 'ニュースを作成するには管理者権限が必要です';

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
    public function api_newsにGETメソッドでアクセス()
    {
        // 認証前
        $response = $this->get('/api/news');

        $response->assertStatus(302);

        // 認証後(admin)
        $this->getActingAs($this->admin);

        $response = $this->get('/api/news');

        $response->assertOk()
        ->assertJsonFragment([
            'title'             => $this->news1->title,
            'content'           => $this->news1->content,
            'update_user_id'    => $this->admin->id
        ]);

        // 認証後(user)
        $this->getActingAs($this->user);

        $response = $this->get('/api/news');

        $response->assertOk()
        ->assertJsonFragment([
            'title'             => $this->news1->title,
            'content'           => $this->news1->content,
            'update_user_id'    => $this->admin->id
        ]);
    }

    /**
     * @test
     */
    public function showアクションにGETメソッドでアクセス()
    {
        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        $response = $this->get('api/news/'.$this->news1->id);

        // 検索対象であるニュースを取得していることを確認
        $response->assertOk()
        ->assertJsonFragment([
            'title'             => $this->news1->title,
            'content'           => $this->news1->content,
            'update_user_id'    => $this->admin->id
        ]);
        
        $response = $this->get('api/news/'.$this->news2->id.'?user_id='.$this->admin->id);

        // 検索対象であるニュースを取得していることを確認
        $response->assertOk()
        ->assertJsonFragment([
            'title'             => $this->news2->title,
            'content'           => $this->news2->content,
            'update_user_id'    => $this->admin->id
        ]);

        // 存在しないニュースを検索
        $response = $this->get('api/news/100');
        
        $response->assertStatus(404)
        ->assertJsonFragment([
            'error_message' => config('const.News.SEARCH_ERR')
        ]);
    }

    /**
     * @test
     */
    public function ニュース作成の動作を確認()
    {
        // データを設定
        $data = [
            'title'             => self::TITLE,
            'content'           => self::CONTENT,
            'update_user_id'    => $this->user->id,
        ];

        // ニュースを作成(ユーザが認証されていない場合)
        $response = $this->post('api/news', $data);

        $response->assertStatus(302);

        // ユーザを認証済みに書き換え
        $this->getActingAs($this->user);

        // ニュースを作成(管理者権限のない認証済みユーザの場合)
        $response = $this->post('api/news', $data);

        $response->assertStatus(400)
        ->assertJsonFragment([
            'errors' => [
                'update_user_id' => [self::ERRMESSAGE]
        ]]);

        // ニュースを作成(管理者権限のある認証済みユーザの場合)
        $this->getActingAs($this->admin);
        $data['update_user_id'] = $this->admin->id;
        
        $response = $this->post('api/news', $data);

        $response->assertOk()
        ->assertJsonFragment([
            'info_message' => config('const.News.REGISTER_INFO')
        ]);
    }

    /**
     * @test
     */
    // public function ニュース削除の動作を確認()
    // {
    //     // ユーザを認証済みに書き換え(user)
    //     $this->getActingAs($this->user);

    //     // ニュースを削除(管理者権限のない認証済みユーザの場合)
    //     $response = $this->delete('api/news/'.$this->news2->news_id.'?user_id='.$this->admin->id);

    //     $response->assertStatus(500)
    //     ->assertJsonFragment([
    //         'error_message' => config('const.News.DELETE_ERR')
    //     ]);
    //     // var_dump($this->news2);
    //     // ユーザを認証済みに書き換え(admin)
    //     $this->getActingAs($this->admin);

    //     $response = $this->delete('api/news/'.$this->news2->news_id.'?user_id='.$this->admin->id);

    //     $response->assertOk()
    //     ->assertJsonFragment([
    //         'info_message' => config('const.News.DELETE_INFO'),
    //     ]);
    // }
}
