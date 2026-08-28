<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_edit_page_displays_current_information()
    {
        $user = User::factory()->create([
            'name' => '山田太郎',
            'profile_image' => 'profile.jpg',
            'postal_code' => '150-0001',
            'address' => '東京都渋谷区',
            'building' => '渋谷マンション',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(
                route('profile.edit')
            );

        $response->assertStatus(200);

        $response->assertSee('山田太郎');

        $response->assertSee('150-0001');

        $response->assertSee('東京都渋谷区');

        $response->assertSee('渋谷マンション');

        $response->assertSee('profile.jpg');
    }
}