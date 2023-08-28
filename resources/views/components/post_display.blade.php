<!-- 投稿の表示 -->
<div class="col-4 ishistagram-post-container">

    <!-- 投稿の表示用モーダル -->
    @include('modals.show_post')

    <div class="ishistagram-image-container" onclick="showPostDetails({{ $post->id }})">
        @foreach($post->images as $key => $image)

        <!-- 各投稿の画像を表示、ただし、複数の画像がある場合は最初の画像のみ表示 -->
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