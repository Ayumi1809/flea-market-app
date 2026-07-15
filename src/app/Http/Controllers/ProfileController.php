<?php

namespace App\Http\Controllers;

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
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'name' => 'required|string|max:255',
            'postal_code' => [
                'required',
                'regex:/^\d{3}-\d{4}$/',
            ],
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ], [
            'profile_image.image' => '画像ファイルを選択してください。',
            'profile_image.mimes' => 'JPEG、PNG、JPG形式の画像を選択してください。',
            'profile_image.max' => '画像サイズは2MB以内にしてください。',
            'name.required' => 'ユーザー名を入力してください。',
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex' => '郵便番号は000-0000形式で入力してください。',
            'address.required' => '住所を入力してください。',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request
                ->file('profile_image')
                ->store('profile_images', 'public');

            $validated['profile_image'] = $path;
        }

        $user->update($validated);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'プロフィールを更新しました。');
    }
}
