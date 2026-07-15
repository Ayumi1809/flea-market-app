<!DOCTYPE html>
<html lang="ja">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'フリマアプリ')</title>

    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">

    <link rel="stylesheet" href="{{ asset('css/common.css') }}">

    @yield('css')

</head>

<body>

<header class="header">

    <div class="header__inner">

        <a href="/">
            coachtechフリマ
        </a>

        @auth

            <nav>

                <a href="/">商品一覧</a>

                <a href="/mypage">マイページ</a>

                <a href="/sell">出品</a>

                <form
                    action="{{ route('logout') }}"
                    method="POST">

                    @csrf

                    <button>

                        ログアウト

                    </button>

                </form>

            </nav>

        @endauth

        @guest

            <nav>

                <a href="{{ route('login') }}">
                    ログイン
                </a>

                <a href="{{ route('register') }}">
                    会員登録
                </a>

            </nav>

        @endguest

    </div>

</header>

<main>

    @yield('content')

</main>

</body>

</html>