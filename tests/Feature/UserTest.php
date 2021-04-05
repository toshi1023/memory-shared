<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function api_usersにGETメソッドでアクセス()
    {
        $response = $this->get('api/users');

        $response->assertStatus(200);
    }
}
