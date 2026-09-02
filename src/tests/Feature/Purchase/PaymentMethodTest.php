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

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_payment_method_can_be_selected()
    {
        $seller = User::factory()->create();

        $buyer = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'price' => 1000,
            'status' => 'selling',
        ]);

        $this->actingAs($buyer);

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
                    && str_contains($url, '/v1/checkout/sessions')
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
            route('purchase.checkout', ['item_id' => $item->id]),
            ['payment_method' => 'カード支払い']
        );

        $response->assertRedirect('http://stripe.test/checkout');

        $this->assertEquals('カード支払い', session('payment_method'));
    }
}
