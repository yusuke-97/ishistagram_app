<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;

class SearchController extends Controller
{
    public function searchTags(Request $request)
    {
        $originalQuery = $request->query('query');
        $query = $originalQuery;
        $isUsernameSearch = false;

        // クエリが#で始まっている場合、#を除去
        if (strpos($query, '#') === 0) {
            $query = substr($query, 1);
        } else {
            $isUsernameSearch = true;
        }

        $tags = Tag::where('name', '=', $query)->get();

        // ここでタグに関連する投稿を取得します。
        $posts = Post::whereHas('tags', function ($q) use ($tags) {
            $q->whereIn('tags.id', $tags->pluck('id'));
        })->get();

        // 検索結果をビューに渡す
        return view('search.results', compact('posts', 'originalQuery', 'isUsernameSearch'));
    }

    public function autocompleteTags(Request $request)
    {
        $query = $request->query('query');
        // クエリの最初の文字が#であれば削除
        if (substr($query, 0, 1) === '#') {
            $query = substr($query, 1);
        }

        // タグ名が$queryで始まり、かつ、関連する投稿が存在するもののみを検索
        $tags = Tag::where('name', 'like', $query . '%')
            ->whereHas('posts')
            ->get();

        return response()->json($tags);
    }

    public function autocompleteUsers(Request $request)
    {
        $query = $request->get('query');
        $users = User::where('user_name', 'like', '%' . $query . '%')
            ->orWhere('name', 'like', '%' . $query . '%')
            ->get();

        return response()->json($users);
    }
}
