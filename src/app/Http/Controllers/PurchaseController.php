<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function create($item_id)
    {
        $item = Item::with('purchase')
        ->findOrFail($item_id);


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

    public function store(PurchaseRequest $request, $item_id)
    {
        DB::transaction(function () use ($request, $item_id) {

        $item = Item::findOrFail($item_id);

        Purchase::create([

            'user_id'=>auth()->id(),

            'item_id'=>$item->id,

            'payment_method' => $request->payment_method,

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

        $item->status = 'sold';

        $item->save();

        session()->forget('purchase_address');

    });

        return redirect()
            ->route('items.index');
    }


    public function checkout(
        Request $request,
        $item_id)
    {
        // PHPUnit実行時はStripeへ接続しない
        if (app()->environment('testing')) {

            session([
                'payment_method' => $request->payment_method
            ]);

            return redirect()->route(
                'purchase.success',
                [
                    'item_id' => $item_id
                ]
            );
        }


        session([
            'payment_method'=>$request->payment_method
        ]);

        $item = Item::findOrFail($item_id);

        Stripe::setApiKey(
            config('services.stripe.secret')
        );


        $session = Session::create([

            'payment_method_types' => [
                $request->payment_method,
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

                ]

            ],


            'mode' => 'payment',


            'success_url' => route(
                'purchase.success',
                $item->id
            ),


            'cancel_url' => route(
                'purchase.cancel'
            ),

        ]);


        return redirect(
            $session->url
        );

    }


    public function success($item_id)
    {
        $item = Item::findOrFail($item_id);

        $this->completePurchase($item);

        return redirect()
            ->route('items.index')
            ->with(
                'success',
                '商品を購入しました。'
            );

    }


    public function completePurchase(Item $item)
    {
        if (!session()->has('payment_method')) {

            session([
                'payment_method'=>'card'
            ]);

        }

        Purchase::create([

            'user_id' => auth()->id(),

            'item_id' => $item->id,

            'payment_method' => session('payment_method'),

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

            'status'=>'sold'

        ]);

        session()->forget('purchase_address');

    }


    public function cancel()
    {

        return redirect()
            ->route('items.index');

    }
}
