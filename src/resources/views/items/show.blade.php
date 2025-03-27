<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧画面（トップ）</title>
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
</head>
<body>

    <!-- ヘッダー -->
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
  <form action="{{ route('logout') }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit" class="logout-btn">ログアウト</button>
  </form>
  <a href="{{ route('mypage') }}" class="mypage-btn">マイページ</a>
  <a href="{{ route('sell') }}" class="sell-btn">出品</a>
</nav>
        </div>
    </header>

<main class="product-detail-container">
  <!-- 左カラム：商品画像 -->
  <div class="left-column">
    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="product-image">
  </div>

  <!-- 右カラム：商品情報 + コメント欄 -->
  <div class="right-column">
    <!-- 商品情報 -->
    <div class="product-info">
      <h1 class="product-name">{{ $item->name }}</h1>
      <p class="product-brand">{{ $item->brand ?? 'ブランド未設定' }}</p>
      <p class="product-price">¥{{ number_format($item->price) }} <span class="tax-included">（税込）</span></p>

      <!-- いいね数・コメント数のアイコン -->
      <div class="icon-info">
          <div class="icon-box">
            @php
              $user = Auth::user();
              $isLiked = $user && $item->favorites()->where('user_id', $user->id)->exists();
            @endphp

            @if($isLiked)
              <!-- 既にいいね済み：赤い星、DELETEメソッド -->
              <form action="{{ route('item.unlike', ['item_id' => $item->id]) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="like-btn" style="background: none; border: none; cursor: pointer;">
                  <span class="icon icon-favorite" style="color: red;">★</span>
                </button>
              </form>
            @else
              <!-- 未いいね：灰色の星、POSTメソッド -->
              <form action="{{ route('item.like', ['item_id' => $item->id]) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="like-btn" style="background: none; border: none; cursor: pointer;">
                  <span class="icon icon-favorite" style="color: gray;">★</span>
                </button>
              </form>
            @endif
            <span class="icon-count">{{ $item->favorites->count() }}</span>
          </div>
          <div class="icon-box">
            <span class="icon icon-comment">💬</span>
            <span class="icon-count">{{ $item->comments->count() }}</span>
          </div>
        </div>


      <!-- 購入ボタン（小さめ） -->
      <a href="#" class="purchase-btn">購入手続きへ</a>

      <!-- 商品説明 -->
      <div class="product-section">
        <h2 class="section-title">商品説明</h2>
        <p class="section-text">{{ $item->description }}</p>
      </div>

      <!-- 商品情報（カテゴリ, 状態） -->
      <div class="product-section">
        <h2 class="section-title">商品情報</h2>
        <p class="section-text">カテゴリ：{{ $item->category->category ?? '' }}</p>
        <p class="section-text">商品の状態：{{ $item->status->name ?? '' }}</p>
      </div>
    </div>

    <!-- コメント欄（右側の下部に配置） -->
    <div class="product-comment">
      <h2 class="comment-title">コメント ({{ $item->comments->count() }})</h2>
      <div class="comment-list">
  @forelse ($item->comments as $comment)
    <div class="comment-item">
      <!-- ユーザー情報 -->
      <div class="user-info">
        @if($comment->user->profile_image)
          <img src="{{ asset('storage/' . $comment->user->profile_image) }}" alt="User Avatar" class="comment-avatar">
        @else
          <div class="comment-avatar no-image"></div>
        @endif
        <p class="comment-user">{{ $comment->user->name }}</p>
      </div>
      <!-- コメント本文（ユーザー情報の左端に合わせる） -->
      <div class="comment-text">
        <p class="comment-body">{{ $comment->comment }}</p>
      </div>
    </div>
  @empty
    <p class="no-comment">まだコメントはありません。</p>
  @endforelse
      </div>

      <!-- コメント投稿フォーム -->
        <form action="{{ route('item.comment', ['item_id' => $item->id]) }}" method="POST" class="comment-form">
          @csrf
          <p class=comment-title>商品へのコメント</p>
          <textarea name="comment" rows="3" placeholder="コメントを入力" >{{ old('comment') }}</textarea>
          @error('comment')
            <div class="error-message">{{ $message }}</div>
          @enderror
          <button type="submit" class="comment-submit">コメントを投稿する</button>
        </form>
    </div>
  </div>
</main>
