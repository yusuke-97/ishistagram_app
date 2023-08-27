<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 現在のユーザーが過去に作成したラベルを取得（削除済みを除く）
        $labels = Label::where('user_id', Auth::id())->get();

        // ラベルの名前のみを抽出
        $labelNames = $labels->pluck('name');

        // JSONとしてレスポンスを返す
        return response()->json(['labels' => $labelNames]);
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
        $labels = $request->json()->get('labels'); // 送信されたJSONデータからラベルを取得

        // バリデーション。この部分はラベルの形式に応じて調整が必要かもしれません
        $request->validate([
            'labels' => 'required|array',
        ]);

        $userId = Auth::id(); // ログインしているユーザーのIDを取得

        foreach ($labels as $labelName) {
            // ユーザーIDとラベル名で既存のラベルを検索
            $existingLabel = Label::where('name', $labelName)->where('user_id', $userId)->first();

            // 既存のラベルが存在しない場合のみ新規作成
            if (!$existingLabel) {
                $label = new Label;
                $label->name = $labelName;
                $label->user_id = $userId;
                $label->save();
            }
        }

        // 成功時のレスポンス
        return response()->json(['message' => 'ラベルを作成しました']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function show(Label $label)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function edit(Label $label)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Label $label)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        //
    }
}