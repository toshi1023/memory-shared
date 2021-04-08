<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * テストの前処理を実行
     */
    public function setUp(): void{
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'UsersTableSeeder']);

        // 認証済みユーザの作成
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
    }

    /**
     * @test
     */
    public function usersテーブルにデータが入っているか確認()
    {
        $this->assertDatabaseHas('users', [
            'status' => config('const.User.MEMBER'),
        ]);
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
