<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ItemController extends Controller
{
   public function index(Request $request)
{
    // クエリパラメータ 'tab' を取得（指定がなければ 'recommend' をデフォルト値とする）
    $tab = $request->input('tab', 'recommend');

    $search = $request->input('search'); // 検索キーワード取得

    // ログインユーザーのIDを取得（未ログイン時はnull）
    $userId = Auth::id();

    if ($tab === 'mylist') {
        // マイリストタブの場合
        // 例として、ここではお気に入り商品のクエリを実装する想定ですが、
        // 実装に応じて、favorites テーブルなどと連携させてください。

        // 商品一覧を取得（自分が出品した商品は除外）
        $query = Item::with(['category', 'status', 'purchase'])
            ->when($userId, function ($query) use ($userId) {
                return $query->where('user_id', '!=', $userId);
            })
            ->latest();

        // 検索キーワードが入力されている場合、商品名で部分一致検索
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $items = $query->paginate(12)
            ->appends(['tab' => 'mylist', 'search' => $search]);
    } else {
        // デフォルト（おすすめ）タブの場合
        $query = Item::with(['category', 'status', 'purchase'])
            ->when($userId, function ($query) use ($userId) {
                return $query->where('user_id', '!=', $userId);
            })
            ->latest();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $items = $query->paginate(12)
            ->appends(['tab' => 'recommend', 'search' => $search]);
    }

    return view('items.index', compact('items', 'tab'));
}
}
