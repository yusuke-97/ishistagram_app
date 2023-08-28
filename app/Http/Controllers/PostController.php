<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Image;
use App\Models\Label;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // 一覧ページ
    public function index()
    {
        $posts = Post::with('images')->latest()->get();

        // 各投稿の内容を変換
        foreach ($posts as $post) {
            $post->content = $this->convertHashtagsToLinks($post->content);
        }

        return view('posts.index', compact('posts'));
    }

    // 作成ページ
    public function create()
    {
        return view('posts.create');
    }

    // 作成機能
    public function store(Request $request)
    {
        // バリデーションの設定
        $request->validate([
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'content' => 'required|max:255',
        ]);

        // ラベルの数を確認
        $labelNames = explode(',', $request->input('labels'));
        if (count($labelNames) > 2) {
            return back()->withInput()->withErrors(['labels' => '最大2つのラベルしか作成できません。']);
        }

        $post = new Post();
        $post->content = $request->input('content');
        $post->user_id = Auth::id();
        $post->save();

        // 複数ファイルを保存し、保存先のパスを取得
        if ($files = $request->file('image')) {
            foreach ($files as $file) {
                $path = Storage::disk('s3')->put('post_images', $file, 'public');
                // 保存先のパスをImageモデルに設定
                $image = new Image();
                $image->file_path = $path;
                $image->post_id = $post->id;
                $image->save();
            }
        }

        // ラベルの処理
        foreach ($labelNames as $labelName) {
            // 既存のラベルを探すか、新しいラベルを作成
            $label = Label::firstOrCreate(['name' => $labelName, 'user_id' => Auth::id()]);
            $post->labels()->attach($label->id); // ラベルIDを指定して紐づけ
        }

        // 投稿内容からハッシュタグを抽出
        preg_match_all('/#([\p{L}\p{Mn}\p{Pd}0-9_]+)/u', $request->input('content'), $tags);
        $tags = $tags[1];

        // タグを保存または取得し、投稿と関連付け
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $post->tags()->attach($tag->id);
        }

        return redirect()->route('posts.index', compact('post'))->with('flash_message', '投稿が完了しました。');
    }

    // 編集ページ
    public function edit(Post $post)
    {
        // 編集する投稿のオーナーが現在のユーザーであるかチェック
        if (Auth::id() !== $post->user_id) {
            return redirect()->back()->with('error', '権限がありません。');
        }

        // 投稿に関連付けられているラベルを取得
        $labels = $post->labels;
        $labelIds = $labels->pluck('id')->toArray(); // 投稿に関連するラベルIDの配列を取得

        return view('posts.edit', compact('post', 'labels', 'labelIds'));
    }

    // 更新機能
    public function update(Request $request, Post $post)
    {
        // バリデーションの設定
        $request->validate([
            'content' => 'required|max:255',
        ]);

        // 投稿の内容を更新
        $post->content = $request->input('content');
        $post->save();

        // ラベルの処理
        $labelsInput = $request->input('labels', []);  // 配列として受け取る
        $labelNames = is_string($labelsInput) ? explode(',', $labelsInput) : $labelsInput;

        if (count($labelNames) > 2) {
            return back()->withInput()->withErrors(['labels' => '最大2つのラベルしか作成できません。']);
        }

        $newLabelIds = [];
        foreach ($labelNames as $labelName) {
            $label = Label::firstOrCreate(['name' => trim($labelName), 'user_id' => Auth::id()]);
            $newLabelIds[] = $label->id;
        }

        $post->labels()->sync($newLabelIds);

        // 既存のラベル名を取得
        $oldLabelNames = $post->labels->pluck('name')->toArray();

        // 削除されたラベル名のリストを取得
        $removedLabelNames = array_diff($oldLabelNames, $labelNames);

        foreach ($removedLabelNames as $labelName) {
            // そのラベル名を持つ他の投稿の数を確認
            $count = Post::whereHas('labels', function ($query) use ($labelName) {
                $query->where('name', $labelName);
            })->count();

            // 他の投稿にそのラベル名が存在しない場合、ラベル名を削除
            if ($count === 0) {
                Label::where('name', $labelName)->delete();
            }
        }

        // 投稿内容からハッシュタグを抽出
        preg_match_all('/#([\p{L}\p{Mn}\p{Pd}0-9_]+)/u', $request->input('content'), $tags);
        $tags = $tags[1];

        // 既存のハッシュタグと新しいハッシュタグを比較して、追加・削除するハッシュタグを特定する
        $oldTagNames = $post->tags->pluck('name')->toArray();
        $addedTags = array_diff($tags, $oldTagNames);
        $removedTags = array_diff($oldTagNames, $tags);

        // 追加されたハッシュタグを保存または取得し、投稿と関連付ける
        foreach ($addedTags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $post->tags()->attach($tag->id);
        }

        // 削除されたハッシュタグを投稿から取り除く
        foreach ($removedTags as $tagName) {
            $tag = Tag::where('name', $tagName)->first();
            if ($tag) {
                $post->tags()->detach($tag->id);
            }
        }

        // 最後に、適当なページにリダイレクトさせる
        return redirect()->route('profile.default')->with('flash_message', '投稿を更新しました。');
    }


    // 削除機能
    public function destroy(Post $post)
    {
        // この投稿に紐づくラベルとタグを取得
        $labels = $post->labels;
        $tags = $post->tags;

        // 投稿を削除
        $post->delete();

        // この投稿に紐づいていたラベルをチェック
        foreach ($labels as $label) {
            // このラベルを持つ他の投稿がないか確認
            if ($label->posts->count() == 0) {
                // 他の投稿でこのラベルが使用されていなければ、ラベルを削除
                $label->delete();
            }
        }

        // この投稿に紐づいていたタグをチェック
        foreach ($tags as $tag) {
            // このタグを持つ他の投稿がないか確認
            if ($tag->posts->count() == 0) {
                // 他の投稿でこのタグが使用されていなければ、タグを削除
                $tag->delete();
            }
        }

        return redirect()->route('profile.default')->with('flash_message', '投稿を削除しました。');
    }

    // #同じハッシュタグを持つ投稿ページ
    public function show(Post $post)
    {
        $post->content = $this->convertHashtagsToLinks($post->content);

        return view('modals.show_post', ['post' => $post]);
    }

    private function convertHashtagsToLinks($content)
    {
        preg_match_all('/#([\p{L}\p{N}_]+)/u', $content, $matches);

        // マッチしたハッシュタグを長さの降順にソート
        usort($matches[0], function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        foreach ($matches[0] as $match) {
            $hashtag = str_replace('#', '', $match);
            $url = "/ishistagram/public/search/tags?query=" . $hashtag;
            $link = '<a href="' . $url . '">' . $match . '</a>';
            $content = str_replace($match, $link, $content);
        }
        return $content;
    }
}
