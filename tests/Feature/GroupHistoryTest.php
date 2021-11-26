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
use App\Models\News;

class GroupHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $group;
    protected $history;

    private const DIS = 'グループ申請の動作を確認するテスト用グループです！';

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
            'name'              => 'GroupHistoryTest',
            'description'       => self::DIS,
            'private_flg'       => config('const.Group.PUBLIC'),
            'host_user_id'      => $this->admin->id,
            'update_user_id'    => $this->admin->id,
        ]);
        // グループ参加履歴の作成
        GroupHistory::create([
            'user_id'              => $this->admin->id,
            'group_id'             => $this->group->id,
            'status'               => config('const.GroupHistory.APPROVAL'),
            'update_user_id'       => $this->admin->id
        ]);
        $this->history = GroupHistory::create([
            'user_id'              => $this->user->id,
            'group_id'             => $this->group->id,
            'status'               => config('const.GroupHistory.APPLY'),
            'update_user_id'       => $this->user->id
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
    public function api_historyにGETメソッドでアクセス()
    {
        // 認証前
        $response = $this->get('/api/history');

        $response->assertStatus(302);

        // 認証後(グループ申請中ユーザ情報をメインで取得)
        $this->getActingAs($this->admin);

        $response = $this->get('/api/history?group_id='.$this->group->id.'&status='.config('const.GroupHistory.APPLY').'&sort_created_at=desc');

        $response->assertOk()
        ->assertJsonFragment([
            'user_id'       => $this->user->id,
            'group_id'      => $this->group->id,
            'name'          => $this->user->name
        ]);

        // 認証後(グループ申請中と申請済みグループ情報をメインで取得)
        $this->getActingAs($this->admin);

        $response = $this->get('/api/history?@not_equalstatus='.config('const.GroupHistory.REJECT'));

        $response->assertOk()
        ->assertJsonFragment([
            'user_id'       => $this->admin->id,
            'group_id'      => $this->group->id,
            'name'          => $this->group->name
        ])
        ->assertJsonMissing([
            'user_id'       => $this->user->id,
            'name'          => $this->user->name
        ]);
    }

    /**
     * @test
     */
    public function グループ履歴作成の動作を確認()
    {
        // グループの作成
        $group = Group::create([
            'name'              => 'RedRock',
            'description'       => '神戸駅の友達作りランチ会！',
            'host_user_id'      => $this->admin->id,
            'update_user_id'    => $this->admin->id,
        ]);
        // グループ参加履歴の作成
        GroupHistory::create([
            'user_id'              => $this->admin->id,
            'group_id'             => $group->id,
            'status'               => config('const.GroupHistory.APPROVAL'),
            'update_user_id'       => $this->admin->id
        ]);


        /***************************************
         * 作成失敗時
         ***************************************/
        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        // 履歴の作成(失敗例: 既存データが存在するのに、新規作成を実行しようとした場合)
        $data = [
            'user_id'              => $this->admin->id,
            'group_id'             => $group->id,
            'status'               => config('const.GroupHistory.APPLY'),
        ];
        
        $response = $this->post('api/groups/'.$group->id.'/history', $data);

        // 申請用のエラーメッセージを返す
        $response->assertStatus(500)
        ->assertJsonFragment([
            'error_message' => config('const.GroupHistory.APPLY_ERR')
        ]);

        // 招待時にエラーが発生した場合は招待時用のエラーメッセージを返す
        $data['status'] = config('const.GroupHistory.APPROVAL');
        
        $response = $this->post('api/groups/'.$group->id.'/history', $data);

        $response->assertStatus(500)
        ->assertJsonFragment([
            'error_message' => config('const.GroupHistory.INVITE_ERR')
        ]);


        /***************************************
         * 作成成功時
         ***************************************/
        // ユーザを認証済みに書き換え
        $this->getActingAs($this->user);
        // 申請時の履歴の作成(成功)
        $data = [
            'group_id'             => $group->id,
            'status'               => config('const.GroupHistory.APPLY'),
        ];
        
        $response = $this->post('api/groups/'.$group->id.'/history', $data);

        $response->assertStatus(200)
        ->assertJsonFragment([
            'info_message' => config('const.GroupHistory.APPLY_INFO'),
            'id'           => $group->id,
            'name'         => $group->name,
            'id'           => $this->user->id,
            'name'         => $this->user->name,
            'user_id'      => $this->user->id
        ]);

        // 参加申請後、ユーザ宛に申請完了通知が保存されているかどうかを確認
        $title = $group->name.'の参加申請について';
        $content = $group->name.'の参加申請が完了しました。申請の結果が出るまでお待ちください。';
        $this->assertDatabaseHas('news', [
            'user_id'           => $this->user->id,
            'title'             => $title,
            'content'           => $content,
            'update_user_id'    => $this->user->id,
        ]);
        // 未読テーブルの保存確認
        $news = News::where('user_id', $this->user->id)->where('title', $title)->where('content', $content)->first();
        $this->assertDatabaseHas('nread_managements', [
            'news_user_id'      => $this->user->id,
            'news_id'           => $news->news_id,
            'user_id'           => $this->user->id,
        ]);

        // // 招待時の履歴の作成(成功)
        // $user = User::factory()->create();
        // $data = [
        //     'user_id'              => $user->id,
        //     'group_id'             => $this->group->id,
        //     'status'               => config('const.GroupHistory.APPROVAL'),
        // ];
        
        // $response = $this->post('api/groups/'.$group->id.'/history', $data);

        // $response->assertStatus(200)
        // ->assertJsonFragment([
        //     'info_message' => config('const.GroupHistory.INVITE_INFO'),
        //     'id'           => $this->group->id,
        //     'name'         => $this->group->name
        // ]);

        // // 参加申請後、ユーザ宛に申請完了通知が保存されているかどうかを確認
        // $title = $this->group->name.'の参加申請について';
        // $content = $this->group->name.'の参加が承認されました。Home画面の参加グループ一覧よりご確認ください。';
        // $this->assertDatabaseHas('news', [
        //     'user_id'           => $user->id,
        //     'title'             => $title,
        //     'content'           => $content,
        //     'update_user_id'    => $this->admin->id,
        // ]);
        // // 未読テーブルの保存確認
        // $news = News::where('user_id', $user->id)->where('title', $title)->where('content', $content)->first();
        // $this->assertDatabaseHas('nread_managements', [
        //     'news_user_id'      => $user->id,
        //     'news_id'           => $news->news_id,
        //     'user_id'           => $user->id,
        // ]);
    }

    /**
     * @test
     */
    public function グループ履歴更新の動作を確認()
    {
        /***************************************
         * 更新失敗時
         ***************************************/
        // ホストユーザではないユーザを認証済みに書き換え
        $this->getActingAs($this->user);

        // GroupHistoryモデルがPivotを継承しているため、createメソッドでデータを作成してもIDが発行されない → DBから取得する必要がある
        $history = GroupHistory::where('user_id', $this->user->id)->where('group_id', $this->group->id)->first();
        
        $data = [
            'id'        => $history->id,
            'group_id'  => $this->group->id,
            'user_id'   => $this->user->id,
            'status'    => config('const.GroupHistory.APPROVAL')
        ];

        $response = $this->put('api/groups/'.$this->group->id.'/history/'.$history->id, $data);

        // 承認用のエラーメッセージを返す
        $response->assertStatus(500)
        ->assertJsonFragment([
            'error_message' => config('const.GroupHistory.APPROVAL_ERR')
        ]);


        /***************************************
         * 更新成功時(承認)
         ***************************************/
        // ホストユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        $response = $this->put('api/groups/'.$this->group->id.'/history/'.$history->id, $data);

        $response->assertOk()
        ->assertJsonFragment([
            'info_message' => config('const.GroupHistory.APPROVAL_INFO')
        ]);

        // 承認後、承認されたユーザ宛に承認通知が保存されているかどうかを確認
        $title = $this->group->name.'の参加申請について';
        $content = $this->group->name.'の参加が承認されました。Home画面の参加グループ一覧よりご確認ください。';
        $this->assertDatabaseHas('news', [
            'user_id'           => $this->user->id,
            'title'             => $title,
            'content'           => $content,
            'update_user_id'    => $this->admin->id,
        ]);
        // 未読テーブルの保存確認
        $news = News::where('user_id', $this->user->id)->where('title', $title)->where('content', $content)->first();
        $this->assertDatabaseHas('nread_managements', [
            'news_user_id'      => $this->user->id,
            'news_id'           => $news->news_id,
            'user_id'           => $this->user->id,
        ]);
    }
}
