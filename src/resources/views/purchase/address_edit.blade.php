<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>住所の変更</title>
  <link rel="stylesheet" href="{{ asset('css/address_edit.css') }}">
</head>
<body>
  @include('layouts.newapp') 
  <main class="address-edit-container">
    <h1 class="page-title">住所の変更</h1>

    <!-- 住所変更フォーム -->
    <form action="{{ route('purchase.address.update', ['item_id' => $item_id]) }}" method="POST" class="address-edit-form">
      @csrf
      @method('PUT')

      <!-- 郵便番号 -->
      <div class="form-group">
        <label for="postal_code">郵便番号</label>
        <input 
          type="text" 
          name="postal_code" 
          id="postal_code" 
          value="{{ old('postal_code', session('postal_code', $profile->postal_code)) }}"
          required maxlength="255"
        >
        @error('postal_code')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <!-- 住所 -->
      <div class="form-group">
        <label for="address">住所</label>
        <input 
          type="text" 
          name="address" 
          id="address" 
          value="{{ old('address', session('address', $profile->address)) }}"
          required maxlength="255"
        >
        @error('address')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <!-- 建物名 -->
      <div class="form-group">
        <label for="building">建物名</label>
        <input 
          type="text" 
          name="building" 
          id="building" 
          value="{{ old('building', session('building', $profile->building)) }}"
          maxlength="255"
        >
        @error('building')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <!-- 更新ボタン -->
      <button type="submit" class="update-btn">更新する</button>
    </form>
  </main>
</body>
</html>
