<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * プロフィール情報を表示
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            $items = $user->purchasedItems;
        } else {
            $items = $user->items;
        }

        return view('mypage.index', compact('user', 'items', 'page'));
    }

    /**
     * プロフィール編集画面を表示
     */
    public function edit()
    {
        $user = Auth::user();

        return view('mypage.profile', compact('user'));
    }

    /**
     * プロフィール情報を更新
     */
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        $data = $request->validated();

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request
                ->file('profile_image')
                ->store('profile_images', 'public');
        }

        $user->update($data);

        return redirect()
            ->route('mypage')
            ->with('success', 'プロフィールを更新しました。');
    }
}
