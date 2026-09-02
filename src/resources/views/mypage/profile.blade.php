@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage/profile.css') }}">
@endsection

@section('content')
    <div class="profile-form">
        <h2 class="profile-form__title">
            プロフィール設定
        </h2>

        <form
            class="profile-form__form"
            action="{{ route('profile.update') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PATCH')

            <div class="profile-form__image">
                <div class="profile-form__image-preview">
                    @if ($user->profile_image)
                        <img
                            src="{{ asset('storage/' . $user->profile_image) }}"
                            alt="プロフィール画像">
                    @else
                        <img
                            src="{{ asset('images/default-profile.png') }}"
                            alt="デフォルト画像">
                    @endif
                </div>

                <label class="profile-form__image-button">
                    画像を選択する
                    <input
                        type="file"
                        name="profile_image"
                        accept="image/png,image/jpeg"
                    >
                </label>

                @error('profile_image')
                    <p class="error-message">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">
                    ユーザー名
                </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                    >

                @error('name')
                    <p class="error-message">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="postal_code">
                    郵便番号
                </label>
                    <input
                        type="text"
                        id="postal_code"
                        name="postal_code"
                        value="{{ old('postal_code', $user->postal_code) }}"
                    >

                @error('postal_code')
                    <p class="error-message">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">
                    住所
                </label>
                    <input
                        type="text"
                        id="address"
                        name="address"
                        value="{{ old('address', $user->address) }}"
                    >

                @error('address')
                    <p class="error-message">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="building">
                    建物名
                </label>
                    <input
                        type="text"
                        id="building"
                        name="building"
                        value="{{ old('building', $user->building) }}"
                    >

                @error('building')
                    <p class="error-message">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button
                type="submit"
                class="profile-form__button"
            >
                更新する
            </button>
        </form>
    </div>
@endsection