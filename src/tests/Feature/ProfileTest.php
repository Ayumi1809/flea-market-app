<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ① 未ログインユーザーはプロフィール編集画面にアクセスできない
     */
    public function test_guest_cannot_access_profile_edit_page()
    {
        $response = $this->get('/mypage/profile');

        $response->assertRedirect('/login');
    }

    /**
     * ② ログインユーザーはプロフィール編集画面を表示できる
     */
    public function test_authenticated_user_can_access_profile_edit_page()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/mypage/profile');

        $response->assertStatus(200);
    }

    /**
     * ③ プロフィールを更新できる
     */
    public function test_user_can_update_profile()
    {
        $user = User::factory()->create([
            'name' => '変更前の名前',
            'postal_code' => '100-0001',
            'address' => '東京都',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/mypage/profile', [
                'name' => '変更後の名前',
                'postal_code' => '150-0001',
                'address' => '東京都渋谷区',
            ]);

        $response->assertRedirect('/mypage/profile');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => '変更後の名前',
            'postal_code' => '150-0001',
            'address' => '東京都渋谷区',
        ]);
    }

    /**
     * ④ 名前が空の場合バリデーションエラー
     */
    public function test_name_is_required()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/mypage/profile')
            ->patch('/mypage/profile', [
                'name' => '',
                'postal_code' => '150-0001',
                'address' => '東京都渋谷区',
            ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * ④ 郵便番号が空の場合バリデーションエラー
     */
    public function test_postal_code_is_required()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/mypage/profile')
            ->patch('/mypage/profile', [
                'name' => '山田太郎',
                'postal_code' => '',
                'address' => '東京都渋谷区',
            ]);

        $response->assertSessionHasErrors('postal_code');
    }

    /**
     * ④ 住所が空の場合バリデーションエラー
     */
    public function test_address_is_required()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/mypage/profile')
            ->patch('/mypage/profile', [
                'name' => '山田太郎',
                'postal_code' => '150-0001',
                'address' => '',
            ]);

        $response->assertSessionHasErrors('address');
    }
}
