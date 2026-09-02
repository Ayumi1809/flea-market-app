@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')
    <div class="sell-container">
        <h1 class="page-title">
            商品の出品
        </h1>

        <form
            action="{{ route('items.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="form-group">
                <label
                    for="image"
                    class="form-label required"
                >
                    商品画像
                </label>

                <div class="image-upload">
                    <label
                        for="image"
                        class="image-button"
                    >
                        画像を選択する
                    </label>

                    <input
                        type="file"
                        name="image"
                        id="image"
                        class="image-input"
                    >

                    <p
                        id="file-name"
                        class="file-name"
                    ></p>
                </div>

                @error('image')
                    <p class="error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <section class="form-section">
                <h2 class="sub-title">
                    商品の詳細
                </h2>

                <div class="form-group">
                    <label class="form-label required">
                        カテゴリー
                    </label>

                    <div class="category-list">
                        @foreach($categories as $category)
                            <label
                                class="category-tag"
                            >

                                <input
                                    type="checkbox"
                                    name="categories[]"
                                    value="{{ $category->id }}"
                                    hidden
                                    {{
                                        in_array(
                                            $category->id,
                                            old('categories', [])
                                        )
                                        ? 'checked'
                                        : ''
                                    }}
                                >

                                <span>
                                    {{ $category->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                    @error('categories')
                        <p class="error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label required">
                        商品の状態
                    </label>

                    <div
                        class="condition-select"
                        id="condition-select"
                    >

                        <input
                            type="hidden"
                            name="condition_id"
                            id="condition_id"
                            value="{{ old('condition_id') }}"
                        >

                        <button
                            type="button"
                            class="condition-selected"
                            id="condition-selected"
                        >

                            <span
                                id="condition-selected-text"
                            >
                                選択してください
                            </span>

                            <span
                                class="condition-arrow"
                            >
                                ▼
                            </span>
                        </button>

                        <div
                            class="condition-options"
                            id="condition-options"
                        >

                            @foreach($conditions as $condition)
                                <div
                                    class="condition-option
                                    {{
                                        old('condition_id') == $condition->id
                                        ? 'selected'
                                        : ''
                                    }}"
                                    data-value="{{ $condition->id }}"
                                >

                                    <span
                                        class="condition-check"
                                    >
                                        ✓
                                    </span>

                                    <span>
                                        {{ $condition->name }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @error('condition_id')
                        <p class="error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </section>

            <section class="form-section">
                <h2 class="sub-title">
                    商品名と説明
                </h2>

                <div class="form-group">
                    <label
                        for="name"
                        class="form-label required"
                    >
                        商品名
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="input"
                        value="{{ old('name') }}"
                    >

                    @error('name')
                        <p class="error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="form-group">
                    <label
                        for="brand_name"
                        class="form-label"
                    >
                        ブランド名
                    </label>

                    <input
                        type="text"
                        id="brand_name"
                        name="brand_name"
                        class="input"
                        value="{{ old('brand_name') }}"
                    >
                </div>

                <div class="form-group">
                    <label
                        for="description"
                        class="form-label required"
                    >
                        商品説明
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        class="textarea"
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <p class="error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="form-group">
                    <label
                        for="price"
                        class="form-label required"
                    >
                        販売価格
                    </label>

                    <div class="price-area">
                        <span>
                            ¥
                        </span>

                        <input
                            type="number"
                            id="price"
                            name="price"
                            class="price-input"
                            value="{{ old('price') }}"
                        >
                    </div>

                    @error('price')
                        <p class="error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </section>

            <div class="button-area">
                <button
                    type="submit"
                    class="submit-button"
                >
                    出品する
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const imageInput =
                document.getElementById('image');

            const fileName =
                document.getElementById('file-name');

            const conditionSelect =
                document.getElementById('condition-select');

            const selectedButton =
                document.getElementById('condition-selected');

            const selectedText =
                document.getElementById('condition-selected-text');

            const options =
                document.getElementById('condition-options');

            const hiddenInput =
                document.getElementById('condition_id');

            imageInput.addEventListener('change', function () {
                if (this.files.length > 0) {
                    fileName.textContent =
                        this.files[0].name;
                }
            });

            const selectedOption =
                options.querySelector('.condition-option.selected');

            if (selectedOption) {
                selectedText.textContent =
                    selectedOption
                        .querySelector('span:last-child')
                        .textContent
                        .trim();
            }

            selectedButton.addEventListener('click', function () {
                conditionSelect.classList.toggle('open');
            });

            options.addEventListener('click', function (event) {
                const option =
                    event.target.closest('.condition-option');

                if (!option) {
                    return;
                }

                const value =
                    option.dataset.value;

                const text =
                    option
                        .querySelector('span:last-child')
                        .textContent
                        .trim();

                hiddenInput.value = value;

                selectedText.textContent = text;

                options
                    .querySelectorAll('.condition-option')
                    .forEach(function (item) {
                        item.classList.remove('selected');
                    });

                option.classList.add('selected');

                conditionSelect.classList.remove('open');
            });

            document.addEventListener('click', function (event) {
                if (!conditionSelect.contains(event.target)) {
                    conditionSelect.classList.remove('open');
                }
            });
        });
    </script>
@endsection