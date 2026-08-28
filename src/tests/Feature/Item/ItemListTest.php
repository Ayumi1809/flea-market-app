<?php

namespace Tests\Feature\Item;

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

        $response->assertStatus(200);

        $response->assertSee('腕時計');

        $response->assertSee('HDD');

        $response->assertSee('ノートPC');
    }

    public function test_sold_label_is_displayed()
    {
        $item = Item::factory()->create([
            'name' => '腕時計',
        ]);

        Purchase::factory()->create([
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('items.index'));

        $response->assertStatus(200);

        $response->assertSee('Sold');
    }

    public function test_my_items_are_not_displayed()
    {
        $user = User::factory()->create();

        Item::factory()->create([
            'user_id' => $user->id,
            'name' => '腕時計',
        ]);

        Item::factory()->create([
            'name' => 'HDD',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('items.index'));

        $response->assertStatus(200);

        $response->assertDontSee('腕時計');

        $response->assertSee('HDD');
    }
}
