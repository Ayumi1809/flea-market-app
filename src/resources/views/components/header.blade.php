<header class="header">
    <div class="header__inner">
        <h1 class="header__logo">
            <a href="{{ route('items.index') }}">
                <img
                    src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}"
                    alt="ロゴ"
                >
            </a>
        </h1>

        <form
            class="header__search"
            action="{{ route('items.index') }}"
            method="GET"
        >
            <input
                type="text"
                name="keyword"
                value="{{ request('keyword') }}"
                placeholder="なにをお探しですか？"
            >

            @if(request('tab') === 'mylist')
                <input
                    type="hidden"
                    name="tab"
                    value="mylist"
                >
            @endif
        </form>

        <nav class="header__nav">
            @guest
                <a href="{{ route('login') }}">
                    ログイン
                </a>

                <a href="{{ route('login') }}">
                    マイページ
                </a>
            @endguest

            @auth
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="header-nav__logout"
                    >
                        ログアウト
                    </button>
                </form>

                <a href="{{ route('mypage') }}">
                    マイページ
                </a>
            @endauth

            <a
                href="{{ route('items.create') }}"
                class="header-nav__sell"
            >
                出品
            </a>
        </nav>
    </div>
</header>