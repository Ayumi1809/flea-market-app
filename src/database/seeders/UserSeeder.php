<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => '山田 太郎',
            'email' => 'taro@example.com',
            'password' => Hash::make('password'),
            'profile_image' => null,
            'postal_code' => '111-1111',
            'address' => '東京都新宿区',
            'building' => 'コーチテックビル101',
        ]);

        User::create([
            'name' => '佐藤 花子',
            'email' => 'hanako@example.com',
            'password' => Hash::make('password'),
            'profile_image' => null,
            'postal_code' => '222-2222',
            'address' => '大阪府大阪市',
            'building' => 'サンプルマンション202',
        ]);

        User::create([
            'name' => '鈴木 一郎',
            'email' => 'ichiro@example.com',
            'password' => Hash::make('password'),
            'profile_image' => null,
            'postal_code' => '333-3333',
            'address' => '福岡県福岡市',
            'building' => 'テストハイツ303',
        ]);
    }
}
