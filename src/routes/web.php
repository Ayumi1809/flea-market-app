<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;

Route::get('/', [ItemController::class, 'index'])
    ->name('items.index');

Route::get('/item/{item_id}', [ItemController::class, 'show'])
    ->name('items.show');

Route::post(
    '/item/{item_id}/comment',
    [CommentController::class, 'store']
)
    ->middleware('auth')
    ->name('comments.store');

Route::middleware('auth')->group(function () {
    Route::get('/mypage', [ProfileController::class, 'index'])
        ->name('mypage');

    Route::get('/mypage/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/mypage/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/sell', [ItemController::class, 'create'])
        ->name('items.create');

    Route::post('/sell', [ItemController::class, 'store'])
        ->name('items.store');

    Route::post(
        '/item/{item_id}/favorite',
        [FavoriteController::class, 'store']
    )
        ->name('favorites.store');

    Route::delete(
        '/item/{item_id}/favorite',
        [FavoriteController::class, 'destroy']
    )
        ->name('favorites.destroy');

    Route::get(
        '/purchase/{item_id}',
        [PurchaseController::class, 'create']
    )
        ->name('purchase.create');

    Route::post(
        '/purchase/{item_id}',
        [PurchaseController::class, 'store']
    )
        ->name('purchase.store');

    Route::get(
        '/purchase/address/{item_id}',
        [AddressController::class, 'edit']
    )
        ->name('purchase.address.edit');

    Route::patch(
        '/purchase/address/{item_id}',
        [AddressController::class, 'update']
    )
        ->name('purchase.address.update');

    Route::post(
        '/purchase/{item_id}/checkout',
        [PurchaseController::class, 'checkout']
    )
        ->name('purchase.checkout');

    Route::get(
        '/purchase/{item_id}/success',
        [PurchaseController::class, 'success']
    )
        ->name('purchase.success');

    Route::get(
        '/purchase/cancel',
        [PurchaseController::class, 'cancel']
    )
        ->name('purchase.cancel');
});