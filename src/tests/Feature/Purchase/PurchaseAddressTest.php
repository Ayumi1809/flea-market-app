<?php

namespace Tests\Feature\Purchase;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseAddressTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 配送先変更画面が表示される
     */
    public function test_purchase_address_edit_page_display()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get(
            route(
                'purchase.address.edit',
                [
                    'item_id'=>$item->id
                ]
            )
        );

        $response->assertStatus(200);

        $response->assertSee(
            '住所の変更'
        );
    }

    /**
     * 配送先変更後、購入画面へ戻る
     */
    public function test_purchase_address_update()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->patch(
            route(
                'purchase.address.update',
                [
                    'item_id'=>$item->id
                ]
            ),
            [
                'postal_code'=>'111-2222',
                'address'=>'東京都新宿区',
                'building'=>'テストビル101',
            ]
        );

        $response->assertRedirect(
            route(
                'purchase.create',
                [
                    'item_id'=>$item->id
                ]
            )
        );

        $this->assertEquals(
            '111-2222',
            session('purchase_address.postal_code')
        );
    }
}
