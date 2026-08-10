<?php

namespace Tests\Feature\Purchase;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;


    /**
     * ① 購入するボタン押下で購入が完了する
     */
    public function test_purchase_is_completed()
    {
        $seller = User::factory()->create();

        // ユーザー作成
        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストマンション101',
        ]);

        // 商品作成
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 'selling',
        ]);

        // ログイン
        $this->actingAs($buyer);

        session([
            'payment_method'=>'card',

            'purchase_address' => [
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストマンション101',
            ],
        ]);

        // Stripe成功後の処理を実行
        $response = $this->get(
            route(
                'purchase.success',
                [
                    'item_id'=>$item->id
                ]
            )
        );

        // 商品一覧へリダイレクト
        $response->assertRedirect(
            route('items.index')
        );

        // 購入データ確認
        $this->assertDatabaseHas(
            'purchases',
            [
                'user_id'=>$buyer->id,
                'item_id'=>$item->id,
                'payment_method'=>'card',
                'postal_code'=>'123-4567',

            ]
        );
    }


    /**
     * ② 購入した商品がsoldになる
     */
    public function test_item_becomes_sold_after_purchase()
    {
        $seller = User::factory()->create();

        $buyer = User::factory()->create([
            'postal_code'=>'123-4567',
            'address'=>'東京都渋谷区',
            'building'=>'101',
        ]);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status'=>'selling',
        ]);

        $this->actingAs($buyer);

        session([
            'payment_method'=>'card',

            'purchase_address'=>[
                'postal_code'=>'123-4567',
                'address'=>'東京都渋谷区',
                'building'=>'101',
            ],
        ]);

        $this->get(
            route(
                'purchase.success',
                [
                    'item_id'=>$item->id
                ]
            )
        );

        $this->assertDatabaseHas(
            'items',
            [
                'id'=>$item->id,
                'status'=>'sold',
            ]
        );

    }

    /**
     * ③ プロフィール購入商品一覧へ追加される
     */
    public function test_purchased_item_is_displayed_on_profile()
    {
        $seller = User::factory()->create();

        $buyer = User::factory()->create([
            'postal_code'=>'123-4567',
            'address'=>'東京都渋谷区',
            'building'=>'101',
        ]);

        $item = Item::factory()->create([
            'name'=>'テスト商品',
            'status'=>'selling',
        ]);

        $this->actingAs($buyer);

        session([
            'payment_method'=>'card',

            'purchase_address'=>[
                'postal_code'=>'123-4567',
                'address'=>'東京都渋谷区',
                'building'=>'101',
            ],
        ]);

        // 購入処理
        $this->get(
            route(
                'purchase.success',
                [
                    'item_id'=>$item->id
                ]
            )
        );

        // マイページ購入一覧表示
        $response = $this->get(
            route(
                'mypage',
                [
                    'page'=>'buy'
                ]
            )
        );

        $response->assertStatus(200);

        // 商品名が表示されることを確認
        $response->assertSee(
            $item->name
        );

    }

}