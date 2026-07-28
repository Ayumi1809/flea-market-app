<?php

namespace Tests\Feature\Item;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /** @test */
    public function 商品詳細に必要な情報が表示される()
    {
        $item = Item::with([
            'user',
            'condition',
            'categories',
            'comments.user',
            'favorites',
        ])->findOrFail(1);

        $response = $this->get(route('items.show', [
            'item_id' => $item->id,
        ]));

        $response->assertStatus(200);

        // 商品情報
        $response->assertSee($item->name);
        $response->assertSee($item->brand_name);
        $response->assertSee(number_format($item->price));
        $response->assertSee($item->description);

        // 商品状態
        $response->assertSee($item->condition->name);

        // カテゴリ
        foreach ($item->categories as $category) {
            $response->assertSee($category->name);
        }

        // コメント数
        $response->assertSee((string)$item->comments->count());

        // コメントしたユーザー名・コメント
        foreach ($item->comments as $comment) {
            $response->assertSee($comment->user->name);
            $response->assertSee($comment->comment);
        }

        // いいね数
        $response->assertSee((string)$item->favorites->count());

        // 商品画像
        $response->assertSee($item->image);
    }

    /** @test */
    public function 複数選択されたカテゴリが表示される()
    {
        $item = Item::with('categories')->findOrFail(1);

        $response = $this->get(route('items.show', [
            'item_id' => $item->id,
        ]));

        $response->assertStatus(200);

        foreach ($item->categories as $category) {
            $response->assertSee($category->name);
        }
    }
}