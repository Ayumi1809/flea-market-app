<?php

namespace Tests\Feature\Purchase;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_is_completed()
    {
        $seller = User::factory()->create();

        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
        ]);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 'selling',
        ]);

        $this->actingAs($buyer);

        session([
            'payment_method' => 'カード支払い',
            'purchase_address' => [
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル101',
            ],
        ]);

        $response = $this->get(
            route('purchase.success', [
                'item_id' => $item->id,
            ])
        );

        $response->assertRedirect(route('items.index'));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
        ]);
    }

    public function test_item_becomes_sold_after_purchase()
    {
        $seller = User::factory()->create();

        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
        ]);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 'selling',
        ]);

        $this->actingAs($buyer);

        session([
            'payment_method' => 'カード支払い',
            'purchase_address' => [
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル101',
            ],
        ]);

        $this->get(
            route('purchase.success', [
                'item_id' => $item->id,
            ])
        );

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold',
        ]);
    }

    public function test_purchased_item_is_displayed_on_profile()
    {
        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
        ]);

        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'status' => 'selling',
        ]);

        $this->actingAs($buyer);

        session([
            'payment_method' => 'カード支払い',
            'purchase_address' => [
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル101',
            ],
        ]);

        $this->get(
            route('purchase.success', [
                'item_id' => $item->id,
            ])
        );

        $response = $this->get(
            route('mypage', ['page' => 'buy'])
        );

        $response->assertOk();

        $response->assertSee($item->name);
    }

    public function test_payment_success_displays_purchase_message()
    {
        $seller = User::factory()->create();

        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
        ]);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 'selling',
        ]);

        $this->actingAs($buyer);

        session([
            'payment_method' => 'カード支払い',
            'purchase_address' => [
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル101',
            ],
        ]);

        $response = $this
            ->followingRedirects()
            ->get(
                route('purchase.success', [
                    'item_id' => $item->id,
                ])
            );

        $response->assertOk();

        $response->assertSee('商品を購入しました。');
    }

    public function test_purchase_success_requires_payment_method()
    {
        $seller = User::factory()->create();

        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
        ]);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 'selling',
        ]);

        $this->actingAs($buyer);

        session([
            'purchase_address' => [
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル101',
            ],
        ]);

        $response = $this->get(
            route('purchase.success', [
                'item_id' => $item->id,
            ])
        );

        $response->assertStatus(400);

        $this->assertDatabaseMissing('purchases', [
            'item_id' => $item->id,
        ]);
    }

    public function test_user_cannot_purchase_own_item()
    {
        $user = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
        ]);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'status' => 'selling',
        ]);

        $this->actingAs($user);

        session([
            'payment_method' => 'カード支払い',
            'purchase_address' => [
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル101',
            ],
        ]);

        $response = $this->get(
            route('purchase.success', [
                'item_id' => $item->id,
            ])
        );

        $response->assertStatus(403);

        $this->assertDatabaseMissing('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}