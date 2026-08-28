<?php

namespace Tests\Feature\Item;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_item_detail_displays_required_information()
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

        $response->assertSee($item->name);

        $response->assertSee($item->brand_name);

        $response->assertSee(number_format($item->price));

        $response->assertSee($item->description);

        $response->assertSee($item->condition->name);

        foreach ($item->categories as $category) {
            $response->assertSee($category->name);
        }

        $response->assertSee((string) $item->comments->count());

        foreach ($item->comments as $comment) {
            $response->assertSee($comment->user->name);
            $response->assertSee($comment->comment);
        }

        $response->assertSee((string) $item->favorites->count());

        $response->assertSee($item->image);
    }

    public function test_multiple_categories_are_displayed()
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