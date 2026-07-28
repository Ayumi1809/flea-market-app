<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'user_id' => 1,
                'condition_id' => 1,
                'name' => '腕時計',
                'brand_name' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'image' => 'items/watch.jpg',
            ],
            [
                'user_id' => 2,
                'condition_id' => 2,
                'name' => 'HDD',
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'image' => 'items/hdd.jpg',
            ],
            [
                'user_id' => 3,
                'condition_id' => 3,
                'name' => '玉ねぎ3束',
                'brand_name' => null,
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'image' => 'items/onion.jpg',
            ],
            [
                'user_id' => 1,
                'condition_id' => 4,
                'name' => '革靴',
                'brand_name' => '',
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'image' => 'items/shoes.jpg',
            ],
            [
                'user_id' => 2,
                'condition_id' => 1,
                'name' => 'ノートPC',
                'brand_name' => '',
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'image' => 'items/laptop.jpg',
            ],
            [
                'user_id' => 3,
                'condition_id' => 2,
                'name' => 'マイク',
                'brand_name' => null,
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'image' => 'items/mic.jpg',
            ],
            [
                'user_id' => 1,
                'condition_id' => 3,
                'name' => 'ショルダーバッグ',
                'brand_name' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'image' => 'items/bag.jpg',
            ],

            [
                'user_id' => 2,
                'condition_id' => 4,
                'name' => 'タンブラー',
                'brand_name' => null,
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'image' => 'items/tumbler.jpg',
            ],
            [
                'user_id' => 3,
                'condition_id' => 1,
                'name' => 'コーヒーミル',
                'brand_name' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'image' => 'items/coffee_mill.jpg',
            ],
            [
                'user_id' => 1,
                'condition_id' => 2,
                'name' => 'メイクセット',
                'brand_name' => '',
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'image' => 'items/makeup.jpg',
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
