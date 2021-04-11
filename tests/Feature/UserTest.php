<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    /**
     * テストの前処理を実行
     */
    public function setUp(): void{
        parent::setUp();
        // $this->artisan('db:seed', ['--class' => 'UsersTableSeeder']);

        // 認証済みユーザの作成
        $this->admin = 
        Sanctum::actingAs(
            User::create([
                'name'              => 'root',
                'email'             => 'root@xxx.co.jp',
                'email_verified_at' => now(),
                'password'          => Hash::make('root1234'),
                'status'            => config('const.User.ADMIN'),
                'user_agent'        => '',
                'remember_token'    => Str::random(10),
                'update_user_id'    => 1,
            ]),
            ['*']
        );
        // Sanctum::actingAs(
        //     User::factory()->create(),
        //     ['*']
        // );
    }

    /**
     * @test
     */
    public function login処理を実行()
    {
        // 作成したテストユーザのemailとpasswordで認証リクエスト
        $response = $this->json('POST', route('login'), [
            'email' => $this->admin->email,
            'password' => 'root1234',
            'status'   => config('const.User.ADMIN')
        ]);

        // 正しいレスポンスが返り、ユーザ名が取得できることを確認
        $response
            ->assertStatus(200)
            ->assertJson(['name' => $this->admin->name]);

        // 指定したユーザーが認証されていることを確認
        $this->assertAuthenticatedAs($this->admin);
    }

    /**
     * @test
     */
    public function loginに失敗_statusに権限がない場合()
    {
        // 作成したテストユーザのemailとpasswordで認証リクエスト
        $response = $this->json('POST', route('login'), [
            'email' => $this->admin->email,
            'password' => 'root1234',
            'status'   => config('const.User.STOP')
        ]);

        // 権限がない旨のエラーメッセージを取得できることを確認
        $response
            ->assertStatus(401)
            ->assertJson(["message:" => config('const.SystemMessage.UNAUTHORIZATION')]);
    }

    /**
     * @test
     */
    public function loginに失敗_認証に失敗した場合()
    {
        // 作成したテストユーザのemailとpasswordで認証リクエスト
        $response = $this->json('POST', route('login'), [
            'email' => $this->admin->email,
            'password' => 'error',
            'status'   => config('const.User.MEMBER')
        ]);

        // 認証失敗のエラーメッセージを取得できることを確認
        $response
            ->assertStatus(401)
            ->assertJson(["message:" => config('const.SystemMessage.LOGIN_ERR')]);
    }

    /**
     * @test
     */
    public function api_usersにGETメソッドでアクセス()
    {
        $response = $this->get('api/users');

        $response->assertOk()
        ->assertJsonFragment([
            'status' => config('const.User.ADMIN'),
        ]);
    }
    
}
