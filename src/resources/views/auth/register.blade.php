@extends('layouts.app')

@section('title','会員登録')

@section('content')

<h2>会員登録</h2>

<form method="POST" action="/register">

    @csrf

    <div>
        <label>ユーザー名</label>

        <input
            type="text"
            name="name"
            value="{{ old('name') }}"
        >

        @error('name')
            <p>{{ $message }}</p>
        @enderror

    </div>

    <div>

        <label>メールアドレス</label>

        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
        >

        @error('email')
            <p>{{ $message }}</p>
        @enderror

    </div>

    <div>

        <label>パスワード</label>

        <input
            type="password"
            name="password"
        >

        @error('password')
            <p>{{ $message }}</p>
        @enderror

    </div>

    <div>

        <label>確認用パスワード</label>

        <input
            type="password"
            name="password_confirmation"
        >

    </div>

    <button type="submit">

        登録する

    </button>

</form>

<a href="/login">

ログインはこちら

</a>

@endsection