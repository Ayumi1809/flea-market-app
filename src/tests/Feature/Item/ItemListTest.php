<?php

namespace Tests\Feature\Item;

use App\Models\Favorite;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_items_are_displayed()
    {
        Item::factory()->create([
            'name' => '腕時計',
        ]);

        Item::factory()->create([
            'name' => 'HDD',
        ]);

        Item::factory()->create([
            'name' => 'ノートPC',
        ]);

        $response = $this->get(route('items.index'));

        $response->assertOk();

        $response->assertSee('腕時計');

        $response->assertSee('HDD');

        $response->assertSee('ノートPC');
    }

    public function test_my_items_are_not_displayed_in_recommended_list()
    {
        $user = User::factory()->create();

        Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の腕時計',
        ]);

        Item::factory()->create([
            'name' => '他ユーザーのHDD',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('items.index'));

        $response->assertOk();

        $response->assertDontSee('自分の腕時計');

        $response->assertSee('他ユーザーのHDD');
    }

    public function test_mylist_displays_favorite_items()
    {
        $user = User::factory()->create();

        $favoriteItem = Item::factory()->create([
            'name' => 'いいねした商品',
        ]);

        $notFavoriteItem = Item::factory()->create([
            'name' => 'いいねしていない商品',
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $favoriteItem->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('items.index', [
                'tab' => 'mylist',
            ]));

        $response->assertOk();

        $response->assertSee('いいねした商品');

        $response->assertDontSee('いいねしていない商品');
    }

    public function test_sold_label_is_displayed_in_mylist()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'name' => '購入済み商品',
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('items.index', [
                'tab' => 'mylist',
            ]));

        $response->assertOk();

        $response->assertSee('購入済み商品');

        $response->assertSee('Sold');
    }

    public function test_mylist_displays_no_items_for_guest()
    {
        Item::factory()->create([
            'name' => '腕時計',
        ]);

        Item::factory()->create([
            'name' => 'HDD',
        ]);

        $response = $this->get(
            route('items.index', [
                'tab' => 'mylist',
            ])
        );

        $response->assertOk();

        $response->assertDontSee('腕時計');

        $response->assertDontSee('HDD');
    }
}
