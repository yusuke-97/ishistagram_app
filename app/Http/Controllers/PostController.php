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
    /**
     * 投稿の一覧表示
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $posts = Post::with('images')->latest()->get();

        foreach ($posts as $post) {
            $post->content = $this->convertHashtagsToLinks($post->content);
        }

        return view('posts.index', compact('posts'));
    }

    /**
     * 新しい投稿の作成画面を表示
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $labels = Label::whereHas('posts', function ($query) {
            $query->whereIn('posts.id', Auth::user()->posts->pluck('id')->toArray());
        })->get();

        return view('posts.create', compact('labels'));
    }

    /**
     * 投稿の保存
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // バリデーション: 画像と内容のチェック
        $request->validate([
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'content' => 'required|max:255',
        ]);

        // 投稿内容の保存
        $post = new Post();
        $post->content = $request->input('content');
        $post->user_id = Auth::id();
        $post->save();

        // 画像の保存
        if ($files = $request->file('image')) {
            foreach ($files as $file) {
                $path = Storage::disk('s3')->put('post_images', $file, 'public');
                $image = new Image();
                $image->file_path = $path;
                $image->post_id = $post->id;
                $image->save();
            }
        }

        // ラベルの関連付け
        $labelsInput = $request->input('labels', []);
        $labelNames = is_string($labelsInput) ? explode(',', $labelsInput) : $labelsInput;

        if (count($labelNames) > 2) {
            return back()->withInput()->withErrors(['labels' => '最大2つのラベルしか作成できません。']);
        }

        // 投稿内容からハッシュタグを取得し、関連付け
        foreach ($labelNames as $labelName) {
            $label = Label::firstOrCreate(['name' => $labelName, 'user_id' => Auth::id()]);
            $post->labels()->attach($label->id);
        }

        // ハッシュタグの関連付け処理
        preg_match_all('/#([\p{L}\p{Mn}\p{Pd}0-9_]+)/u', $request->input('content'), $tags);
        $tags = $tags[1];
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $post->tags()->attach($tag->id);
        }

        return redirect()->route('posts.index', compact('post'))->with('flash_message', '投稿が完了しました。');
    }

    /**
     * 投稿の編集画面を表示
     *
     * @param Post $post
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            return redirect()->back()->with('error', '権限がありません。');
        }

        $labels = Label::whereHas('posts', function ($query) {
            $query->whereIn('posts.id', Auth::user()->posts->pluck('id')->toArray());
        })->get();

        return view('posts.edit', compact('post', 'labels'));
    }

    /**
     * 投稿の更新
     *
     * @param Request $request
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Post $post)
    {
        // バリデーション: 内容のチェック
        $request->validate([
            'content' => 'required|max:255',
        ]);

        // 投稿内容の更新
        $post->content = $request->input('content');
        $post->save();

        // ラベルの更新: 既存のラベルと新しいラベルの比較、追加、削除処理
        $labelsInput = $request->input('labels', []);
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

        return redirect()->route('profile.default')->with('flash_message', '投稿を更新しました。');
    }

    /**
     * 投稿の削除
     *
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $post)
    {
        // 削除前に、この投稿に関連するラベルとタグを取得
        $labels = $post->labels;
        $tags = $post->tags;

        // 投稿の削除
        $post->delete();

        // ラベルとタグの削除処理: 使用されていないラベルやタグの削除
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

    /**
     * 投稿の詳細表示: ハッシュタグを持つ投稿の詳細ページ
     *
     * @param Post $post
     * @return \Illuminate\View\View
     */
    public function show(Post $post)
    {
        // 投稿の内容に含まれるハッシュタグをリンクに変換
        $post->content = $this->convertHashtagsToLinks($post->content);

        return view('modals.show_post', ['post' => $post]);
    }

    /**
     * ハッシュタグをリンクに変換する関数
     *
     * @param string $content
     * @return string
     */
    private function convertHashtagsToLinks($content)
    {
        // 投稿の内容に含まれるハッシュタグを抽出し、リンクに変換
        preg_match_all('/#([\p{L}\p{N}_]+)/u', $content, $matches);

        // マッチしたハッシュタグを長さの降順にソート
        usort($matches[0], function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        foreach ($matches[0] as $match) {
            $hashtag = str_replace('#', '', $match);
            $url = "/search/tags?query=" . $hashtag;
            $link = '<a href="' . $url . '">' . $match . '</a>';
            $content = str_replace($match, $link, $content);
        }
        return $content;
    }
}
