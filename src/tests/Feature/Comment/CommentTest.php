<?php

namespace Tests\Feature\Comment;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_post_comment()
    {
        $user = User::find(1);
        $item = Item::find(1);

        $beforeCount = Comment::where('item_id', $item->id)->count();

        $response = $this->actingAs($user)->post(
            route('comments.store', ['item_id' => $item->id]),
            [
                'comment' => 'テストコメントです',
            ]
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメントです',
        ]);

        $afterCount = Comment::where('item_id', $item->id)->count();

        $this->assertEquals($beforeCount + 1, $afterCount);
    }

    public function test_guest_cannot_post_comment()
    {
        $item = Item::find(1);

        $response = $this->post(
            route('comments.store', ['item_id' => $item->id]),
            [
                'comment' => 'コメント',
            ]
        );

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'comment' => 'コメント',
        ]);
    }

    public function test_comment_is_required()
    {
        $user = User::find(1);
        $item = Item::find(1);

        $response = $this
            ->from(route('items.show', ['item_id' => $item->id]))
            ->actingAs($user)
            ->post(
                route('comments.store', ['item_id' => $item->id]),
                [
                    'comment' => '',
                ]
            );

        $response
            ->assertRedirect(route('items.show', ['item_id' => $item->id]))
            ->assertSessionHasErrors(['comment']);
    }

    public function test_comment_cannot_exceed_255_characters()
    {
        $user = User::find(1);
        $item = Item::find(1);

        $response = $this
            ->from(route('items.show', ['item_id' => $item->id]))
            ->actingAs($user)
            ->post(
                route('comments.store', ['item_id' => $item->id]),
                [
                    'comment' => str_repeat('あ', 256),
                ]
            );

        $response
            ->assertRedirect(route('items.show', ['item_id' => $item->id]))
            ->assertSessionHasErrors(['comment']);
    }
}
