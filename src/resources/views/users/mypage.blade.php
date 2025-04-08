<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>マイページ</title>
  <link rel="stylesheet" href="{{ asset('css/users/mypage.css') }}">
</head>
<body>
  @include('layouts.newapp')

  <!-- メインコンテンツ -->
  <main class="container">

      <!-- プロフィール画像＆ユーザー名 -->
      <div class="profile-info">
        @php
          $profileImage = $user->profile && $user->profile->profile_image
            ? asset('storage/' . $user->profile->profile_image)
            : null;
        @endphp

         @if ($profileImage)
        <img src="{{ $profileImage }}"  class="profile-image">
        @else
        <!-- 画像なしの場合、グレーの丸を表示 -->
        <div class="profile-image no-image"></div>
        @endif
        <div class="profile-name-edit">
        <h2 class="user-name">{{ $user->name }}</h2>
        <a href="{{ route('profile.edit') }}" class="edit-profile-btn">プロフィールを編集</a>
      </div>
    </div>

    <!-- タブ切り替えナビゲーション -->
    <div class="nav-bar">
      <!-- 出品した商品一覧 -->
      <a href="{{ route('mypage', ['tab' => 'sell']) }}"
         class="nav-item {{ $mode === 'sell' ? 'active' : '' }}">
        出品した商品
      </a>
      <!-- 購入した商品一覧 -->
      <a href="{{ route('mypage', ['tab' => 'buy']) }}"
         class="nav-item {{ $mode === 'buy' ? 'active' : '' }}">
        購入した商品
      </a>
    </div>
    <hr class="full-width-line">

    <!-- 商品一覧 -->
    <div class="item-grid">
      @forelse ($items as $item)
        <div class="item-card">
          <!-- 商品詳細ページへのリンク -->
          <a href="{{ route('item.show', ['item_id' => $item->id]) }}">
            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="item-image">
          </a>
          <p class="item-name">{{ $item->name }}</p>
          <p class="item-price">¥{{ number_format($item->price) }}</p>
          @if ($item->purchase)
            <p class="sold-label">Sold</p>
          @endif
        </div>
      @empty
        @if ($mode === 'sell')
          <p>出品した商品はありません。</p>
        @else
          <p>購入した商品はありません。</p>
        @endif
      @endforelse
    </div>
  </main>
</body>
</html>
