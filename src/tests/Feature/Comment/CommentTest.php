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

    protected $seed = true;

    /**
     * @test
     */
    public function ログイン済みユーザーはコメントを送信できる()
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

    /**
     * @test
     */
    public function ログイン前ユーザーはコメントを送信できない()
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

    /**
     * @test
     */
    public function コメント未入力ならバリデーションエラーになる()
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
            ->assertSessionHasErrors([
                'comment',
            ]);
    }

    /**
     * @test
     */
    public function コメントが255文字を超えるとバリデーションエラーになる()
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
            ->assertSessionHasErrors([
                'comment',
            ]);
    }
}
