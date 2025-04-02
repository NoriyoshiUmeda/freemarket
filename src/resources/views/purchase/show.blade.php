<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>商品購入画面</title>
  <!-- 購入画面用のCSS -->
  <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
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

  <main class="purchase-container">

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="purchase-main">
      <!-- 左カラム：商品情報・支払方法選択・配送先 -->
      <div class="left-info">
        <!-- 商品情報セクション：商品画像と商品名・価格を横並びに配置 -->
        <div class="section product-info-section">
          <div class="product-image-wrapper">
            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="product-image">
          </div>
          <div class="product-text">
            <div class="product-name">{{ $item->name }}</div>
            <!-- 商品名の下に価格を表示 -->
            <div class="product-price-left">¥{{ number_format($item->price) }}</div>
          </div>
        </div>
        <!-- 支払方法選択セクション -->
        <div class="section">
          <span class="label">支払方法</span>
          <div class="content">
            <form id="payment-form" action="{{ route('purchase.show', ['item_id' => $item->id]) }}" method="GET">
              <select name="payment_method" id="payment_method">
                <option value="credit_card" @if(request('payment_method', 'credit_card') === 'credit_card') selected @endif>カード支払い</option>
                <option value="convenience_store" @if(request('payment_method') === 'convenience_store') selected @endif>コンビ二支払い</option>
              </select>
            </form>
          </div>
        </div>
        <!-- 配送先セクション -->
        <div class="section shipping-section">
          <span class="label">配送先</span>
          <a href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}" class="address-change-btn">変更する</a>
          <div class="content">
            @if(isset($user) && $user->profile)
              〒{{ $user->profile->postal_code }}<br>
              {{ $user->profile->address }}
            @else
              住所情報が登録されていません。
            @endif
          </div>
        </div>
      </div>

      <!-- 右カラム -->
      <div class="right-info">
        <div class="price-method-box">
          @php
            $paymentMethod = request('payment_method', 'credit_card');
            $methodLabels = [
              'credit_card' => 'カード支払い',
              'convenience_store' => 'コンビニ支払い',
              ];
          @endphp
          <!-- 横並びの行：商品代金 -->
          <div class="info-row">
            <span class="label-row">商品代金</span>
            <span class="price-row">¥{{ number_format($item->price) }}</span>
          </div>

          <hr class="separator">
          <!-- 横並びの行：支払方法 -->
          <div class="info-row">
            <span class="label-row">支払方法</span>
            <span class="method-row">{{ $methodLabels[$paymentMethod] ?? '不明' }}</span>
          </div>
        </div>
        <form action="{{ route('purchase.execute', ['item_id' => $item->id]) }}" method="POST">
          @csrf
          <input type="hidden" name="payment_method" value="{{ $paymentMethod }}">
          <button type="submit" class="purchase-btn">購入する</button>
        </form>
      </div>
    </div>
  </main>

  <!-- JavaScript: リアルタイム更新 -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
  var paymentSelect = document.getElementById('payment_method');
  var methodRow = document.querySelector('.right-info .method-row');
  var hiddenPaymentInput = document.querySelector('input[name="payment_method"]');

  const labels = {
    credit_card: 'カード支払い',
    convenience_store: 'コンビニ払い'
  };

  if (paymentSelect) {
    paymentSelect.addEventListener('change', function () {
      var selectedValue = this.value;
      if (methodRow) {
        methodRow.textContent = labels[selectedValue] || '不明';
      }
      if (hiddenPaymentInput) {
        hiddenPaymentInput.value = selectedValue;
      }
    });
  }
});

  </script>
</body>
</html>
