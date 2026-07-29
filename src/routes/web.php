<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

    // 商品一覧画面
Route::get('/', [ItemController::class, 'index'])
    ->name('items.index');

    // 商品詳細画面
    Route::get('/item/{item_id}',       [ItemController::class, 'show'])
    ->name('items.show');

    Route::post(
    '/item/{item_id}/comment',
    [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('comments.store');



Route::middleware('auth')->group(function () {

    // マイページ
    Route::get('/mypage', [ProfileController::class, 'index'])
        ->name('mypage');

    // プロフィール編集画面
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    // プロフィール更新
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // 商品出品画面
    Route::get('/sell', [ItemController::class, 'create'])
        ->name('items.create');

    // 商品登録処理
    Route::post('/sell', [ItemController::class, 'store'])
        ->name('items.store');
});