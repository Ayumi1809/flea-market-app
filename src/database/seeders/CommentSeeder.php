<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = [
            [
                'user_id' => 2,
                'item_id' => 1,
                'comment' => 'とてもきれいな状態ですね！',
            ],

            [
                'user_id' => 3,
                'item_id' => 1,
                'comment' => 'まだ購入できますか？',
            ],

            [
                'user_id' => 1,
                'item_id' => 2,
                'comment' => '動作確認済みでしょうか？',
            ],

            [
                'user_id' => 2,
                'item_id' => 5,
                'comment' => '付属品はありますか？',
            ],

            [
                'user_id' => 3,
                'item_id' => 7,
                'comment' => 'サイズを教えてください。',
            ],

            [
                'user_id' => 1,
                'item_id' => 10,
                'comment' => '新品未使用ですか？',
            ],
        ];

        foreach ($comments as $comment) {
            Comment::create($comment);
        }
    }
}
