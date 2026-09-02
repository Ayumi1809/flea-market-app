<?php

namespace Tests\Feature\Purchase;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_address_edit_page_display()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get(
            route('purchase.address.edit', [
                'item_id' => $item->id,
            ])
        );

        $response->assertOk();

        $response->assertSee('住所の変更');
    }

    public function test_purchase_address_update()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->patch(
            route('purchase.address.update', [
                'item_id' => $item->id,
            ]),
            [
                'postal_code' => '111-2222',
                'address' => '東京都新宿区',
                'building' => 'テストビル101',
            ]
        );

        $response->assertRedirect(
            route('purchase.create', [
                'item_id' => $item->id,
            ])
        );

        $this->assertEquals(
            '111-2222',
            session('purchase_address.postal_code')
        );

        $this->assertEquals(
            '東京都新宿区',
            session('purchase_address.address')
        );

        $this->assertEquals(
            'テストビル101',
            session('purchase_address.building')
        );
    }
}