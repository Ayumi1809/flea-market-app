<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function store($item_id)
    {
        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'item_id' => $item_id,
        ]);

        return back();
    }

    public function destroy($item_id)
    {
        Favorite::where('user_id', auth()->id())
            ->where('item_id', $item_id)
            ->delete();

        return back();
    }
}
