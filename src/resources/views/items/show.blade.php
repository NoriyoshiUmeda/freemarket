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
  <form action="{{ route('logout') }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit" class="logout-btn">ログアウト</button>
  </form>
  <a href="{{ route('mypage') }}" class="mypage-btn">マイページ</a>
  <a href="{{ route('sell') }}" class="sell-btn">出品</a>
</nav>
        </div>
    </header>