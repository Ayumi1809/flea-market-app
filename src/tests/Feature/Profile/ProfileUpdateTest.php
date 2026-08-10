<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;



    /**
     * プロフィール情報を更新できる
     */
    public function test_user_can_update_profile()
    {

        Storage::fake('public');


        $user = User::factory()->create([

            'name'=>'変更前の名前',

            'postal_code'=>'100-0001',

            'address'=>'東京都',

            'building'=>'旧ビル',

        ]);



        $file = UploadedFile::fake()->create(
            'profile.jpg',
            100,
            'image/jpeg'
        );



        $response = $this
            ->actingAs($user)
            ->patch(
                route('profile.update'),
                [

                    'profile_image'=>$file,

                    'name'=>'変更後の名前',

                    'postal_code'=>'150-0001',

                    'address'=>'東京都渋谷区',

                    'building'=>'新ビル',

                ]
            );



        /*
        |--------------------------------------------------------------------------
        | リダイレクト確認
        |--------------------------------------------------------------------------
        */


        $response->assertRedirect(
            route('mypage')
        );



        /*
        |--------------------------------------------------------------------------
        | DB確認
        |--------------------------------------------------------------------------
        */


        $this->assertDatabaseHas(
            'users',
            [

                'id'=>$user->id,

                'name'=>'変更後の名前',

                'postal_code'=>'150-0001',

                'address'=>'東京都渋谷区',

                'building'=>'新ビル',

            ]
        );



        /*
        |--------------------------------------------------------------------------
        | 画像保存確認
        |--------------------------------------------------------------------------
        */


        Storage::disk('public')
            ->assertExists(
                'profile_images/'.$file->hashName()
            );

    }




    /**
     * 名前が空の場合バリデーションエラー
     */
    public function test_name_is_required()
    {

        $user = User::factory()->create();


        $response = $this
            ->actingAs($user)
            ->from(
                route('profile.edit')
            )
            ->patch(
                route('profile.update'),
                [

                    'name'=>'',

                    'postal_code'=>'150-0001',

                    'address'=>'東京都渋谷区',

                ]
            );


        $response->assertSessionHasErrors(
            'name'
        );

    }




    /**
     * 郵便番号が空の場合バリデーションエラー
     */
    public function test_postal_code_is_required()
    {

        $user = User::factory()->create();


        $response = $this
            ->actingAs($user)
            ->from(
                route('profile.edit')
            )
            ->patch(
                route('profile.update'),
                [

                    'name'=>'山田太郎',

                    'postal_code'=>'',

                    'address'=>'東京都渋谷区',

                ]
            );


        $response->assertSessionHasErrors(
            'postal_code'
        );

    }





    /**
     * 住所が空の場合バリデーションエラー
     */
    public function test_address_is_required()
    {

        $user = User::factory()->create();


        $response = $this
            ->actingAs($user)
            ->from(
                route('profile.edit')
            )
            ->patch(
                route('profile.update'),
                [

                    'name'=>'山田太郎',

                    'postal_code'=>'150-0001',

                    'address'=>'',

                ]
            );


        $response->assertSessionHasErrors(
            'address'
        );

    }



    /**
     * 建物名は空でも更新できる
     */
    public function test_building_is_optional()
    {

        $user = User::factory()->create();


        $response = $this
            ->actingAs($user)
            ->patch(
                route('profile.update'),
                [

                    'name'=>'山田太郎',

                    'postal_code'=>'150-0001',

                    'address'=>'東京都渋谷区',

                    'building'=>'',

                ]
            );


        $response->assertRedirect(
            route('mypage')
        );



        $this->assertDatabaseHas(
            'users',
            [

                'id'=>$user->id,

                'building'=>null,

            ]
        );

    }

}