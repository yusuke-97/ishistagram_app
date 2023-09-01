<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * 与えられた記事に対する「いいね」を作成または削除
     * すでに「いいね」が存在する場合は削除し、存在しない場合は新たに作成
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        $postId = $request->post_id;

        // 既存の「いいね」を検索
        $existingLike = Like::where('user_id', $userId)->where('post_id', $postId)->first();

        if ($existingLike) {
            // 既存の「いいね」がある場合は削除
            $existingLike->delete();
            return response()->json(['status' => 'unliked']);
        } else {
            // 「いいね」が存在しない場合は新規作成
            $like = new Like;
            $like->user_id = $userId;
            $like->post_id = $postId;
            $like->save();
            return response()->json(['status' => 'liked']);
        }
    }

    /**
     * 指定した「いいね」を削除
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function destroy(Like $like)
    {
        $like->delete();

        return response()->json(['status' => 'success']);
    }
}
