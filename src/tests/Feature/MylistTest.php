<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Address;
use App\Models\Favorite;
use App\Models\Item;
use Tests\TestCase;


class MylistTest extends TestCase
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
    public function test_mylisttest_いいね商品表示()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $address = Address::create([
            'user_id' => $user->id,
            'post_code' => '1234567',
            'address' => '東京都渋谷区',
            'building' => '宇田川町',
        ]);

        $expectedItem = Item::find(2);


        $favorite = Favorite::create([
            'user_id' => $user->id,
            'item_id' => 2,
        ]);

        $response = $this->get('/?tab=mylist');
        $response->assertViewHas('mylistItems');
        $items = $response->viewData('mylistItems');
        $this->assertCount(1,$items);
        $firstmylistItem = $items->first();
        $this->assertEquals($expectedItem->toArray(),$firstmylistItem->item->toArray());
    }

    public function test_mylisttest_自分出品表示されない()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $address = Address::create([
            'user_id' => $user->id,
            'post_code' => '1234567',
            'address' => '東京都渋谷区',
            'building' => '宇田川町',
        ]);

        $expectedItem = Item::find(1);


        $favorite = Favorite::create([
            'user_id' => $user->id,
            'item_id' => 1,
        ]);

        $response = $this->get('/?tab=mylist');
        $response->assertViewHas('mylistItems');
        $items = $response->viewData('mylistItems');
        $this->assertCount(0,$items);
    }

    public function test_mylisttest_ゲストのとき()
    {
        $user = User::find(1);
        $favorite = Favorite::create([
            'user_id' => $user->id,
            'item_id' => 2,
        ]);

        $this->assertGuest();
        $response = $this->get('/?tab=mylist');
        $response->assertViewHas('mylistItems');
        $items = $response->viewData('mylistItems');
        $this->assertCount(0,$items);
    }
}
