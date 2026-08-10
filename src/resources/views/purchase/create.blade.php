@extends('layouts.app')

@section('title', '商品購入')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/create.css') }}">
@endsection

@section('content')

<div class="purchase">

        <div class="purchase__container">

            {{-- 左側 --}}
            <div class="purchase__left">

                {{-- 商品情報 --}}
                <div class="purchase-item">

                    <div class="purchase-item__image">

                        <img
                            src="{{ asset('storage/'.$item->image) }}"
                            alt="{{ $item->name }}"
                        >

                    </div>

                    <div class="purchase-item__info">

                        <h2>
                            {{ $item->name }}
                        </h2>

                        <p class="price">

                            ¥{{ number_format($item->price) }}

                        </p>

                    </div>

                </div>

                <hr>

                {{-- 支払い方法 --}}
                <div class="purchase-section">

                    <label class="purchase-label">

                        支払い方法

                    </label>

                    <select
                        name="payment_method"
                        id="payment_method"
                        class="purchase-select"
                    >

                        <option value="">
                            選択してください
                        </option>

                        <option value="konbini"
                            @selected(old('payment_method') == 'konbini')
                        >
                            コンビニ払い
                        </option>

                        <option value="card"
                            @selected(old('payment_method') == 'card')
                        >
                            カード支払い
                        </option>

                    </select>

                    @error('payment_method')
                        <p class="error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <hr>

                {{-- 配送先 --}}
                <div class="purchase-section">

                    <div class="purchase-address__header">

                        <h3>

                            配送先

                        </h3>

                        <a
                            href="{{ route('purchase.address.edit',
                                ['item_id' => $item->id]
                            ) }}"
                        >

                            変更する

                        </a>

                    </div>

                    <p>

                        〒{{ session('purchase_address.postal_code', $user->postal_code) }}

                    </p>

                    <p>

                        {{ session('purchase_address.address', $user->address) }}

                    </p>

                    <p>

                        {{ session('purchase_address.building', $user->building) }}

                    </p>

                </div>

            </div>

            {{-- 右側 --}}
            <div class="purchase__right">

                <table class="purchase-table">

                    <tr>

                        <th>

                            商品代金

                        </th>

                        <td>

                            ¥{{ number_format($item->price) }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            支払い方法

                        </th>

                        <td id="payment_display">

                            -

                        </td>

                    </tr>

                </table>

                {{-- Stripe送信用form --}}
                <form
                    action="{{ route(
                        'purchase.checkout',
                        [
                            'item_id'=>$item->id
                        ]
                    ) }}"
                    method="POST"
                    id="purchase-form"
                >
                    @csrf

                    <input
                        type="hidden"
                        name="payment_method"
                        id="stripe_payment_method"
                    >

                        <button
                            type="submit"
                            class="purchase-button"
                        >

                            購入する

                        </button>
                </form>

            </div>

        </div>

    </form>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    // プルダウン
        const paymentSelect =
            document.getElementById(
                'payment_method'
            );


        // 右側表示部分
        const paymentDisplay =
            document.getElementById(
                'payment_display'
            );


        // Stripe送信用hidden
        const stripePayment =
            document.getElementById(
                'stripe_payment_method'
            );



        paymentSelect.addEventListener(
            'change',
            function(){


                // Stripeへ送る値をセット
                stripePayment.value =
                    this.value;



                // 右側の表示変更
                if(this.value === 'konbini'){


                    paymentDisplay.textContent =
                        'コンビニ払い';


                }else if(this.value === 'card'){


                    paymentDisplay.textContent =
                        'カード支払い';


                }else{


                    paymentDisplay.textContent =
                        '-';


                }


            }
        );


    }

);
</script>

@endsection