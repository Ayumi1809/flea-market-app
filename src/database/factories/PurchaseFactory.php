<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'postal_code' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101',
            'payment_method' => 'コンビニ払い',
        ];
    }
}
