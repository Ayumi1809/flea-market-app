<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // クエリパラメータを取得
        $tab = $request->query('tab');
        $keyword = $request->query('keyword');

        // マイリストの場合
        if ($tab === 'mylist') {
            $items = $this->getMyListItems($keyword);
        }

        // おすすめの場合
        else {
            $items = $this->getRecommendedItems($keyword);
        }

        return view ('items.index', compact(
            'items',
            'tab',
            'keyword'
        ));
    }

    // おすすめ商品一覧
    private function getRecommendedItems($keyword)
    {
        $query = Item::query();

        // ログイン中は自分が出品した商品を除外
        if (auth()->check()) {
            $query->where(
                'user_id',
                '!=',
                auth()->id()
            );
        }

        // 商品名の部分一致検索
        if ($keyword) {
            $query->where(
                'name',
                'like',
                '%' . $keyword . '%'
            );
        }

        return $query
            ->with ([
                'purchase',
                'user',
                'condition',
                'categories',
            ])
            ->latest()
            ->get();
    }

    //マイリスト商品一覧
    private function getMyListItems($keyword)
    {
        // 未認証の場合は空のコレクション
        if (!auth()->check()) {
            return collect();
        }

        $query = Item::query()
        ->whereHas('favorites', function($query) {
            $query->where(
                'user_id',
                auth()->id()
            );
        });

        // 商品名の部分一致検索
        if ($keyword) {
            $query->where(
                'name',
                'like',
                '%' . $keyword . '%'
            );
        }

        return $query
        ->with([
            'purchase',
            'user',
            'condition',
            'categories',
        ])
        ->latest()
        ->get();
    }

    // 商品詳細画面
    public function show($item_id)
    {
        $item = Item::with([
            'user',
            'condition',
            'categories',
            'favorites',
            'comments.user',
            'purchase',
        ])
        ->findOrFail($item_id);

    return view('items.show', compact('item'));
}
}
