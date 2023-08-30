@extends('layouts.app')

@section('content')
<div class="container">

    <!-- 検索クエリに基づくヘッダタイトル -->
    @if(isset($isUsernameSearch) && $isUsernameSearch)
    <h1>{{ $query }}の検索結果</h1>
    @else
    <h1>{{ Str::startsWith($query, '#') ? '' : '#' }}{{ $query }}の検索結果</h1>
    @endif

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
                <img src="{{ Storage::disk('s3')->url($image->file_path) }}" alt="投稿画像" class="{{ $key == 0 ? 'ishistagram-post-image' : 'd-none' }}">
                @endforeach

                <!-- 複数の画像が存在する投稿にだけ、複数画像アイコンを表示 -->
                @if(count($post->images) > 1)

                <!-- モバイル用 -->
                <div class="multiple-image-icon d-block d-md-none" data-post-id="{{ $post->id }}">
                    <i class="fa-regular fa-clone"></i>
                </div>

                <!-- デスクトップ用 -->
                <div class="multiple-image-icon d-none d-md-block" data-post-id="{{ $post->id }}">
                    <i class="fa-regular fa-clone"></i>
                </div>

                @endif

            </div>
        </div>
        @endforeach
    </div>
    @else

    <!-- 検索結果が0件の場合のメッセージ -->
    @if(isset($isUsernameSearch) && $isUsernameSearch)
    <p>該当するユーザーは見つかりませんでした。</p>
    @else
    <p>該当する投稿は見つかりませんでした。</p>
    @endif

    @endif

</div>
@endsection