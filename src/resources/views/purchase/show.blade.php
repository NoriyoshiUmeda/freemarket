<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>商品購入画面</title>
  <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
</head>
<body>
  @include('layouts.newapp')

  <main class="purchase-container">
    <div class="purchase-main">
      <!-- 左カラム -->
      <div class="left-info">
        <div class="section product-info-section">
          <div class="product-image-wrapper">
            <img src="{{ asset('storage/' . $item->image) }}"
                 alt="{{ $item->name }}"
                 class="product-image">
          </div>
          <div class="product-text">
            <div class="product-name">{{ $item->name }}</div>
            <div class="product-price-left">¥{{ number_format($item->price) }}</div>
          </div>
        </div>

        <div class="section">
          <span class="label">支払方法</span>
          <div class="content">
            <form id="payment-form"
                  action="{{ route('purchase.show', ['item_id' => $item->id]) }}"
                  method="GET">
              <select name="payment_method"
                      id="payment-method">
                <option value="credit_card"
                  @if(request('payment_method', 'credit_card') === 'credit_card') selected @endif>
                  カード支払い
                </option>
                <option value="convenience_store"
                  @if(request('payment_method') === 'convenience_store') selected @endif>
                  コンビニ支払い
                </option>
              </select>
            </form>
          </div>
        </div>

        <div class="section shipping-section">
          <span class="label">配送先</span>
          <a href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}"
             class="address-change-btn">
            変更する
          </a>
          <div class="content">
            @if($postal_code && $address)
              〒{{ $postal_code }}<br>
              {{ $address }}<br>
              @if($building) {{ $building }} @endif
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
              'credit_card'       => 'カード支払い',
              'convenience_store' => 'コンビニ支払い',
            ];
          @endphp

          <div class="info-row">
            <span class="label-row">商品代金</span>
            <span class="price-row">¥{{ number_format($item->price) }}</span>
          </div>
          <hr class="separator">
          <div class="info-row">
            <span class="label-row">支払方法</span>
            <span class="method-row">
              {{ $methodLabels[$paymentMethod] ?? '不明' }}
            </span>
          </div>
        </div>

        <form action="{{ route('purchase.execute', ['item_id' => $item->id]) }}"
              method="POST">
          @csrf
          <input type="hidden"
                 name="payment_method"
                 value="{{ $paymentMethod }}">
          <button type="submit"
                  class="purchase-btn">
            購入する
          </button>
        </form>
      </div>
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const paymentSelect      = document.getElementById('payment-method');
      const methodRow          = document.querySelector('.right-info .method-row');
      const hiddenPaymentInput = document.querySelector('input[name="payment_method"]');

      const labels = {
        credit_card       : 'カード支払い',
        convenience_store : 'コンビニ支払い'
      };

      if (paymentSelect) {
        paymentSelect.addEventListener('change', function () {
          const val = this.value;
          if (methodRow) {
            methodRow.textContent = labels[val] || '不明';
          }
          if (hiddenPaymentInput) {
            hiddenPaymentInput.value = val;
          }
          // 必要ならフォーム自動送信：
          // document.getElementById('payment-form').submit();
        });
      }
    });
  </script>
</body>
</html>
