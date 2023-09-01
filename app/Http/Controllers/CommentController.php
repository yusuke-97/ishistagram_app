<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * リクエストから送られた情報をもとに新しいコメントをデータベースに保存
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // リクエストのバリデーション
        $request->validate([
            'content' => 'required|string|max:255',
            'post_id' => 'required|integer|exists:posts,id',
        ]);

        // 新しいコメントのインスタンスを作成
        $comment = new Comment();
        $comment->user_id = auth()->id();
        $comment->post_id = $request->post_id;
        $comment->content = $request->content;
        $comment->save();

        // 現在のユーザー名を取得
        $userName = auth()->user()->user_name;

        // 成功した場合、JSONレスポンスを返す
        return response()->json([
            'success' => 'コメントを投稿しました！',
            'user_name' => $userName,
            'id' => $comment->id
        ]);
    }

    /**
     * 指定されたコメントを表示
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return response()->json(['comment' => $comment]);
    }

    /**
     * 指定されたコメントをデータベースから削除
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        // コメントのオーナーのみがコメントを削除できるようにする
        if (auth()->id() != $comment->user_id) {
            return response()->json(['error' => '許可されていない操作です。'], 403);
        }

        $comment->delete();
        return response()->json(['success' => 'コメントを削除しました。']);
    }
}
