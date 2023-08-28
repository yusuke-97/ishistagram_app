<!-- 投稿画像のカルーセル表示 -->
<div id="postImagesCarousel" class="carousel slide" data-wrap="false" data-interval="false">

    <!-- カルーセルのドットナビゲーションを表示 -->
    @if(count($post->images) > 1)
    <ol class="carousel-indicators">
        @foreach($post->images as $index => $image)
        <li data-target="#postImagesCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></li>
        @endforeach
    </ol>
    @endif

    @php
    $firstImageIndex = 1;
    $lastImageIndex = count($post->images);
    @endphp

    <!-- カルーセルの中の画像部分 -->
    <div class="carousel-inner">
        @foreach($post->images as $index => $image)
        <div class="carousel-item {{ $index == 0 ? 'active' : '' }} modal-image-container">
            <img class="modal-image" src="{{ Storage::disk('s3')->url($image->file_path) }}" class="d-block w-100" alt="投稿画像">
        </div>
        @endforeach
    </div>

    <!-- カルーセルのコントロール(前後ボタン)を表示 -->
    <button class="carousel-control-prev" type="button" data-bs-target="#postImagesCarousel" data-bs-slide="prev">
        <i class="fa-solid fa-circle-chevron-left" style="color: black;"></i>
        <span class="visually-hidden">前へ</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#postImagesCarousel" data-bs-slide="next">
        <i class="fa-solid fa-circle-chevron-right" style="color: black;"></i>
        <span class="visually-hidden">次へ</span>
    </button>
</div>