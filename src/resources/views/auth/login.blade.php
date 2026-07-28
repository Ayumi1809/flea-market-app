@extends('layouts.app')

@section('title','ログイン')

@section('content')

<h2>ログイン</h2>

<form method="POST" action="{{ route('login') }}">

    @csrf

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

    <button>

        ログイン

    </button>

</form>

<a href="/register">

会員登録はこちら

</a>

@endsection