<?php

namespace Tests\Feature\Item;

use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ConditionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExhibitionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ユーザーが商品情報を入力して出品できる()
    {
        // ストレージをテスト用に変更
        Storage::fake('public');

        // ユーザー作成
        $user = User::factory()->create();

        // カテゴリ作成
        $this->seed([
            CategorySeeder::class,
            ConditionSeeder::class,
        ]);

        // カテゴリ取得
        $category = Category::first();

        // 商品状態取得
        $condition = Condition::first();

        // ログイン
        $this->actingAs($user);

        // 商品出品画面を開く
        $response = $this->get(route('items.create'));

        // 出品画面が正常に表示される
        $response->assertStatus(200);

        // テスト用画像
        $image = UploadedFile::fake()->image('test-item.jpg');

        // 商品出品
        $response = $this->post(route('items.store'), [

            'image' => $image,

            'categories' => [
                $category->id,
            ],

            'condition_id' => $condition->id,

            'name' => 'テスト商品',

            'brand_name' => 'テストブランド',

            'description' => 'これはテスト用の商品説明です。',

            'price' => 5000,
        ]);

        // 出品後の商品一覧へリダイレクト
        $response->assertRedirect(
            route('items.index')
        );

        // 商品情報がDBに保存されている
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'これはテスト用の商品説明です。',
            'price' => 5000,
        ]);

        // 保存された商品を取得
        $item = Item::where('name', 'テスト商品')->first();

        // カテゴリが紐付いている
        $this->assertDatabaseHas('item_category', [
            'item_id' => $item->id,
            'category_id' => $category->id,
        ]);

        // 画像パスがDBに保存されている
        $this->assertNotNull($item->image);

        // storage/app/public/items に画像が保存されている
        Storage::disk('public')->assertExists(
            $item->image
        );
    }
}
