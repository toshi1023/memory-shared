<?php

namespace Tests\Feature;

ini_set("memory_limit", "256M");

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

class UserTest extends TestCase
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
        // $this->artisan('db:seed', ['--class' => 'UsersTableSeeder']);

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
    public function login処理を実行()
    {
        // 作成したテストユーザのemailとpasswordで認証リクエスト
        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => 'test1234',
            'status'   => config('const.User.MEMBER')
        ]);

        // 正しいレスポンスが返り、ユーザ名が取得できることを確認
        $response
            ->assertStatus(200)
            ->assertJson([
                'user' => ['name' => $this->user->name],
                'info_message' => config('const.SystemMessage.LOGIN_INFO')
            ]);

        // 指定したユーザーが認証されていることを確認
        $this->assertAuthenticatedAs($this->user);
    }

    /**
     * @test
     */
    public function loginに失敗_statusに権限がない場合()
    {
        // 作成したテストユーザのemailとpasswordで認証リクエスト
        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => 'test1234',
            'status'   => config('const.User.STOP')
        ]);

        // 権限がない旨のエラーメッセージを取得できることを確認
        $response
            ->assertStatus(401)
            ->assertJson(["error_message" => config('const.SystemMessage.UNAUTHORIZATION')]);
    }

    /**
     * @test
     */
    public function loginに失敗_認証に失敗した場合()
    {
        // 作成したテストユーザのemailとpasswordで認証リクエスト
        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => 'error',
            'status'   => config('const.User.MEMBER')
        ]);

        // 認証失敗のエラーメッセージを取得できることを確認
        $response
            ->assertStatus(401)
            ->assertJson(["error_message" => config('const.SystemMessage.LOGIN_ERR')]);
    }

    /**
     * @test
     */
    public function api_usersにGETメソッドでアクセス()
    {
        // 認証前
        $response = $this->get('api/users');
        // リダイレクトが発生する
        $response->assertStatus(302);

        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        $response = $this->get('api/users');

        $response->assertOk()
        ->assertJsonFragment([
            'status' => config('const.User.ADMIN'),
        ]);
    }

    /**
     * @test
     */
    public function showアクションにGETメソッドでアクセス()
    {
        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        $response = $this->get('api/users/'.$this->admin->name);

        $response->assertOk()
        ->assertJsonFragment([
            'status' => config('const.User.ADMIN'),
        ]);
    }
    
    /**
     * @test
     */
    public function softDeleteの動作を確認()
    {
        // ユーザの生成
        $user = $this->getActingAs(User::factory()->create());
        User::factory()->create();
        
        // 論理削除
        $user->delete();

        // 論理削除済みのユーザを取得
        $user = User::onlyTrashed()->whereNotNull('id')->get();

        // 論理削除されていないユーザをすべて取得
        $user2 = User::all();

        // 論理削除済みのユーザも含めたユーザ全体を取得
        $allUser = User::withTrashed()->whereNotNull('id')->get();

        // ソフトデリートの判別カラムに値がある場合
        foreach ($user2 as $key => $value) {
            if($key == 'deleted_at' && !$value) {
                throw new Exception('softDelete does not work');
            }
        }
        // 論理削除フラグがtrueで、かつ判別カラムの値が事前に取得した論理削除済みユーザと一致しない場合
        foreach ($allUser->toArray() as $key => $value) {
            if($value['deleted_at'] && $value['deleted_at'] !== $user->toArray()[0]['deleted_at']) {
                throw new Exception('softDelete does not work');
            }
        }
    }

    /**
     * @test
     */
    public function 検索付きのクエリ動作を確認()
    {
        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        // 一覧ページ用の正常な検索動作を確認
        $response = $this->get('api/users?name@like='.$this->admin->name.'&status='.$this->admin->status);
        // $response = $this->get('api/users?email='.$this->admin->email.'&status='.$this->admin->status);

        $response->assertOk()
        ->assertJsonFragment([
            'status' => config('const.User.ADMIN'),
        ]);

        // 一覧ページ用の検索動作の失敗を確認
        $response = $this->get('api/users?name@like='.$this->admin->name.'&status='.config('const.User.MEMBER'));
        $response->assertOk()
        ->assertJsonMissing([
            'status' => config('const.User.ADMIN'),
        ]);

        // 詳細ページ用のデータ取得を確認
        $response = $this->get('api/users/root');

        $response->assertOk()
        ->assertJsonFragment([
            'status' => config('const.User.ADMIN'),
        ]);
    }

    /**
     * @test
     */
    public function friendsの動作を確認()
    {
        // ユーザを認証済みに書き換え
        $this->getActingAs($this->admin);

        // グループ参加履歴の作成
        GroupHistory::create([
            'user_id'              => $this->admin->id,
            'group_id'             => $this->group->id,
            'status'               => config('const.GroupHistory.APPROVAL'),
            'update_user_id'       => $this->admin->id
        ]);
        GroupHistory::create([
            'user_id'              => $this->user->id,
            'group_id'             => $this->group->id,
            'status'               => config('const.GroupHistory.APPROVAL'),
            'update_user_id'       => $this->user->id
        ]);

        // 一覧ページ用の正常な検索動作を確認
        $response = $this->get('api/users/'.$this->admin->name.'/friends');

        $response->assertOk()
        ->assertJsonFragment([
            'name' => $this->user->name,
        ]);
    }

    /**
     * @test
     */
    public function logoutの動作を確認()
    {
        // ユーザを認証済みに書き換え
        $response = $this->actingAs($this->admin)->json('POST', route('logout'), [
            'id' => $this->admin->id
        ]);
        
        // 正しいレスポンスが返ることを確認
        $response
            ->assertStatus(200)
            ->assertJson([
                'info_message' => config('const.SystemMessage.LOGOUT_INFO')
            ]);
        $this->assertGuest();
    }
}
