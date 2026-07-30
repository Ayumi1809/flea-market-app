<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;


    /**
     * ①いいねアイコン押下で商品を登録できる
     */
    public function test_user_can_add_favorite()
    {

        // ユーザー作成
        $user = User::factory()->create();


        // 商品作成
        $item = Item::factory()->create([
            'user_id' => $user->id
        ]);


        // ログイン
        $this->actingAs($user);


        // いいね登録
        $response = $this->post(
            route(
                'favorites.store',
                [
                    'item_id'=>$item->id
                ]
            )
        );


        // 元ページへ戻る
        $response->assertStatus(302);


        // favoritesテーブル確認
        $this->assertDatabaseHas(
            'favorites',
            [
                'user_id'=>$user->id,
                'item_id'=>$item->id
            ]
        );


        // いいね数確認
        $this->assertEquals(
            1,
            Favorite::where(
                'item_id',
                $item->id
            )->count()
        );

    }



    /**
     * ②いいね済みアイコンの色変更確認
     */
    public function test_favorite_icon_changes_when_registered()
    {

        $user = User::factory()->create();


        $item = Item::factory()->create();


        // いいね登録済み状態
        Favorite::create([
            'user_id'=>$user->id,
            'item_id'=>$item->id
        ]);


        $this->actingAs($user);



        // 商品詳細ページ表示
        $response = $this->get(
            route(
                'items.show',
                [
                    'item_id'=>$item->id
                ]
            )
        );


        $response->assertStatus(200);

        // ★表示確認
        $response->assertSee(
            'heart-active.png'
        );

        $response->assertSee('favorite-button active');

    }



    /**
     * ③再度押下でいいね解除できる
     */
    public function test_user_can_remove_favorite()
    {

        $user = User::factory()->create();


        $item = Item::factory()->create();


        // 事前にいいね登録
        Favorite::create([
            'user_id'=>$user->id,
            'item_id'=>$item->id
        ]);


        $this->actingAs($user);



        // 解除
        $response = $this->delete(
            route(
                'favorites.destroy',
                [
                    'item_id'=>$item->id
                ]
            )
        );


        // リダイレクト確認
        $response->assertStatus(302);



        // 削除確認
        $this->assertDatabaseMissing(
            'favorites',
            [
                'user_id'=>$user->id,
                'item_id'=>$item->id
            ]
        );


        // いいね数0確認
        $this->assertEquals(
            0,
            Favorite::where(
                'item_id',
                $item->id
            )->count()
        );

    }

}