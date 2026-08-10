<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileAccessTest extends TestCase
{
    use RefreshDatabase;


    /**
     * 未ログインユーザーはプロフィール編集画面にアクセスできない
     */
    public function test_guest_cannot_access_profile_edit_page()
    {
        $response = $this->get(
            route('profile.edit')
        );


        $response->assertRedirect('/login');
    }



    /**
     * ログインユーザーはプロフィール編集画面を表示できる
     */
    public function test_authenticated_user_can_access_profile_edit_page()
    {

        $user = User::factory()->create();


        $response = $this
            ->actingAs($user)
            ->get(
                route('profile.edit')
            );


        $response->assertStatus(200);

    }



    /**
     * 未ログインユーザーはマイページへアクセスできない
     */
    public function test_guest_cannot_access_mypage()
    {

        $response = $this->get(
            route('mypage')
        );


        $response->assertRedirect('/login');

    }



    /**
     * ログインユーザーはマイページを表示できる
     */
    public function test_authenticated_user_can_access_mypage()
    {

        $user = User::factory()->create();


        $response = $this
            ->actingAs($user)
            ->get(
                route('mypage')
            );


        $response->assertStatus(200);

    }

}
