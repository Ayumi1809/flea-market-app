@extends('layouts.app')

@section('title', $item->name)

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')

<div class="item-detail">

    {{-- 商品画像 --}}
    <div class="item-detail__image">

        @if($item->image)
            <img
                src="{{ asset('storage/' . $item->image) }}"
                alt="{{ $item->name }}"
            >
        @else
            <div class="item-detail__no-image">
                商品画像
            </div>
        @endif

    </div>

    {{-- 商品情報 --}}
    <div class="item-detail__content">

        <h1 class="item-detail__name">
            {{ $item->name }}
        </h1>

        <p class="item-detail__brand">
            {{ $item->brand_name }}
        </p>

        <p class="item-detail__price">
            ¥{{ number_format($item->price) }}
            <span>（税込）</span>
        </p>

        {{-- いいね・コメント --}}
        <div class="item-detail__status">

        {{-- いいね --}}
        <div class="status-box">

            @auth

                @if($item->isFavoriteBy(auth()->user()))

                    <form
                        action="{{ route('favorites.destroy', $item->id) }}"
                        method="POST"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit"   class="favorite-button active">
                            <img
                                src="{{ asset('images/icons/heart-active.png') }}"
                                alt="いいね済み"
                            >
                        </button>
                    </form>

                @else

                    <form
                        action="{{ route('favorites.store', $item->id) }}"
                        method="POST"
                    >
                        @csrf

                        <button type="submit"       class="favorite-button">
                            <img
                                src="{{ asset('images/icons/heart.png') }}"
                                alt="いいね"
                            >
                        </button>
                    </form>

                @endif

            @endauth

            @guest

                <span class="favorite-button">
                    <img
                        src="{{ asset('images/icons/heart.png') }}"
                        alt="いいね"
                    >
                </span>

            @endguest

            <p>{{ $item->favorites->count() }}</p>

        </div>

        {{-- コメント --}}
        <div class="status-box">

            <img
                src="{{ asset('images/icons/comment.png') }}"
                alt="コメント"
                class="status-icon"
            >

            <p>{{ $item->comments->count() }}</p>

        </div>

    </div>

        {{-- 購入ボタン --}}
    @if(!$item->purchase)

        <a
            href="{{ route('purchase.create', ['item_id' => $item->id]) }}"
            class="purchase-button"
        >
            購入手続きへ
        </a>

    @else

        <p>
            売り切れました
        </p>

    @endif

        {{-- 商品説明 --}}
        <section class="item-section">

            <h2>商品説明</h2>

            <p>
                {{ $item->description }}
            </p>

        </section>

        {{-- 商品情報 --}}
        <section class="item-section">

            <h2>商品の情報</h2>

            <div class="info-row">

                <span class="info-title">
                    カテゴリー
                </span>

                <div class="category-list">

                    @foreach($item->categories as $category)

                        <span class="category-tag">

                            {{ $category->name }}

                        </span>

                    @endforeach

                </div>

            </div>

            <div class="info-row">

                <span class="info-title">
                    商品の状態
                </span>

                <span>

                    {{ $item->condition->name }}

                </span>

            </div>

        </section>

        {{-- コメント一覧 --}}
        <section class="item-section">

            <h2>

                コメント（{{ $item->comments->count() }}）

            </h2>

            @forelse($item->comments as $comment)

                <div class="comment">

                    <div class="comment-user">

                        @if($comment->user->profile_image)

                            <img
                                src="{{ asset('storage/' . $comment->user->profile_image) }}"
                                class="comment-user__image"
                            >

                        @else

                            <div class="comment-user__icon">

                            </div>

                        @endif

                        <strong>

                            {{ $comment->user->name }}

                        </strong>

                    </div>

                    <div class="comment-body">

                        {{ $comment->comment }}

                    </div>

                </div>

            @empty

                <p>

                    コメントはまだありません。

                </p>

            @endforelse

        </section>

        {{-- コメントフォーム --}}
        <section class="item-section">

            <h2>

                商品へのコメント

            </h2>

            @auth

            <form
                action="{{ route('comments.store', ['item_id' => $item->id]) }}"
                method="POST"
            >

                @csrf

                <textarea
                    name="comment"
                    rows="5"
                >{{ old('comment') }}</textarea>

                @error('comment')

                    <p class="error">

                        {{ $message }}

                    </p>

                @enderror

                <button
                    type="submit"
                    class="comment-button"
                >

                    コメントを送信する

                </button>

            </form>

            @else

                <p>

                    コメントするにはログインしてください。

                </p>

            @endauth

        </section>

    </div>

</div>

@endsection