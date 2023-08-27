@extends('layouts.app')

@section('content')
<div class="container">

    <!-- 検索クエリに基づくヘッダタイトル -->
    <h1>#{{ $query }}の検索結果</h1>

    <!-- 投稿が存在する場合の処理 -->
    @if($posts->count())
    <div class="row justify-content-start">

        <!-- 各投稿をループで表示 -->
        @foreach($posts as $post)
        <div class="col-4 ishistagram-post-container">

            <!-- 投稿の詳細表示用モーダルをインクルード -->
            @include('modals.post_result')

            <!-- 画像をクリックすると投稿詳細モーダルが表示されるエリア -->
            <div class="ishistagram-image-container" data-bs-toggle="modal" data-bs-target="#showPostModal{{ $post->id }}">

                <!-- 投稿の画像をループで表示。複数画像の場合、最初の画像のみ表示する。 -->
                @foreach($post->images as $key => $image)
                <img src="{{ asset('storage/' . $image->file_path) }}" alt="投稿画像" class="{{ $key == 0 ? 'ishistagram-post-image' : 'd-none' }}">
                @endforeach

                <!-- 投稿に複数の画像がある場合、多画像存在を示すアイコンを表示 -->
                @if(count($post->images) > 1)
                <div class="multiple-image-icon" data-post-id="{{ $post->id }}">
                    <i class="fa-regular fa-clone"></i>
                </div>
                @endif

            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- 検索結果が0件の場合のメッセージ -->
    <p>該当する投稿は見つかりませんでした。</p>
    @endif

</div>
@endsection