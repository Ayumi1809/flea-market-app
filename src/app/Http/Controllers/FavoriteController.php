<?php

namespace App\Http\Controllers;

use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function store($itemId)
    {
        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'item_id' => $itemId,
        ]);

        return back();
    }

    public function destroy($itemId)
    {
        Favorite::where('user_id', auth()->id())
            ->where('item_id', $itemId)
            ->delete();

        return back();
    }
}
