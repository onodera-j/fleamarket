<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Address;

class ProfileupdateTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_profileupdate_ユーザー情報変更()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $address_register = Address::create([
            'user_id' => $user->id,
            'post_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => '宇田川町',
        ]);

        $address_update = [
            'name' => '更新',
            'post_code' => '987-6543',
            'address' => '神奈川県川崎市',
            'building' => '多摩川',
            'user_id' => $user->id,
        ];

        $response = $this->get('/mypage/profile');
        $response->assertStatus(200);

        $response->assertViewHas('user');
        $userData = $response->viewData('user');
        $response->assertViewHas('address');
        $addressData = $response->viewData('address');
        $response->assertViewHas('profileImageUrl');
        $profileImageUrl = $response->viewData('profileImageUrl');

        $this->assertEquals($user->name,$userData->name);
        $this->assertEquals($address_register->post_code,$addressData->post_code);
        $this->assertEquals($address_register->address,$addressData->address);
        $this->assertEquals($address_register->building,$addressData->building);
        $response->assertSee($profileImageUrl);

        $response = $this->post('/address',$address_update);
        $response->assertRedirect('/mypage/profile');

        $userupdate = User::find(1);

        $response = $this->get('/mypage/profile');
        $response->assertViewHas('user');
        $userData = $response->viewData('user');
        $response->assertViewHas('address');
        $addressData = $response->viewData('address');
        $response->assertViewHas('profileImageUrl');
        $profileImageUrl = $response->viewData('profileImageUrl');

        $this->assertEquals($userupdate->name,$userData->name);
        $this->assertEquals($address_update['post_code'],$addressData->post_code);
        $this->assertEquals($address_update['address'],$addressData->address);
        $this->assertEquals($address_update['building'],$addressData->building);
        $response->assertSee($profileImageUrl);

    }
}
