<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'post_id' => 'required|integer|exists:posts,id',
        ]);

        $comment = new Comment();
        $comment->user_id = auth()->id();
        $comment->post_id = $request->post_id;
        $comment->content = $request->content;
        $comment->save();

        $userName = auth()->user()->user_name;

        return response()->json([
            'success' => 'コメントを投稿しました！',
            'user_name' => $userName,
            'id' => $comment->id
        ]);        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return response()->json(['comment' => $comment]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        // コメントのオーナーのみがコメントを削除できるようにする
        if(auth()->id() != $comment->user_id) {
            return response()->json(['error' => '許可されていない操作です。'], 403);
        }

        $comment->delete();
        return response()->json(['success' => 'コメントを削除しました。']);
    }
}
