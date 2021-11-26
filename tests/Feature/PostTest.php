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
use App\Models\Post;
use App\Models\PostComment;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $group;
    protected $post1;
    protected $post2;
    protected $comment1;
    protected $comment2;

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
        GroupHistory::create([
            'user_id'       => $this->user->id,
            'group_id'      => $this->group->id,
            'status'        => config('const.GroupHistory.APPROVAL')
        ]);

        // 投稿の作成
        $this->post1 = Post::create([
            'content'           => '8月31日にBBQしましょう！',
            'user_id'           => $this->admin->id,
            'group_id'          => $this->group->id,
            'update_user_id'    => $this->admin->id,
        ]);
        $this->post2 = Post::create([
            'content'           => '今度の日曜日暇な人ー！',
            'user_id'           => $this->user->id,
            'group_id'          => $this->group->id,
            'update_user_id'    => $this->user->id,
        ]);

        // コメントの作成
        $this->comment1 = PostComment::create([
            'content'           => '楽しみー！',
            'user_id'           => $this->user->id,
            'post_id'           => $this->post1->id,
            'update_user_id'    => $this->user->id,
        ]);
        $this->comment2 = PostComment::create([
            'content'           => 'BBQ楽しかったねー！',
            'user_id'           => $this->user->id,
            'post_id'           => $this->post1->id,
            'update_user_id'    => $this->user->id,
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
    public function api_groups_postsにGETメソッドでアクセス()
    {
        // 認証前
        $response = $this->get('/api/groups/'.$this->group->id.'/posts');

        $response->assertStatus(302);

        // 認証後
        $this->getActingAs($this->admin);

        $response = $this->get('/api/groups/'.$this->group->id.'/posts');

        $response->assertOk()
        ->assertJsonFragment([
            'content'      => '今度の日曜日暇な人ー！',
            'user_id'      => $this->admin->id,
        ]);

        // グループに加盟していないユーザからのリクエスト時
        $user = User::factory()->create();
        $this->getActingAs($user);

        $response = $this->get('/api/groups/'.$this->group->id.'/posts');

        $response->assertStatus(500)
        ->assertJsonFragment([
            'error_message' => config('const.Post.GET_ERR')
        ]);
    }

    /**
     * @test
     */
    public function 投稿作成の動作を確認()
    {
        // 作成データ
        $data = [
            'content'           => 'グループ登録数が100を祝って、祝賀会やろう！',
            'user_id'           => $this->admin->id,
            'group_id'          => $this->group->id,
            'update_user_id'    => $this->admin->id,
        ];

        // グループに加盟していないユーザからのリクエスト時
        $user = User::factory()->create();
        $this->getActingAs($user);

        $response = $this->post('/api/groups/'.$this->group->id.'/posts', $data);

        $response->assertStatus(500)
        ->assertJsonFragment([
            'error_message' => config('const.Post.REGISTER_ERR')
        ]);

        // グループに加盟済みのユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        $response = $this->post('/api/groups/'.$this->group->id.'/posts', $data);

        $response->assertOk()
                 ->assertJsonFragment([
                    'info_message' => config('const.Post.REGISTER_INFO')
                 ]);

        // 登録されたデータが一覧データにて確認できるかを確認
        $response = $this->get('/api/groups/'.$this->group->id.'/posts');

        $response->assertOk()
        ->assertJsonFragment([
            'content'      => 'グループ登録数が100を祝って、祝賀会やろう！',
            'user_id'      => $this->admin->id,
        ]);

        // 投稿後、投稿ユーザ宛に投稿完了通知が保存されているかどうかを確認
        $title = $this->group->name.'の掲示板が新規投稿されました';
        $content = $this->admin->name.'さんが'.$this->group->name.'の掲示板に新たな投稿を追加しました。掲示板にて内容を確認することが出来ます';
        $this->assertDatabaseHas('news', [
            'user_id'           => $this->admin->id,
            'title'             => $title,
            'content'           => $content,
            'update_user_id'    => $this->admin->id,
        ]);

        // 投稿後、グループに加盟しているユーザ宛にも投稿完了通知が保存されているかどうかを確認
        $this->assertDatabaseHas('news', [
            'user_id'           => $this->user->id,
            'title'             => $title,
            'content'           => $content,
            'update_user_id'    => $this->admin->id,
        ]);
    }

    /**
     * @test
     */
    public function api_groups_posts_commentsにGETメソッドでアクセス()
    {
        // 認証前
        $response = $this->get('/api/groups/'.$this->group->id.'/posts/'.$this->post1->id.'/comments');

        $response->assertStatus(302);

        // 認証後
        $this->getActingAs($this->admin);

        $response = $this->get('/api/groups/'.$this->group->id.'/posts/'.$this->post1->id.'/comments');

        $response->assertOk()
        ->assertJsonFragment([
            'content'      => 'BBQ楽しかったねー！',
            'user_id'      => $this->user->id,
        ]);

        // グループに加盟していないユーザからのリクエスト時
        $user = User::factory()->create();
        $this->getActingAs($user);

        $response = $this->get('/api/groups/'.$this->group->id.'/posts/'.$this->post1->id.'/comments');

        $response->assertStatus(500)
        ->assertJsonFragment([
            'error_message' => config('const.PostComment.GET_ERR')
        ]);
    }

    /**
     * @test
     */
    public function 投稿削除の動作を確認()
    {
        // グループに加盟済みのユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        // 作成者本人の投稿を削除する場合
        $response = $this->delete('/api/groups/'.$this->group->id.'/posts/'.$this->post1->id);
        
        $response->assertOk()
                 ->assertJsonFragment([
                    'info_message' => config('const.Post.DELETE_INFO')
                 ]);

        // 作成者本人じゃない投稿を削除する場合
        $response = $this->delete('/api/groups/'.$this->group->id.'/posts/'.$this->post2->id);

        $response->assertStatus(500)
        ->assertJsonFragment([
            'error_message' => config('const.Post.DELETE_ERR')
        ]);

        // 削除されたデータがDBにないことを確認
        $this->assertDatabaseMissing('posts', [
            'id'                => $this->post1->id,
            'content'           => '8月31日にBBQしましょう！',
            'user_id'           => $this->admin->id,
            'group_id'          => $this->group->id,
            'update_user_id'    => $this->admin->id,
        ]);
        // 削除した投稿に紐づくコメントも削除されていることを確認
        $this->assertDatabaseMissing('post_comments', [
            'id'                => $this->comment1->id,
            'content'           => '楽しみー！',
            'user_id'           => $this->user->id,
            'post_id'           => $this->post1->id,
            'update_user_id'    => $this->user->id,
        ]);
        $this->assertDatabaseMissing('post_comments', [
            'id'                => $this->comment1->id,
            'content'           => 'BBQ楽しかったねー！',
            'user_id'           => $this->user->id,
            'post_id'           => $this->post1->id,
            'update_user_id'    => $this->user->id,
        ]);
    }
}
