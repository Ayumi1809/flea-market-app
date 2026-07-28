<?php

namespace Database\Seeders;

use App\Models\Purchase;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $purchases = [

            [
                'user_id' => 2,
                'item_id' => 2,
                'postal_code' => '150-0001',
                'address' => '東京都渋谷区神宮前1-1-1',
                'building' => '原宿マンション101',
                'payment_method' => 'コンビニ払い',
            ],

            [
                'user_id' => 3,
                'item_id' => 4,
                'postal_code' => '530-0001',
                'address' => '大阪府大阪市北区梅田1-2-3',
                'building' => null,
                'payment_method' => 'カード支払い',
            ],

            [
                'user_id' => 1,
                'item_id' => 7,
                'postal_code' => '460-0001',
                'address' => '愛知県名古屋市中区栄1-1-1',
                'building' => '栄ビル301',
                'payment_method' => 'コンビニ払い',
            ],

        ];

        foreach ($purchases as $purchase) {
            Purchase::create($purchase);
        }
    }
}
