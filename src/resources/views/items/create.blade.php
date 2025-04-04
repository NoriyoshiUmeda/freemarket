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

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- 商品画像セクション -->
    <div class="section-title">商品画像</div>
    
    <div class="image-upload-area">
        <div class="image-preview-container">
            <label class="image-upload-label">
                画像を選択する
                <input type="file" id="image" name="image" accept="image/*" hidden>
            </label>
        </div>
    </div>

    <!-- フォーム -->
    <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data" class="sell-form">
        @csrf

        <!-- 商品の詳細セクション -->
        <div class="section-title">商品の詳細</div>
        <hr class="section-line" />

        <!-- カテゴリー（複数選択） -->
        <div class="form-group">
            <label>カテゴリー</label>
            <div class="category-list">
                @foreach($categories as $category)
                    <label class="category-label">
                        <input type="checkbox" name="category_id[]" value="{{ $category->id }}"
                            {{ is_array(old('category_id')) && in_array($category->id, old('category_id')) ? 'checked' : '' }}>
                        {{ $category->category }}
                    </label>
                @endforeach
            </div>
            @error('category_id')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- 商品の状態（プルダウン） -->
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

        <!-- 商品名と説明セクション -->
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

        <!-- 販売価格 -->
        <div class="form-group">
            <label for="price">販売価格</label>
            <input type="number" name="price" id="price" value="{{ old('price') }}" step="1">
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

    // 画像未選択時は背景が白になるように、最初はsrcを空に
    // CSSで白背景を設定しておく
    previewImage.src = "";

    imageInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            // 未選択または画像以外なら空にする
            previewImage.src = "";
        }
    });
});
</script>
@endsection
