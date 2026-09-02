<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            $items = $user
                ->purchasedItems()
                ->with('purchase')
                ->latest()
                ->get();
        } else {
            $items = $user
                ->items()
                ->latest()
                ->get();
        }

        return view('mypage.index', compact('user', 'items', 'page'));
    }

    public function edit()
    {
        $user = auth()->user();

        return view('mypage.profile', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

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
