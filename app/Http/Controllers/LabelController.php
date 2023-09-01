<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{
    /**
     * ラベルのリストを表示
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
     * 新しいラベルを保存
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // リクエストからラベルの情報を取得
        $labels = $request->json()->get('labels');

        // バリデーションルールの適用
        $request->validate([
            'labels' => 'required|array',
        ]);

        // 現在のユーザーIDを取得
        $userId = Auth::id();

        foreach ($labels as $labelName) {
            // 同じユーザーの既存のラベルを検索
            $existingLabel = Label::where('name', $labelName)->where('user_id', $userId)->first();

            // ラベルがまだ存在しない場合に新しいラベルを作成
            if (!$existingLabel) {
                $label = new Label;
                $label->name = $labelName;
                $label->user_id = $userId;
                $label->save();
            }
        }

        // 成功時のレスポンスを返す
        return response()->json(['message' => 'ラベルを作成しました']);
    }
}
