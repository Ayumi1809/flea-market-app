<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Mockery;

class StripeCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_stripe_checkout_redirect_success()
    {

        $user = User::factory()->create();


        $condition = Condition::factory()->create();


        $item = Item::factory()->create([

            'user_id'=>$user->id,

            'condition_id'=>$condition->id,

            'name'=>'テスト商品',

            'price'=>5000,

            'status'=>'selling',

        ]);


        $this->actingAs($user);



        $response = $this->post(
            route(
                'purchase.checkout',
                $item->id
            ),
            [
                'payment_method'=>'card'
            ]
        );


        // successページへリダイレクト確認

        $response->assertRedirect(
            route(
                'purchase.success',
                [
                    'item_id'=>$item->id
                ]
            )
        );


        // 支払い方法がsession保存されているか

        $this->assertEquals(
            'card',
            session('payment_method')
        );


    }
}
