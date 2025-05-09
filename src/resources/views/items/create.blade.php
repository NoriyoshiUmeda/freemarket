<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>商品出品画面</title>
  <link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
</head>
<body>
  @extends('layouts.newapp')

  @section('title', '商品出品')

  @section('content')
  <div class="product-create-container">
    <h1>商品の出品</h1>

    <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data" class="sell-form">
      @csrf

      
      <div class="section-title section-title-image">商品画像</div>
      <div class="image-upload-area">
        <div class="image-preview-container" id="preview-container">
          
          <img id="preview-image"
               class="image-preview"
               src=""
               alt="画像プレビュー"
               style="display: none;">
          
          <input type="file" id="image" name="image" accept="image/*" hidden>
          
          <label for="image" class="image-upload-label">画像を選択する</label>
          
          <label for="image" class="image-change-label">変更する</label>
        </div>
      </div>
      @error('image')
        <p class="error-message">{{ $message }}</p>
      @enderror

      
      <div class="section-title section-title-detail">商品の詳細</div>
      <hr class="section-line">

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

      <div class="section-title">商品名と説明</div>
      <hr class="section-line">

      <div class="form-group">
        <label for="name">商品名</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}">
        @error('name') <p class="error-message">{{ $message }}</p> @enderror
      </div>

      <div class="form-group">
        <label for="brand">ブランド名</label>
        <input type="text" name="brand" id="brand" value="{{ old('brand') }}">
        @error('brand') <p class="error-message">{{ $message }}</p> @enderror
      </div>

      <div class="form-group">
        <label for="description">商品説明</label>
        <textarea name="description" id="description" rows="5">{{ old('description') }}</textarea>
        @error('description') <p class="error-message">{{ $message }}</p> @enderror
      </div>

      <div class="form-group">
        <label for="price">販売価格</label>
        <div class="price-input-wrapper">
          <span class="yen-symbol">¥</span>
          <input type="number" name="price" id="price" value="{{ old('price') }}" step="1">
        </div>
        @error('price') <p class="error-message">{{ $message }}</p> @enderror
      </div>

      <button type="submit" class="submit-button">出品する</button>
    </form>
  </div>
  @endsection

  @section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const imageInput       = document.getElementById('image');
      const previewImage     = document.getElementById('preview-image');
      const previewContainer = document.getElementById('preview-container');
      const form             = document.querySelector('.sell-form');




      const savedImageData = sessionStorage.getItem('selectedImage');
      if (savedImageData) {
        previewImage.src = savedImageData;
        previewImage.style.display = 'block';
        previewContainer.classList.add('has-image');
      }




      imageInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
          const reader = new FileReader();
          reader.onload = function (ev) {
            previewImage.src = ev.target.result;
            previewImage.style.display = 'block';
            previewContainer.classList.add('has-image');
            sessionStorage.setItem('selectedImage', ev.target.result);
          };
          reader.readAsDataURL(file);
        } else {
          previewImage.src = '';
          previewImage.style.display = 'none';
          previewContainer.classList.remove('has-image');
          sessionStorage.removeItem('selectedImage');
        }
      });




      window.addEventListener('beforeunload', function () {
        sessionStorage.removeItem('selectedImage');
      });




      form.addEventListener('submit', function () {
        sessionStorage.removeItem('selectedImage');
      });
    });
  </script>
  @endsection
</body>
</html>
