<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧画面（トップ）</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body>
    @include('layouts.newapp')


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
