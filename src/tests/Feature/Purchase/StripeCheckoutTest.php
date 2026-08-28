<?php

namespace Tests\Feature\Purchase;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Stripe\ApiRequestor;
use Stripe\HttpClient\CurlClient;
use Stripe\Stripe;
use Tests\TestCase;

class StripeCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_stripe_checkout_redirect_success()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'price' => 1000,
            'status' => 'selling',
        ]);

        $this->actingAs($user);

        Stripe::setApiKey('sk_test_mock');

        $mockHttpClient = Mockery::mock(CurlClient::class)->makePartial();

        $mockHttpClient
            ->shouldReceive('request')
            ->once()
            ->withArgs(function (
                $method,
                $url,
                $headers,
                $params,
                $hasFile,
                $apiMode,
                $maxNetworkRetries
            ) {
                return $method === 'post'
                    && str_contains(
                        $url,
                        '/v1/checkout/sessions'
                    )
                    && $params['payment_method_types'][0] === 'card';
            })
            ->andReturn([
                json_encode([
                    'id' => 'cs_test_mock',
                    'object' => 'checkout.session',
                    'url' => 'http://stripe.test/checkout',
                ]),
                200,
                [],
            ]);

        ApiRequestor::setHttpClient($mockHttpClient);

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
            'http://stripe.test/checkout'
        );

        $this->assertEquals(
            'カード支払い',
            session('payment_method')
        );
    }

    public function test_stripe_checkout_with_konbini()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'price' => 1000,
            'status' => 'selling',
        ]);

        $this->actingAs($user);

        Stripe::setApiKey('sk_test_mock');

        $mockHttpClient = Mockery::mock(CurlClient::class)->makePartial();

        $mockHttpClient
            ->shouldReceive('request')
            ->once()
            ->withArgs(function (
                $method,
                $url,
                $headers,
                $params,
                $hasFile,
                $apiMode,
                $maxNetworkRetries
            ) {
                return $method === 'post'
                    && str_contains(
                        $url,
                        '/v1/checkout/sessions'
                    )
                    && $params['payment_method_types'][0] === 'konbini';
            })
            ->andReturn([
                json_encode([
                    'id' => 'cs_test_mock_konbini',
                    'object' => 'checkout.session',
                    'url' => 'http://stripe.test/checkout',
                ]),
                200,
                [],
            ]);

        ApiRequestor::setHttpClient($mockHttpClient);

        $response = $this->post(
            route(
                'purchase.checkout',
                [
                    'item_id' => $item->id,
                ]
            ),
            [
                'payment_method' => 'コンビニ払い',
            ]
        );

        $response->assertRedirect(
            'http://stripe.test/checkout'
        );

        $this->assertEquals(
            'コンビニ払い',
            session('payment_method')
        );
    }
}