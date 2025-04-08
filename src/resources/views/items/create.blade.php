<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>マイページ</title>
  <link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
</head>
<body>
@extends('layouts.newapp')

@section('title', '商品出品')

@section('content')
<div class="product-create-container">
    <h1>商品の出品</h1>

    <!-- フォーム -->
    <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data" class="sell-form">
        @csrf

        <!-- 商品画像セクション（フォーム内に移動） -->
        <div class="section-title section-title-image">商品画像</div>
        
        <div class="image-upload-area">
            <div class="image-preview-container">
                <img id="preview-image" class="image-preview" src="" alt="画像プレビュー" style="display: none;">
                <label class="image-upload-label">
                    画像を選択する
                    <input type="file" id="image" name="image" accept="image/*" hidden>
                </label>
            </div>
        </div>

        <!-- その他のフォーム項目 -->
        <div class="section-title section-title-detail">商品の詳細</div>
        <hr class="section-line" />

        <!-- カテゴリー -->
        <div class="form-group">
            <label>カテゴリー</label>
            <div class="category-list">
                @foreach($categories as $category)
                    <label class="category-label">
                        <input type="checkbox" name="category_id[]" value="{{ $category->id }}"
                            {{ is_array(old('category_id')) && in_array($category->id, old('category_id')) ? 'checked' : '' }}>
                        <span class="category-text">{{ $category->category }}</span>
                    </label>
                @endforeach
            </div>
            @error('category_id')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- 商品の状態 -->
        <div class="form-group">
            <label for="status_id">商品の状態</label>
            <select name="status_id" id="status_id">
                <option value="">選択してください</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
            @error('status_id')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- 商品名と説明 -->
        <div class="section-title">商品名と説明</div>
        <hr class="section-line" />

        <!-- 商品名 -->
        <div class="form-group">
            <label for="name">商品名</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">
            @error('name')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- ブランド名 -->
        <div class="form-group">
            <label for="brand">ブランド名</label>
            <input type="text" name="brand" id="brand" value="{{ old('brand') }}">
            @error('brand')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- 商品説明 -->
        <div class="form-group">
            <label for="description">商品説明</label>
            <textarea name="description" id="description" rows="5">{{ old('description') }}</textarea>
            @error('description')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- 販売価格(¥記号つき) -->
        <div class="form-group">
            <label for="price">販売価格</label>
            <div class="price-input-wrapper">
                <span class="yen-symbol">¥</span>
                <input type="number" name="price" id="price" value="{{ old('price') }}" step="1">
            </div>
            @error('price')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- 出品ボタン -->
        <button type="submit" class="submit-button">出品する</button>
    </form>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('image');
    const previewImage = document.getElementById('preview-image');
    const uploadLabel = document.querySelector('.image-upload-label');

    // 初期状態の設定
    if (previewImage) {
        previewImage.src = "";
        previewImage.style.display = "none";
    }

    if (imageInput) {
        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewImage) {
                        previewImage.src = e.target.result;
                        previewImage.style.display = "block"; // 画像を表示
                    }
                    if (uploadLabel) {
                        uploadLabel.style.display = "none"; // ラベルを非表示にする
                    }
                }; // reader.onloadの閉じ括弧
                reader.readAsDataURL(file);
            } else {
                // 未選択または画像以外の場合は元に戻す
                if (previewImage) {
                    previewImage.src = "";
                    previewImage.style.display = "none";
                }
                if (uploadLabel) {
                    uploadLabel.style.display = "block";
                }
            }
        });
    }
});
</script>
@endsection

</body>
</html>
