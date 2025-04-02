<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧画面（トップ）</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
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
                {{-- タブ情報を引き継ぐため hidden フィールドを追加する例 --}}
                <input type="hidden" name="tab" value="{{ $tab }}">
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

    <main class="container">
        <!-- タブ切り替えナビゲーション -->
        <div class="nav-bar">
            <a href="{{ route('item.index', ['tab' => 'recommend']) }}"
               class="nav-item {{ $tab === 'recommend' ? 'active' : '' }}">
                おすすめ
            </a>
            <a href="{{ route('item.index', ['tab' => 'mylist']) }}"
               class="nav-item {{ $tab === 'mylist' ? 'active' : '' }}">
                マイリスト
            </a>
        </div>

                <!-- 商品一覧 -->
        <div class="item-grid">
            @if ($tab === 'mylist' && !Auth::check())
            @else
            @forelse ($items as $item)
                <div class="item-card">
                    <a href="{{ route('item.show', ['item_id' => $item->id]) }}">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="item-image">
                    </a>
                    <p class="item-name">{{ $item->name }}</p>
                    
                    <!-- 購入済みの場合 "Sold" を表示 -->
                    @if ($item->purchase)
                        <p class="sold-label">Sold</p>
                    @endif
                </div>
            @empty
                <p>商品が見つかりませんでした。</p>
            @endforelse
            @endif
        </div>


        <!-- ページネーション -->
        <div class="pagination">
            {{ $items->links() }}
        </div>
    </main>

</body>
</html>
