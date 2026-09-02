<?php

namespace Tests\Feature\Item;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_items_by_keyword()
    {
        Item::factory()->create([
            'name' => 'ノートPC',
        ]);

        Item::factory()->create([
            'name' => 'デスクトップPC',
        ]);

        Item::factory()->create([
            'name' => '腕時計',
        ]);

        $response = $this->get(
            route('items.index', [
                'keyword' => 'PC',
            ])
        );

        $response->assertOk();

        $response->assertSee('ノートPC');

        $response->assertSee('デスクトップPC');

        $response->assertDontSee('腕時計');
    }
}
