<?php

namespace Tests\Feature\Item;

use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ConditionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExhibitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_exhibit_item()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->seed([
            CategorySeeder::class,
            ConditionSeeder::class,
        ]);

        $category = Category::first();

        $condition = Condition::first();

        $this->actingAs($user);

        $response = $this->get(route('items.create'));

        $response->assertOk();

        $image = UploadedFile::fake()->image('test-item.jpeg');

        $response = $this->post(route('items.store'), [
            'image' => $image,
            'categories' => [
                $category->id,
            ],
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'これはテスト用の商品説明です。',
            'price' => 5000,
        ]);

        $response->assertRedirect(route('items.index'));

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'これはテスト用の商品説明です。',
            'price' => 5000,
        ]);

        $item = Item::where('name', 'テスト商品')->first();

        $this->assertDatabaseHas('item_category', [
            'item_id' => $item->id,
            'category_id' => $category->id,
        ]);

        $this->assertNotNull($item->image);

        Storage::disk('public')->assertExists($item->image);
    }
}
