@extends('layouts.app')

@section('title', '会員登録')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
    <div class="register-container">
        <h2 class="register-title">
            会員登録
        </h2>

        <form
            method="POST"
            action="{{ route('register') }}"
            class="register-form"
        >
            @csrf

            <div class="register-form__group">
                <label
                    for="name"
                    class="register-form__label"
                >
                    ユーザー名
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="register-form__input"
                >

                @error('name')
                    <p class="register-form__error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="register-form__group">
                <label
                    for="email"
                    class="register-form__label"
                >
                    メールアドレス
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="register-form__input"
                >

                @error('email')
                    <p class="register-form__error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="register-form__group">
                <label
                    for="password"
                    class="register-form__label"
                >
                    パスワード
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    class="register-form__input"
                >

                @error('password')
                    <p class="register-form__error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="register-form__group">
                <label
                    for="password_confirmation"
                    class="register-form__label"
                >
                    確認用パスワード
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="register-form__input"
                >

                @error('password_confirmation')
                    <p class="register-form__error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button
                type="submit"
                class="register-form__button"
            >
                登録する
            </button>
        </form>

        <a
            href="{{ route('login') }}"
            class="register-login-link"
        >
            ログインはこちら
        </a>
    </div>
@endsection