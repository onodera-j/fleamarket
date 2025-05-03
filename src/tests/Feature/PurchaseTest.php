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

class PurchaseTest extends TestCase
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
    public function test_purchase_購入()
    {
        $user = User::find(1);
        $item = Item::find(2);
        $this->actingAs($user);
        $address_register = Address::create([
            'user_id' => $user->id,
            'post_code' => '1234567',
            'address' => '東京都渋谷区',
            'building' => '宇田川町',
        ]);


        $response = $this->get('/purchase/2');
        $response->assertStatus(200);

        $response->assertViewHas('address');
        $address = $response->viewData('address');

        $response= $this->post('/transaction',[
            'payment_method' => 1,
            'item_id' => 2,
            'post_code' => $address->post_code,
            'address' => $address->address,
            'building' => $address->building,
        ]);

        $this->assertDatabaseHas('transactions',[
            'item_id' => 2,
            'purchaser_id' => $user->id,
            'post_code' => $address->post_code,
            'address' => $address->address,
            'building' => $address->building,
            'payment_method' => 1,
        ]);

        $response->assertRedirect('/mypage/mypage?tab=buy');
        $response = $this->get('/mypage/mypage?tab=buy');

        $response->assertViewHas('sellItems');
        $response->assertSee($item->item_name);
        $response->assertSee('sold');
    }

    public function test_purchase_住所変更()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $address_register = Address::create([
            'user_id' => $user->id,
            'post_code' => '1234567',
            'address' => '東京都渋谷区',
            'building' => '宇田川町',
        ]);

        $address_update = [
            'post_code' => '987-6543',
            'address' => '神奈川県川崎市',
            'building' => '多摩川',
            'item_id' => 2,
        ];

        $response = $this->get('/purchase/address/2');
        $response->assertStatus(200);

        $response->assertViewHas('address');
        $address = $response->viewData('address');
        $response->assertSee($address->post_code);
        $response->assertSee($address->address);
        $response->assertSee($address->building);

        $response= $this->patch('/updateaddress',$address_update);

        $response->assertRedirect('/purchase/2');
        $response = $this->get('/purchase/2');

        $response->assertViewHas('address');
        $updatedaddress = $response->viewData('address');
        $this->assertEquals($address_update['post_code'],$updatedaddress->post_code);
        $this->assertEquals($address_update['address'],$updatedaddress->address);
        $this->assertEquals($address_update['building'],$updatedaddress->building);

        $response= $this->post('/transaction',[
            'payment_method' => 2,
            'item_id' => 2,
            'post_code' => $updatedaddress->post_code,
            'address' => $updatedaddress->address,
            'building' => $updatedaddress->building,
        ]);

        $this->assertDatabaseHas('transactions',[
            'item_id' => 2,
            'purchaser_id' => $user->id,
            'post_code' => $updatedaddress->post_code,
            'address' => $updatedaddress->address,
            'building' => $updatedaddress->building,
            'payment_method' => 2,
        ]);

    }
}
