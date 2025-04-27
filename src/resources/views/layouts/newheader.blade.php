<!-- ヘッダー -->
<header class="auth-header">
  <div class="header-container">
    <!-- 左側：ロゴ -->
    <div class="header-left">
      <a href="{{ route('item.index') }}">
        <img src="{{ asset('svg/logo.svg') }}" alt="COACHTECHロゴ" class="header-logo">
      </a>
    </div>

    @unless(Route::is('login', 'register'))
      <!-- 中央：検索フォーム -->
      <div class="header-center">
        <form action="{{ route('item.index') }}" method="GET" class="search-form">
          <input
            type="text"
            name="search"
            placeholder="なにをお探しですか？"
            class="search-box"
            value="{{ $search ?? '' }}"
          >
          <input type="hidden" name="tab" value="{{ $tab ?? 'recommend' }}">
        </form>
      </div>

      <!-- 右側：ナビゲーション -->
      <div class="header-right">
        <nav class="nav-links">
          @auth
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
              @csrf
              <button type="submit" class="logout-btn">ログアウト</button>
            </form>
          @else
            <a href="{{ route('login') }}" class="login-btn">ログイン</a>
          @endauth

          <a href="{{ route('mypage') }}" class="mypage-btn">マイページ</a>
          <a href="{{ route('sell') }}" class="sell-btn">出品</a>
        </nav>
      </div>
    @endunless

  </div>
</header>
