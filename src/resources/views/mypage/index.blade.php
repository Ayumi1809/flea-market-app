@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
    <div class="mypage">
        <div class="mypage__profile">
            <div class="mypage__image">
                @if ($user->profile_image)
                    <img
                        src="{{ asset('storage/' . $user->profile_image) }}"
                        alt="プロフィール画像"
                    >
                @endif
            </div>

            <h2 class="mypage__name">
                {{ $user->name }}
            </h2>

            <a
                href="{{ route('profile.edit') }}"
                class="mypage__edit-button"
            >
                プロフィールを編集
            </a>
        </div>

        <div class="mypage__tabs">

            <a
                href="{{ route('mypage', ['page' => 'sell']) }}"
                class="{{ $page === 'sell' ? 'active' : '' }}"
            >
                出品した商品
            </a>

            <a
                href="{{ route('mypage', ['page' => 'buy']) }}"
                class="{{ $page === 'buy' ? 'active' : '' }}"
            >
                購入した商品
            </a>

        </div>

        <div class="item-list">
            @forelse ($items as $item)
                <a
                    href="{{ route('items.show', $item->id) }}"
                    class="item-card"
        >
                    <div class="item-card__image">
                        @if ($item->image)
                            <img
                                src="{{ asset('storage/' . $item->image) }}"
                                alt="{{ $item->name }}"
                    >
                        @else
                            <div class="item-card__no-image">
                                商品画像
                            </div>
                        @endif
                    </div>
                    <p class="item-card__name">
                        {{ $item->name }}
                    </p>
                </a>

            @empty
                <p>商品がありません。</p>
            @endforelse

        </div>

    </div>
@endsection