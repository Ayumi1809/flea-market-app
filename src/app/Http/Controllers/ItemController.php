<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab');
        $keyword = $request->query('keyword');

        if ($tab === 'mylist') {
            $items = $this->getMyListItems($keyword);
        } else {
            $items = $this->getRecommendedItems($keyword);
        }

        return view('items.index', compact(
            'items',
            'tab',
            'keyword'
        ));
    }

    private function getRecommendedItems($keyword)
    {
        $query = Item::query();

        if (auth()->check()) {
            $query->where(
                'user_id',
                '!=',
                auth()->id()
            );
        }

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

    private function getMyListItems($keyword)
    {
        if (!auth()->check()) {
            return collect();
        }

        $query = Item::query()
            ->whereHas('favorites', function ($query) {
                $query->where(
                    'user_id',
                    auth()->id()
                );
            });

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

    public function show($itemId)
    {
        $item = Item::with([
            'user',
            'condition',
            'categories',
            'favorites',
            'comments.user',
            'purchase',
        ])
            ->findOrFail($itemId);

        return view('items.show', compact('item'));
    }

    public function create()
    {
        $categories = Category::all();

        $conditions = Condition::all();

        return view(
            'items.create',
            compact(
                'categories',
                'conditions'
            )
        );
    }

    public function store(ExhibitionRequest $request)
    {
        $path = $request->file('image')
            ->store('items', 'public');

        $item = Item::create([
            'user_id' => auth()->id(),
            'condition_id' => $request->condition_id,
            'name' => $request->name,
            'brand_name' => $request->brand_name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $path,
        ]);

        $item->categories()->attach(
            $request->categories
        );

        return redirect()
            ->route('items.index');
    }
}
