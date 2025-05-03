<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Address;
use App\Models\Item;
use App\Models\Transaction;

class ProfileTest extends TestCase
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
    public function test_profiletest_ユーザー情報取得()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $address_register = Address::create([
            'user_id' => $user->id,
            'post_code' => '1234567',
            'address' => '東京都渋谷区',
            'building' => '宇田川町',
        ]);

        $transaction = Transaction::create([
            'item_id' => 9,
            'purchaser_id' => 1,
            'post_code' => '000-0000',
            'address' => 'test',
            'building' => 'texttext',
            'payment_method' => 1,
        ]);

        $response = $this->get('/mypage/mypage');
        $response->assertStatus(200);

        $response->assertViewHas('sellItems');
        $sellItems = $response->viewData('sellItems');
        $response->assertViewHas('user');
        $userData = $response->viewData('user');

        $this->assertEquals($user->name,$userData->name);
        $this->assertEquals($user->profile_image,$userData->profile_image);
        $this->assertCount(2,$sellItems);

        $response = $this->get('/mypage/mypage?tab=buy');
        $response->assertStatus(200);

        $response->assertViewHas('buyItems');
        $buyItems = $response->viewData('buyItems');
        $this->assertCount(1,$buyItems);
        $response->assertSee($buyItems->first()->item->item_name);

    }
}
