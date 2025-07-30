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

class ItemsearchTest extends TestCase
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
    public function test_ItemsearchTest_商品名を検索()
    {
        $expectedItem = Item::find(3)->toArray();
        $response = $this->get('/search?keyword=玉ねぎ');
        $response->assertOk();

        $response->assertViewHas('Items');
        $items = $response->viewData('Items');
        $this->assertCount(1, $items, '「玉ねぎ」の検索結果が1件ではありません。');
        $firstItem = $items->first()->toArray();
        $this->assertEquals($expectedItem,$firstItem);

    }

}
