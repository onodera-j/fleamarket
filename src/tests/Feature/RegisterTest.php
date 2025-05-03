<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Validation\Rule;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_名前必須バリデーション()
    {
        $response = $this->post('/register',[
            'name' => '',
            'email' => 'test@example.com',
            'password' => '00000000',
            'password_confirmation' => '00000000',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $response->assertSessionHaserrors(['name' => 'お名前を入力してください']);
    }

    public function test_register_メールアドレス必須バリデーション()
    {
        $response = $this->post('/register',[
            'name' => 'name',
            'email' => '',
            'password' => '00000000',
            'password_confirmation' => '00000000',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
        $response->assertSessionHaserrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_register_パスワード必須バリデーション()
    {
        $response = $this->post('/register',[
            'name' => 'name',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '00000000',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
        $response->assertSessionHaserrors(['password' => 'パスワードを入力してください']);
    }

    public function test_register_パスワード7文字以下バリデーション()
    {
        $response = $this->post('/register',[
            'name' => 'name',
            'email' => 'test@example.com',
            'password' => '0000000',
            'password_confirmation' => '0000000',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
        $response->assertSessionHaserrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    public function test_register_パスワード不一致バリデーション()
    {
        $response = $this->post('/register',[
            'name' => 'name',
            'email' => 'test@example.com',
            'password' => '00000000',
            'password_confirmation' => '00000001',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
        $response->assertSessionHaserrors(['password' => 'パスワードと一致しません']);
    }

    public function test_register_正常に登録()
    {
        $userData = [
            'name' => 'name',
            'email' => 'test@example.com',
            'password' => '00000000',
            'password_confirmation' => '00000000',
        ];

        $response = $this->post('/register', $userData);

        $this->assertDatabaseHas('users',[
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');

    }
}

