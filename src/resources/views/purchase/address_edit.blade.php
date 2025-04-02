<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>住所の変更</title>
  <link rel="stylesheet" href="{{ asset('css/address_edit.css') }}">
</head>
<body>

     <!-- 既存のヘッダー -->
  <header class="auth-header">
    <div class="header-container">
      <a href="{{ route('item.index') }}">
        <img src="{{ asset('svg/logo.svg') }}" alt="COACHTECHロゴ" class="header-logo">
      </a>
      <!-- 検索フォーム -->
      <form action="{{ route('item.index') }}" method="GET" class="search-form">
        <input type="text" name="search" placeholder="なにをお探しですか？" class="search-box">
      </form>
      <!-- ナビゲーションメニュー -->
      <nav class="nav-links">
        @auth
          <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn">ログアウト</button>
          </form>
        @endauth
        @guest
          <a href="{{ route('login') }}" class="login-btn">ログイン</a>
        @endguest
        <a href="{{ route('mypage') }}" class="mypage-btn">マイページ</a>
        <a href="{{ route('sell') }}" class="sell-btn">出品</a>
      </nav>
    </div>
  </header>

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
          value="{{ old('postal_code', $profile->postal_code) }}"
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
          value="{{ old('address', $profile->address) }}" 
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
          value="{{ old('building', $profile->building) }}"
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