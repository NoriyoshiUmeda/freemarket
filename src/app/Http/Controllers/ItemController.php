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

/**
     * 商品詳細画面の表示
     *
     * @param int $item_id
     * @return \Illuminate\View\View
     */
    public function show($item_id)
    {
        // 商品を取得（存在しない場合は404を返す）
        $item = Item::with(['category', 'status', 'purchase', 'comments', 'favorites'])->findOrFail($item_id);
        // 関連するコメントを取得（最新順など必要に応じて調整）
        $comments = $item->comments()->latest()->get();

        return view('items.show', compact('item', 'comments'));
    }

    /**
     * お気に入り（いいね）追加処理
     *
     * @param int $item_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function like($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        // 既にお気に入り登録していなければ新規作成
        if (!$item->favorites()->where('user_id', $user->id)->exists()) {
            $item->favorites()->create([
                'user_id' => $user->id,
            ]);
        }

        return back();
    }

    /**
     * お気に入り（いいね）削除処理
     *
     * @param int $item_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unlike($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        // 該当ユーザーのお気に入り情報を削除
        $item->favorites()->where('user_id', $user->id)->delete();

        return back();
    }

    /**
     * コメント投稿処理
     *
     * @param \Illuminate\Http\Request $request
     * @param int $item_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addComment(Request $request, $item_id)
    {
        // バリデーション
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        $item = Item::findOrFail($item_id);

        // コメントレコードを作成
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->item_id = $item_id;
        $comment->comment = $request->input('comment');
        $comment->save();

        return back();
    }

}
