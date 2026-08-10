@extends('layouts.app')

@section('title', '商品一覧')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <div class="items">

        <div class="items__tabs">

            <a href="{{ route('items.index', ['keyword' => request('keyword')]) }}"
                class="{{ $tab !== 'mylist' ? 'active' : '' }}">
                おすすめ
            </a>

            @auth
                <a href="{{ route('items.index', [
                    'tab' => 'mylist',
                    'keyword' => $keyword,
                ]) }}"
                    class="{{ $tab === 'mylist' ? 'active' : '' }}">
                    マイリスト
                </a>
            @endauth

        </div>

        <div class="items__content">

            @forelse ($items as $item)

                <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="item-card">

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

                    @if($item->purchase)
                        <span class="item-card__sold">Sold</span>
                    @endif

                </a>

            @empty

                <p>
                    商品がありません。
                </p>

            @endforelse

        </div>

    </div>

@endsection