<?php

namespace Tests\Feature\Purchase;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_method_can_be_selected()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(
            route(
                'purchase.checkout',
                [
                    'item_id' => $item->id,
                ]
            ),
            [
                'payment_method' => 'カード支払い',
            ]
        );

        $response->assertRedirect(
            route(
                'purchase.success',
                [
                    'item_id' => $item->id,
                ]
            )
        );

        $this->assertEquals(
            'カード支払い',
            session('payment_method')
        );
    }
}