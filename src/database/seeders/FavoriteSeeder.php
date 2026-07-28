<?php

namespace Database\Seeders;

use App\Models\Favorite;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $favorites = [

            [
                'user_id' => 1,
                'item_id' => 2,
            ],

            [
                'user_id' => 1,
                'item_id' => 5,
            ],

            [
                'user_id' => 2,
                'item_id' => 1,
            ],

            [
                'user_id' => 2,
                'item_id' => 7,
            ],

            [
                'user_id' => 3,
                'item_id' => 4,
            ],

            [
                'user_id' => 3,
                'item_id' => 10,
            ],

        ];

        foreach ($favorites as $favorite) {
            Favorite::create($favorite);
        }
    }
}
