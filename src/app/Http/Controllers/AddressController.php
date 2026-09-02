<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Item;

class AddressController extends Controller
{
    public function edit($itemId)
    {
        $item = Item::findOrFail($itemId);

        $user = auth()->user();

        return view(
            'purchase.address.edit',
            compact(
                'item',
                'user'
            )
        );
    }

    public function update(
        AddressRequest $request,
        $itemId
    ) {
        session([
            'purchase_address' => [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ],
        ]);

        return redirect()
            ->route(
                'purchase.create',
                ['item_id' => $itemId]
            );
    }
}
