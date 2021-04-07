<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * テストの前処理を実行
     */
    public function setUp(): void{
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'UsersTableSeeder']);
    }

    /**
     * @test
     */
    public function api_usersにGETメソッドでアクセス()
    {
        $response = $this->get('api/users');

        $response->assertStatus(200);
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
}
