<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Label;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * プロフィールページの初期表示
     * ログイン中のユーザーの投稿、ラベルを取得して表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::where('user_id', Auth::id())->with('images')->latest()->get();
        $user = Auth::user();
        $labels = Label::all();

        return view('profile.index', compact('posts', 'user', 'labels'));
    }

    /**
     * ユーザーのプロフィールページの表示
     * ラベルが指定されている場合、そのラベルに紐づいた投稿を表示
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $profile = null, $label = null)
    {
        if ($profile === null) {
            $user = Auth::user();
        } else {
            $user = User::findOrFail($profile);
        }

        // ユーザーの投稿にのみ紐づいているラベルを取得
        $labels = Label::whereHas('posts', function ($query) use ($user) {
            $query->whereIn('posts.id', $user->posts->pluck('id')->toArray());
        })->get();

        if ($label) {
            // 選択されたラベルを持つ投稿を取得
            $posts = $user->posts()->whereHas('labels', function ($query) use ($label) {
                $query->where('name', $label);
            })->with('images')->orderBy('created_at', 'desc')->get();
            $currentLabelName = $label;
        } else {
            // すべての投稿を取得
            $posts = $user->posts()->with('images')->orderBy('created_at', 'desc')->get();
            $currentLabelName = 'すべてを表示';
        }

        return view('profile.index', compact('user', 'posts', 'labels', 'label', 'currentLabelName'));
    }

    /**
     * プロフィール編集フォームの表示
     *
     * @param  \App\Models\User  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(User $profile)
    {
        return view('profile.edit', ['user' => $profile]);
    }

    /**
     * プロフィール情報の更新
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $profile)
    {
        // バリデーション
        $request->validate([
            'name' => 'required|max:255',
            'user_name' => 'required|max:255|unique:users,user_name,' . $profile->id,
            'email' => 'required|email|max:255|unique:users,email,' . $profile->id,
            'bio' => 'nullable',
            'profile_image' => 'nullable|image|max:4096',
        ]);

        // データの更新
        $profile->name = $request->input('name');
        $profile->user_name = $request->input('user_name');
        $profile->email = $request->input('email');
        $profile->bio = $request->input('bio');

        // プロフィール画像削除のフラグが1の場合、プロフィール画像を削除
        if ($request->input('delete_image_flag') === '1') {
            if ($profile->profile_image) {
                Storage::delete('public/profile_images/' . $profile->profile_image);
            }
            $profile->profile_image = null;
        }
        // 画像がアップロードされた場合の処理
        elseif ($request->hasFile('profile_image')) {
            $fileName = Storage::disk('s3')->put('profile_images', $request->file('profile_image'), 'public');
            $profile->profile_image = basename($fileName);
        }

        $profile->save();

        // リダイレクト
        return redirect()->route('profile.default')->with('flash_message', 'プロフィールを更新しました。');
    }

    /**
     * ラベルに基づいてユーザーの投稿を表示
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null  $profile
     * @param  string|null  $label
     * @return \Illuminate\Http\Response
     */
    public function showLabel(Request $request, $profile = null, $label = null)
    {
        // デフォルトのルートの場合、ログインユーザーを使用
        if ($profile === null) {
            $user = Auth::user();
        } else {
            $user = User::findOrFail($profile);
        }

        // ユーザーの投稿にのみ紐づいているラベルを取得
        $labels = Label::whereHas('posts', function ($query) use ($user) {
            $query->whereIn('posts.id', $user->posts->pluck('id')->toArray());
        })->get();

        if ($label) {
            // 選択されたラベルを持つ投稿を取得
            $posts = $user->posts()->whereHas('labels', function ($query) use ($label) {
                $query->where('name', $label);
            })->with('images')->orderBy('created_at', 'desc')->get();
            $currentLabelName = $label;
        } else {
            // すべての投稿を取得
            $posts = $user->posts()->with('images')->orderBy('created_at', 'desc')->get();
            $currentLabelName = 'すべてを表示';
        }

        return view('profile.index', compact('user', 'posts', 'labels', 'label', 'currentLabelName'));
    }
}
