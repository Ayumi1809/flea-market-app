@extends('layouts.app')

@section('title', '商品購入')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase/create.css') }}">
@endsection

@section('content')
<div class="purchase__container">
    <div class="purchase__left">
        <div class="purchase-item">
            <div class="purchase-item__image">
                @if ($item->image)
                    <img
                        src="{{ asset('storage/'.$item->image) }}"
                        alt="{{ $item->name }}"
                    >
                @else
                    <div class="no-image">
                        商品画像
                    </div>
                @endif
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

        <div class="purchase-section payment-section">
            <h3 class="purchase-section__title">
                支払い方法
            </h3>
                <div
                class="payment-dropdown"
                id="payment_dropdown"
                >

                    <button
                        type="button"
                        class="payment-dropdown__button"
                        id="payment_dropdown_button"
                        aria-haspopup="listbox"
                        aria-expanded="false"
                    >

                        <span id="payment_selected_text">
                            選択してください
                        </span>

                        <span class="payment-dropdown__arrow">
                            ▼
                        </span>
                    </button>

                    <div
                    class="payment-dropdown__menu"
                    id="payment_dropdown_menu"
                    role="listbox"
                    >

                        <div
                            class="payment-dropdown__option"
                            data-value="コンビニ払い"
                            role="option"
                        >
                            コンビニ払い
                        </div>

                        <div
                            class="payment-dropdown__option"
                            data-value="カード支払い"
                            role="option"
                        >
                            カード支払い
                        </div>
                    </div>
                </div>

            @error('payment_method')
                <p class="error">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="purchase-section purchase-address-section">
            <div class="purchase-address__header">
                <h3 class="purchase-section__title">
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

            <div class="purchase-address">
            <p>
                〒{{ session('purchase_address.postal_code', $user->postal_code) }}
            </p>

            <p>
                {{ session('purchase_address.address', $user->address) }}
            </p>
                @if (
                    session(
                        'purchase_address.building',
                        $user->building
                    )
                )
                    <p>
                        {{ session('purchase_address.building', $user->building) }}
                    </p>
                @endif
            </div>
        </div>
    </div>

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

        <form
            action="{{ route(
                'purchase.checkout',
                ['item_id' => $item->id]
            ) }}"
            method="POST"
            id="purchase-form"
        >
            @csrf

            <input
                type="hidden"
                name="payment_method"
                id="stripe_payment_method"
                value=""
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdown =
            document.getElementById('payment_dropdown');

        const dropdownButton =
            document.getElementById('payment_dropdown_button');

        const dropdownMenu =
            document.getElementById('payment_dropdown_menu');

        const selectedText =
            document.getElementById('payment_selected_text');

        const paymentDisplay =
            document.getElementById('payment_display');

        const stripePaymentMethod =
            document.getElementById('stripe_payment_method');

        const purchaseForm =
            document.getElementById('purchase-form');

        const options =
            document.querySelectorAll(
                '.payment-dropdown__option'
            );

        let currentValue = '';

        @if (old('payment_method') === 'コンビニ払い'
            || session('payment_method') === 'コンビニ払い')

            currentValue = 'コンビニ払い';

        @elseif (old('payment_method') === 'カード支払い'
            || session('payment_method') === 'カード支払い')

            currentValue = 'カード支払い';
        @endif

        function updatePaymentMethod(value) {
            currentValue = value;
            if (value === 'コンビニ払い') {
                selectedText.textContent =
                    'コンビニ払い';
                paymentDisplay.textContent =
                    'コンビニ払い';
                stripePaymentMethod.value =
                    'コンビニ払い';
            }

            else if (value === 'カード支払い') {
                selectedText.textContent =
                    'カード支払い';
                paymentDisplay.textContent =
                    'カード支払い';
                stripePaymentMethod.value =
                    'カード支払い';
            }

            else {
                selectedText.textContent =
                    '選択してください';
                paymentDisplay.textContent =
                    '-';
                stripePaymentMethod.value =
                    '';
            }

            options.forEach(function (option) {
                if (option.dataset.value === value) {
                    option.classList.add(
                        'is-selected'
                    );
                } else {
                    option.classList.remove(
                        'is-selected'
                    );
                }
            });
        }

        dropdownButton.addEventListener(
            'click',
            function () {
                dropdown.classList.toggle(
                    'is-open'
                );

                const isOpen =
                    dropdown.classList.contains(
                        'is-open'
                    );

                dropdownButton.setAttribute(
                    'aria-expanded',
                    isOpen ? 'true' : 'false'
                );
            }
        );

        options.forEach(function (option) {
            option.addEventListener(
                'click',
                function () {
                    const value =
                        option.dataset.value;

                    updatePaymentMethod(value);

                    dropdown.classList.remove(
                        'is-open'
                    );

                    dropdownButton.setAttribute(
                        'aria-expanded',
                        'false'
                    );
                }
            );
        });

        document.addEventListener(
            'click',
            function (event) {
                if (
                    !dropdown.contains(event.target)
                ) {
                    dropdown.classList.remove(
                        'is-open'
                    );

                    dropdownButton.setAttribute(
                        'aria-expanded',
                        'false'
                    );
                }
            }
        );

        updatePaymentMethod(
            currentValue
        );

        purchaseForm.addEventListener(
            'submit',
            function (event) {
                if (currentValue === '') {
                    event.preventDefault();

                    alert(
                        '支払い方法を選択してください。'
                    );

                    dropdownButton.focus();
                    return;
                }

                stripePaymentMethod.value =
                    currentValue;
            }
        );
    });
</script>
@endsection