@extends('layouts.app')

@section('title', '住所の変更')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase/address.css') }}">
@endsection

@section('content')

<div class="address">

    <h1 class="address__title">
        住所の変更
    </h1>

    <form
        action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}"
        method="POST"
        class="address-form"
    >

        @csrf
        @method('PATCH')

        {{-- 郵便番号 --}}
        <div class="form-group">

            <label for="postal_code">
                郵便番号
            </label>

            <input
                type="text"
                name="postal_code"
                id="postal_code"
                value="{{ old('postal_code', session('purchase_address.postal_code', auth()->user()->postal_code)) }}"
            >

            @error('postal_code')
                <p class="error">
                    {{ $message }}
                </p>
            @enderror

        </div>

        {{-- 住所 --}}
        <div class="form-group">

            <label for="address">
                住所
            </label>

            <input
                type="text"
                name="address"
                id="address"
                value="{{ old('address', session('purchase_address.address', auth()->user()->address)) }}"
            >

            @error('address')
                <p class="error">
                    {{ $message }}
                </p>
            @enderror

        </div>

        {{-- 建物名 --}}
        <div class="form-group">

            <label for="building">
                建物名
            </label>

            <input
                type="text"
                name="building"
                id="building"
                value="{{ old('building', session('purchase_address.building', auth()->user()->building)) }}"
            >

            @error('building')
                <p class="error">
                    {{ $message }}
                </p>
            @enderror

        </div>

        <button
            type="submit"
            class="address-form__button"
        >
            更新する
        </button>

    </form>

</div>

@endsection