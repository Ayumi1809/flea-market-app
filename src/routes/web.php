<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;

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

    Route::post(
        '/item/{item_id}/favorite',
        [FavoriteController::class, 'store']
    )->name('favorites.store');

    Route::delete(
        '/item/{item_id}/favorite',
        [FavoriteController::class, 'destroy']
    )->name('favorites.destroy');

    Route::get(
        '/purchase/{item_id}',
        [PurchaseController::class,'create']
    )->name('purchase.create');

    Route::post(
        '/purchase/{item_id}',
        [PurchaseController::class,'store']
    )->name('purchase.store');

    Route::get(
        '/purchase/address/{item_id}',
        [AddressController::class, 'edit']
    )->name('purchase.address.edit');

    Route::patch(
        '/purchase/address/{item_id}',
        [AddressController::class, 'update']
    )->name('purchase.address.update');

    Route::post(
        '/purchase/{item_id}/checkout',
        [PurchaseController::class,'checkout']
    )
    ->name('purchase.checkout');

    Route::get(
        '/purchase/{item_id}/success',
        [PurchaseController::class,'success']
    )
    ->name('purchase.success');

    Route::get(
        '/purchase/cancel',
        [PurchaseController::class,'cancel']
    )
    ->name('purchase.cancel');

});
