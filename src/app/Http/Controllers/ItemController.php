<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'recommend');

        $search = $request->input('search'); // 検索キーワード取得


        $userId = Auth::id();

        if ($tab === 'mylist') {
            if (! $userId) {
                $items = collect();
            } else {

                $favorites = \App\Models\Favorite::with(['item.category', 'item.status', 'item.purchase'])
                    ->where('user_id', $userId)
                    ->latest()
                    ->get();
                $items = $favorites->pluck('item')
                    ->filter(function ($item) use ($userId) {
                        return $item->user_id !== $userId; // 自分が出品した商品は除外
                        });


                if ($search) {
                    $items = $items->filter(function ($item) use ($search) {
                        return stripos($item->name, $search) !== false;
                    });
                }
            }


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

            $query = Item::with(['category', 'status', 'purchase'])
                ->when($userId, function ($query) use ($userId) {
                    return $query->where('user_id', '!=', $userId);
                })
                ->latest();

            if ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            }

            $items = $query->paginate(12)
                ->appends(['tab' => 'recommend', 'search' => $search]);
        }

        return view('items.index', compact('items', 'tab', 'search'));
    }

        public function show($item_id)
    {

        $item = Item::with(['status', 'purchase', 'comments.user', 'favorites'])->findOrFail($item_id);

        $comments = $item->comments()->with('user')->latest()->get();

        $favoriteCount = $item->favorites->count();
        $commentCount = $item->comments->count();

        $categoryIds = explode(',', $item->category_id);
        $categories = Category::whereIn('id', $categoryIds)->get();


        $hasPurchased = $item->purchase()->exists();

        return view(
            'items.show',
            compact('item', 'comments', 'favoriteCount', 'commentCount', 'categories', 'hasPurchased')
        );
    }

        public function like($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();


        if (! $item->favorites()->where('user_id', $user->id)->exists()) {
            $item->favorites()->create([
                'user_id' => $user->id,
            ]);
        }

        return back();
    }

        public function unlike($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();


        $item->favorites()->where('user_id', $user->id)->delete();

        return back();
    }

        public function addComment(CommentRequest $request, $item_id)
    {

        $item = Item::findOrFail($item_id);


        $comment = new Comment;
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

        $validated = $request->validated();


        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }


        $validated['user_id'] = Auth::id();


        if (isset($validated['category_id']) && is_array($validated['category_id'])) {
            $validated['category_id'] = implode(',', $validated['category_id']);
        }


        $item = Item::create($validated);

        return redirect()->route('item.index');
    }
}
