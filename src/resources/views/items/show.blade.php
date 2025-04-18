<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>商品詳細画面</title>
  <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
</head>
<body>

  @include('layouts.newapp')

  @if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
  @endif

  <main class="product-detail-container">
    {{-- 左カラム：商品画像 --}}
    <div class="left-column">
      <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="product-image">
    </div>

    {{-- 右カラム：商品情報 + コメント欄 --}}
    <div class="right-column">

      {{-- 商品情報 --}}
      <div class="product-info">
        <h1 class="product-name">{{ $item->name }}</h1>
        <p class="product-brand">{{ $item->brand ?? '' }}</p>
        <p class="product-price">
          ¥{{ number_format($item->price) }} <span class="tax-included">（税込）</span>
        </p>

        {{-- いいね数・コメント数のアイコン --}}
        <div class="icon-info">
          {{-- いいね部分 --}}
          <div class="icon-box">
            @php
              $user      = Auth::user();
              $isLiked   = $user && $item->favorites()->where('user_id', $user->id)->exists();
              $likeCount = $item->favorites->count();
            @endphp

            @if($isLiked)
              <form action="{{ route('item.unlike', ['item_id' => $item->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="like-btn liked">
                  <img src="{{ asset('storage/images/favorite.png') }}" alt="いいね済み" class="icon-img">
                </button>
              </form>
            @else
              <form action="{{ route('item.like', ['item_id' => $item->id]) }}" method="POST">
                @csrf
                <button type="submit" class="like-btn">
                  <img src="{{ asset('storage/images/favorite.png') }}" alt="いいね" class="icon-img">
                </button>
              </form>
            @endif

            {{-- いいね数が1以上のときだけ表示 --}}
            @if($likeCount > 0)
              <span class="icon-count">{{ $likeCount }}</span>
            @endif
          </div>

          {{-- コメント部分 --}}
          <div class="icon-box">
            <img src="{{ asset('storage/images/comment.png') }}" alt="コメント" class="icon-img">
            @php
              $commentCount = $item->comments->count();
            @endphp
            {{-- コメント数が1以上のときだけ表示 --}}
            @if($commentCount > 0)
              <span class="icon-count">{{ $commentCount }}</span>
            @endif
          </div>
        </div>

        {{-- 購入ボタン制御 --}}
        @if ($hasPurchased || $item->user_id == Auth::id())
          <span class="purchase-btn disabled">購入出来ません</span>
        @else
          <a href="{{ route('purchase.show', ['item_id' => $item->id]) }}" class="purchase-btn">
            購入手続きへ
          </a>
        @endif

        {{-- 商品説明 --}}
        <div class="product-section">
          <h2 class="section-title">商品説明</h2>
          <p class="section-text">{{ $item->description }}</p>
        </div>

        {{-- 商品情報（カテゴリ, 状態） --}}
        <div class="product-section">
          <h2 class="section-title">商品の情報</h2>
          <div class="section-text">
            <span class="bold-700">カテゴリ</span>：
            <div class="category-tags">
              @foreach ($categories as $cat)
                <span class="category-tag">{{ $cat->category }}</span>
              @endforeach
            </div>
          </div>
          <p class="section-text">
            <span class="bold-700">商品の状態</span>：{{ $item->status->name ?? '' }}
          </p>
        </div>
      </div>

      {{-- コメント欄 --}}
      <div class="product-comment">
        <h2 class="comment-title">コメント ({{ $commentCount }})</h2>
        <div class="comment-list">
          @forelse ($item->comments as $comment)
            <div class="comment-item">
              {{-- ユーザー情報 --}}
              <div class="user-info">
                @php
                  $profileImage = optional($comment->user->profile)->profile_image
                    ? asset('storage/' . $comment->user->profile->profile_image)
                    : null;
                @endphp

                @if($profileImage)
                  <img src="{{ $profileImage }}" alt="User Avatar" class="comment-avatar">
                @else
                  <div class="comment-avatar no-image"></div>
                @endif

                <p class="comment-user">{{ $comment->user->name }}</p>
              </div>
              {{-- コメント本文 --}}
              <div class="comment-text">
                <p class="comment-body">{{ $comment->comment }}</p>
              </div>
            </div>
          @empty
            <p class="no-comment">まだコメントはありません。</p>
          @endforelse
        </div>

        {{-- コメント投稿フォーム --}}
        <form action="{{ route('item.comment', ['item_id' => $item->id]) }}"
              method="POST"
              class="comment-form">
          @csrf
          <p class="comment-title">商品へのコメント</p>
          <textarea name="comment" rows="3"
                    placeholder="コメントを入力">{{ old('comment') }}</textarea>
          @error('comment')
            <div class="error-message">{{ $message }}</div>
          @enderror
          <div class="submit-wrapper">
            <button type="submit" class="comment-submit">コメントを送信する</button>
          </div>
        </form>
      </div>

    </div>
  </main>

</body>
</html>
