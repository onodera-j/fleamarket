<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SellTest extends TestCase
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
    public function test_selltest_出品登録()
    {
        //テスト確認時ExhibitionRequestの"item_image" => ["required","image","mimes:jpeg,png"],をコメントアウトする
        $user = User::find(1);
        $this->actingAs($user);
        Storage::fake('public'); // Storage::put() などの操作をメモリ上で行う
        $file = UploadedFile::fake()->create('test_image.txt', 10, 'text/plain');
        $sellData = [
            'item_image' => $file,
            'category_id' => [1,5],
            'condition' => 2,
            'item_name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'item_detail' => 'テスト商品の詳細です',
            'price' => 5000,
        ];

        $response = $this->get('/sell');
        $response->assertStatus(200);

        $response = $this->post('/sell_register',$sellData);
        $response->assertRedirect('/mypage/mypage');

        $this->assertDatabaseHas('items',[
            'user_id' => 1,
            'condition' => 2,
            'item_name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'item_detail' => 'テスト商品の詳細です',
            'price' => 5000,
        ]);
        $this->assertDatabaseHas('category_items',[
            'item_id' => Item::where('item_name', 'テスト商品')->first()->id,
            'category_id' => 1,
        ]);
        $this->assertDatabaseHas('category_items',[
            'item_id' => Item::where('item_name', 'テスト商品')->first()->id,
            'category_id' => 5,
        ]);

    }
}
