<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Address;

class ItemlistTest extends TestCase
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
    public function test_itemlist()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewHas('Items', Item::all());
    }

    public function test_itemlist_購入済み表示()
    {
        $response = $this->get('/');
        $response->assertSee('Sold');
    }

    public function test_itemlist_自分の表示されない()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $address = Address::create([
            'user_id' => $user->id,
            'post_code' => '1234567',
            'address' => '東京都渋谷区',
            'building' => '宇田川町',
        ]);

        $response = $this->get('/');
        $response->assertViewHas('Items');

        $items = $response->viewData('Items');
        $this->assertNotContains($user->id, $items->pluck('user_id')->toArray(),'自分の商品が含まれています');





    }

}
