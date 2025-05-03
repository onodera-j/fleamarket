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
use App\Models\CategoryItem;
use App\Models\Comment;
use Tests\TestCase;

class ItemdetailTest extends TestCase
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
    public function test_ItemdetailTest_情報の表示()
    {
        $expectedComment = Comment::create([
            'item_id' => 2,
            'user_id' => 3,
            'comment' => 'テストテスト',
        ]);

        $expectedFavorite = Favorite::create([
            'user_id' => 4,
            'item_id' => 2,
        ]);

        $response = $this->get('/item/2');
        $response->assertStatus(200);

        $expectedItem = Item::find(2);
        $expectedCategories = CategoryItem::where('item_id',2)->get();

        $response->assertViewHas('item');
        $item = $response->viewData('item');
        $response->assertViewHas('comments');
        $comments = $response->viewData('comments');
        $response->assertViewHas('counts');
        $counts = $response->viewData('counts');
        $response->assertViewHas('categories');
        $categories = $response->viewData('categories');

        $this->assertEquals($item->item_name,$expectedItem->item_name);
        $this->assertEquals($item->brand_name,$expectedItem->brand_name);
        $this->assertEquals($item->item_detail,$expectedItem->item_detail);
        $this->assertEquals($item->condition,$expectedItem->condition);
        $this->assertEquals($item->price,$expectedItem->price);
        $this->assertEquals($item->item_image,$expectedItem->item_image);

        $this->assertCount(1,$counts);
        $this->assertCount(1,$comments);
        $this->assertEquals($comments->first()->user_id,3);
        $this->assertEquals($comments->first()->comment,'テストテスト');
        $this->assertCount($expectedCategories->count(),$categories);

        $expectedCategoryIds = CategoryItem::where('item_id', 2)->pluck('category_id')->toArray();
        $actualCategoryIds = $categories->pluck('category_id')->toArray();
        $this->assertEqualsCanonicalizing($expectedCategoryIds, $actualCategoryIds);

        $expectedContents = $expectedCategories->map(function ($categoryItem) {
            return $categoryItem->category->content;
        })->toArray();

        $actualContents = $categories->map(function ($categoryItem) {
            return $categoryItem->category->content;
        })->toArray();

        $this->assertEqualsCanonicalizing($expectedContents, $actualContents);

    }

    public function test_ItemdetailTest_いいね機能()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $expectedFavorite = Favorite::create([
            'user_id' => 4,
            'item_id' => 2,
        ]);

        $response = $this->get('/item/2');
        $response->assertStatus(200);
        $response->assertSee('storage/star1.svg');
        $response->assertViewHas('counts');
        $counts = $response->viewData('counts');
        $this->assertCount(1,$counts);


        $response = $this->post('/favorite',[
            'item_id' => 2,
        ]);
        $response->assertRedirect('/item/2');

        $response = $this->get('/item/2');
        $response->assertSee('storage/star2.svg');
        $response->assertViewHas('counts');
        $counts = $response->viewData('counts');
        $this->assertCount(2,$counts);

        $response = $this->delete('/favoritedelete',[
            'item_id' => 2,
        ]);
        $response->assertRedirect('/item/2');

        $response = $this->get('/item/2');
        $response->assertSee('storage/star1.svg');
        $response->assertViewHas('counts');
        $counts = $response->viewData('counts');
        $this->assertCount(1,$counts);

    }

     public function test_ItemdetailTest_コメント機能()
    {
        $expectedComment = Comment::create([
            'item_id' => 2,
            'user_id' => 3,
            'comment' => 'テストテスト',
        ]);

        $this->assertGuest();
        $response = $this->get('/item/2');
        $response->assertStatus(200);

        $commentData = [
            'comment' => 'コメントテスト',
            'item_id' => 2,
        ];
        $longComment = str_repeat('a', 256);

        $response = $this->post('/comment', $commentData);
        $this->assertDatabaseMissing('comments', $commentData);
        $response->assertRedirect('/email/verify');

        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->get('/item/2');
        $response->assertStatus(200);

        $response = $this->post('/comment', [
            'comment' => '',
            'item_id' => 2
        ]);
        $response->assertSessionHaserrors(['comment' => 'コメントを入力してください']);

        $response = $this->post('/comment', [
            'comment' => $longComment,
            'item_id' => 2
        ]);
        $response->assertSessionHaserrors(['comment' => '入力できるのは最大255文字です']);

        $response = $this->post('/comment', $commentData);
        $response->assertRedirect('/item/2');
        $this->assertDatabaseHas('comments', $commentData);

        $response = $this->get('/item/2');
        $response->assertViewHas('comments');
        $comments = $response->viewData('comments');
        $this->assertCount(2,$comments);





    }


}
