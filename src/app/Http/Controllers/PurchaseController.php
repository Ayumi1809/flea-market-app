<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PurchaseController extends Controller
{
    public function create($itemId)
    {
        $item = Item::with('purchase')
            ->findOrFail($itemId);

        if ($item->status === 'sold') {
            abort(403);
        }

        if ($item->purchase) {
            abort(403);
        }

        $user = auth()->user();

        return view(
            'purchase.create',
            compact(
                'item',
                'user'
            )
        );
    }

    public function store(PurchaseRequest $request, $itemId)
    {
        $item = Item::findOrFail($itemId);

        $this->completePurchase(
            $item,
            $request->payment_method
        );

        return redirect()
            ->route('items.index');
    }

    public function checkout(
        Request $request,
        $itemId
    ) {
        $item = Item::findOrFail($itemId);

        if ($item->status === 'sold') {
            abort(403);
        }

        if ($item->purchase()->exists()) {
            abort(403);
        }

        session([
            'payment_method' => $request->payment_method,
        ]);

        $stripePaymentMethod = match (
            $request->payment_method
        ) {

            'カード支払い' => 'card',

            'コンビニ払い' => 'konbini',

            default => abort(
                400,
                '不正な支払い方法です。'
            ),

        };

        Stripe::setApiKey(
            config('services.stripe.secret')
        );

        $session = Session::create([
            'payment_method_types' => [
                $stripePaymentMethod,
            ],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route(
                'purchase.success',
                [
                    'item_id' => $item->id,
                ]
            ),
            'cancel_url' => route(
                'purchase.cancel'
            ),
        ]);

        return redirect($session->url);
    }

    public function success($itemId)
    {
        $item = Item::findOrFail($itemId);

        $paymentMethod = session('payment_method');

        if (!$paymentMethod) {
            abort(400);
        }

        $this->completePurchase(
            $item,
            $paymentMethod
        );

        session()->forget('payment_method');

        return redirect()
            ->route('items.index')
            ->with(
                'success',
                '商品を購入しました。'
            );
    }

    private function completePurchase(
        Item $item,
        $paymentMethod
    ): void {
        DB::transaction(function () use (
            $item,
            $paymentMethod
        ) {
            if ($item->status === 'sold') {
                abort(403);
            }

            if ($item->purchase()->exists()) {
                abort(403);
            }

            Purchase::create([
                'user_id' => auth()->id(),
                'item_id' => $item->id,
                'payment_method' => $paymentMethod,
                'postal_code' => session(
                    'purchase_address.postal_code',
                    auth()->user()->postal_code
                ),
                'address' => session(
                    'purchase_address.address',
                    auth()->user()->address
                ),
                'building' => session(
                    'purchase_address.building',
                    auth()->user()->building
                ),
            ]);

            $item->update([
                'status' => 'sold',
            ]);

            session()->forget('purchase_address');
        });
    }

    public function cancel()
    {
        return redirect()
            ->route('items.index');
    }
}
