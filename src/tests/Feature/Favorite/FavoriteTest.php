<?php

namespace Tests\Feature\Favorite;

use App\Models\Favorite;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_favorite()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('favorites.store', [
            'item_id' => $item->id,
        ]));

        $response->assertRedirect();

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(
            1,
            Favorite::where('item_id', $item->id)->count()
        );
    }

    public function test_favorite_icon_changes_when_registered()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('items.show', [
            'item_id' => $item->id,
        ]));

        $response->assertOk();

        $response->assertSee('heart-active.png');

        $response->assertSee('favorite-button active');
    }

    public function test_user_can_remove_favorite()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user);

        $response = $this->delete(route('favorites.destroy', [
            'item_id' => $item->id,
        ]));

        $response->assertRedirect();

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(
            0,
            Favorite::where('item_id', $item->id)->count()
        );
    }
}