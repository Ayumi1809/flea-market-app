<?php

namespace Tests\Feature\Purchase;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
                    'item_id'=>$item->id
                ]
            ),
            [
                'payment_method'=>'card'
            ]
        );

        $response->assertRedirect(
            route(
                'purchase.success',
                [
                    'item_id'=>$item->id
                ]
            )
        );

        $this->assertEquals(
            'card',
            session('payment_method')
        );
    }
}