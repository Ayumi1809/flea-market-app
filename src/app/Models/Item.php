<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'condition_id',
        'name',
        'brand_name',
        'description',
        'price',
        'image',
        'status',
    ];

    // 購入情報
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    // カテゴリー
    public function categories()
    {
        return $this->belongsToMany(
        Category::class,
        'item_category',
        'item_id',
        'category_id'
        );
    }

    // コメント
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // 出品者
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 商品状態
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteUsers()
    {
        return $this->belongsToMany(
            User::class,
            'favorites',
            'item_id',
            'user_id'
        );
    }

    public function isFavoriteBy($user)
    {
        if (!$user) {
            return false;
        }

        return $this->favorites()
            ->where('user_id', $user->id)
            ->exists();
    }

}
