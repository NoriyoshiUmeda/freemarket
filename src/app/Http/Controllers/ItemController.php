<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Comment; // コメント取得用
use App\Models\Favorite; // いいね数を取得するため
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\CommentRequest;
use App\Models\Category;
use App\Models\Status;
use App\Http\Requests\ExhibitionRequest; 



class ItemController extends Controller
{
   public function index(Request $request)
{
    $tab = $request->input('tab', 'recommend');

    $search = $request->input('search'); // 検索キーワード取得

    // ログインユーザーのIDを取得（未ログイン時はnull）
    $userId = Auth::id();

   if ($tab === 'mylist') {
    if (!$userId) {
        $items = collect();
    } else {
        // ログインユーザーのお気に入りを取得し、そこから Item を抽出
        $favorites = \App\Models\Favorite::with(['item.category', 'item.status', 'item.purchase'])
            ->where('user_id', $userId)
            ->latest()
            ->get();
        $items = $favorites->pluck('item');

        // 検索キーワードが入力されている場合、コレクション上で部分一致検索
        if ($search) {
            $items = $items->filter(function($item) use ($search) {
                return stripos($item->name, $search) !== false;
            });
        }
    }

    // 手動ページネーション処理
        $page = $request->input('page', 1);
        $perPage = 12; // 「recommend」タブと同じ件数にする場合
        $offset = ($page - 1) * $perPage;
        $currentItems = $items->slice($offset, $perPage)->values();
        $paginatedItems = new LengthAwarePaginator(
            $currentItems,
            $items->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
        $items = $paginatedItems;

} else {
    // おすすめタブの場合は、現在のコードの通りに自分が出品した商品を除外したクエリで取得
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


    return view('items.index', compact('items', 'tab', 'search'));
}

/**
     * 商品詳細画面の表示
     *
     * @param int $item_id
     * @return \Illuminate\View\View
     */
    public function show($item_id)
    {
        // 商品を取得
        $item = Item::with(['status', 'purchase', 'comments.user', 'favorites'])->findOrFail($item_id);
        // 関連するコメントを取得（最新順など必要に応じて調整）
        $comments = $item->comments()->with('user')->latest()->get();

        $favoriteCount = $item->favorites->count();
        $commentCount = $item->comments->count();
         // 保存されたカンマ区切りのカテゴリーIDを配列に変換し、Category モデルから情報を取得
        $categoryIds = explode(',', $item->category_id);
        $categories = Category::whereIn('id', $categoryIds)->get();

        // ログインの有無に関わらず、商品に対して購入レコードがあるかをチェック
        $hasPurchased = $item->purchase()->exists();

       

        return view('items.show', compact('item', 'comments', 'favoriteCount', 'commentCount', 'categories', 'hasPurchased'));

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
    public function addComment(CommentRequest $request, $item_id)
    {

        $item = Item::findOrFail($item_id);

        // コメントレコードを作成
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->item_id = $item_id;
        $comment->comment = $request->input('comment');
        $comment->save();

        return back();
    }

    public function create()
    {
    $categories = Category::all();
    $statuses = Status::all();
    
    return view('items.create', compact('categories', 'statuses'));
    }   

    public function store(ExhibitionRequest $request)
    {
        // バリデーション済みデータを取得
        $validated = $request->validated();

        // 画像アップロード
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        // 現在のユーザーIDをセット（必要に応じて）
        $validated['user_id'] = Auth::id();

        // 複数選択のカテゴリーをカンマ区切りの文字列に変換
        if (isset($validated['category_id']) && is_array($validated['category_id'])) {
        $validated['category_id'] = implode(',', $validated['category_id']);
    }

        // 商品情報登録（Itemモデルの $fillable に必要な項目が設定されていること）
        $item = Item::create($validated);

        return redirect()->route('item.index');
    }

}
