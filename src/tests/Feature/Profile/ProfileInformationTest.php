<?php

namespace Tests\Feature\Profile;

use App\Models\Condition;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_information_is_displayed()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'profile.jpg',
        ]);

        $condition = Condition::first();

        Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => '出品した商品',
            'price' => 3000,
            'status' => 'selling',
        ]);

        $seller = User::factory()->create([
            'name' => '出品者ユーザー',
        ]);

        $purchaseItem = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => '購入した商品',
            'price' => 5000,
            'status' => 'sold',
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $purchaseItem->id,
            'payment_method' => 'card',
            'postal_code' => '100-0001',
            'address' => '東京都',
            'building' => '',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('mypage'));

        $response->assertOk();

        $response->assertSee('テストユーザー');

        $response->assertSee('profile.jpg');

        $response->assertSee('出品した商品');

        $response->assertSee('購入した商品');
    }
}
