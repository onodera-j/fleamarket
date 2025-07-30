<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login_メールアドレス必須バリデーション()
    {
        //【準備】ユーザー登録
        $user = User::create([
            'name' => 'サンプル',
            'email' => 'test@example.com',
            'password' => '00000000',
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login',[
            'email' => '',
            'password' => '00000000',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
        $response->assertSessionHaserrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_login_パスワード必須バリデーション()
    {
        //【準備】ユーザー登録
        $user = User::create([
            'name' => 'サンプル',
            'email' => 'test@example.com',
            'password' => '00000000',
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login',[
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
        $response->assertSessionHaserrors(['password' => 'パスワードを入力してください']);
    }

    public function test_login_ユーザー不一致バリデーション()
    {
        //【準備】ユーザー登録
        $user = User::create([
            'name' => 'サンプル',
            'email' => 'test@example.com',
            'password' => '00000000',
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login',[
            'email' => 'testtest@example.com',
            'password' => '00000000',
        ]);

        $response->assertStatus(302);

        $errors = session('errors');
        $this->assertEquals('ログイン情報が登録されていません',$errors->first());
    }

    public function test_login_ログイン()
    {
        //【準備】ユーザー登録
        $user = User::create([
            'name' => 'サンプル',
            'email' => 'testtest@example.com',
            'password' => Hash::make('00000000'),
            'email_verified_at' => now(),
        ]);

        $address = Address::create([
            'user_id' => $user->id,
            'post_code' => '1234567',
            'address' => '東京都渋谷区',
            'building' => '宇田川町',
        ]);

        $response = $this->post('/login',[
            'email' => 'testtest@example.com',
            'password' => '00000000',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertAuthenticated();

    }

}
