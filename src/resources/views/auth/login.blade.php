@extends('layouts.app')

@section('title', 'ログイン')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
    <div class="login-container">
        <h2 class="login-title">
            ログイン
        </h2>

        <form
            method="POST"
            action="{{ route('login') }}"
            class="login-form"
        >
            @csrf

            <div class="login-form__group">
                <label
                    for="email"
                    class="login-form__label"
                >
                    メールアドレス
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="login-form__input"
                >

                @error('email')
                    <p class="login-form__error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="login-form__group">
                <label
                    for="password"
                    class="login-form__label"
                >
                    パスワード
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    class="login-form__input"
                >

                @error('password')
                    <p class="login-form__error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button
                type="submit"
                class="login-form__button"
            >
                ログインする
            </button>
        </form>

        <a
            href="{{ route('register') }}"
            class="login-register-link"
        >
            会員登録はこちら
        </a>
    </div>
@endsection