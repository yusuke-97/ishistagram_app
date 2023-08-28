<!-- スクリプトの追加 -->
@push('scripts')
<script>
    // 投稿の画像カルーセルIDを設定
    window.postCarouselId = 'postImagesCarousel{{ $post->id }}';
</script>
<script>
    // 投稿のIDを設定
    var postId = "{{ $post->id }}";
</script>
<!-- 各種JSファイルを読み込み -->
<script src="{{ asset('js/comment_script.js') }}"></script>
<script src="{{ asset('js/like_script.js') }}"></script>
<script src="{{ asset('js/show_post_script.js') }}"></script>
@endpush

<!-- 投稿表示用モーダル -->
<div class="modal fade" id="showPostModal{{ $post->id }}" tabindex="-1" aria-labelledby="showPostModalLabel{{ $post->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="modal-user-info profile-info-spacing">

                        <a href="{{ route('profile.default') }}" style="text-decoration: none; color: inherit;">
                            <!-- ユーザープロフィール画像の表示 -->
                            @if($post->user->profile_image)
                            <img class="modal-profile-image" src="{{ Storage::disk('s3')->url('profile_images/' . Auth::user()->profile_image) }}" alt="プロフィール画像">
                            @else

                            <!-- プロフィール画像がない場合、プロフィールアイコンを表示 -->
                            <i class="fas fa-user fa-2x modal-profile-icon"></i> <!-- プロフィール画像がない場合の代替表示 -->
                            @endif
                        </a>

                        <!-- ユーザーネームを表示 -->
                        <p class="modal-user">{{ $post->user->user_name }}</p> <!-- ユーザーネームを表示 -->

                    </div>
                </div>

                <!-- モーダルを閉じるボタン -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>

            </div>
            <div class="modal-body">

                <!-- 投稿画像のカルーセル -->
                <div id="postImagesCarousel{{ $post->id }}" class="carousel slide" data-wrap="false" data-interval="false" data-id="{{ $post->id }}">

                    <!-- ドットを表示する部分 -->
                    @if(count($post->images) > 1)
                    <ol class="carousel-indicators">
                        @foreach($post->images as $index => $image)
                        <li data-target="#postImagesCarousel{{ $post->id }}" data-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></li>
                        @endforeach
                    </ol>
                    @endif

                    <!-- 投稿画像の表示部分 -->
                    <div class="carousel-inner">
                        @foreach($post->images as $index => $image)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }} modal-image-container">
                            <img class="modal-image" src="{{ Storage::disk('s3')->url($image->file_path) }}" alt="投稿画像">
                        </div>
                        @endforeach
                    </div>
                    @php
                    $firstImageIndex = 1; // 最初の画像のインデックス
                    $lastImageIndex = count($post->images); // 最後の画像のインデックスを取得
                    @endphp

                    <!-- カルーセルの操作ボタン（左右） -->
                    @if(count($post->images) > 1)
                    <a class="carousel-control-prev" href="#postImagesCarousel{{ $post->id }}" role="button" data-slide="prev">
                        <i class="fa-solid fa-circle-chevron-left"></i>
                        <span class="sr-only">前へ</span>
                    </a>

                    <a class="carousel-control-next" href="#postImagesCarousel{{ $post->id }}" role="button" data-slide="next">
                        <i class="fa-solid fa-circle-chevron-right"></i>
                        <span class="sr-only">次へ</span>
                    </a>
                    @endif
                </div>

                <!-- いいねボタンとコメントボタン -->
                @php
                $like = $post->likes->where('user_id', Auth::id())->first();
                @endphp

                <div class="action-buttons">
                    <div class="like-and-comment-container">

                        <!-- いいねボタンとコメントアイコン -->
                        <div class="like-comment-buttons">
                            <form id="like-form-{{ $post->id }}" method="POST" action="{{ route('likes.store') }}" style="display: inline;">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">

                                <!-- いいねボタン -->
                                <button class="button-like" type="submit" onclick="event.preventDefault(); onLikeButtonClicked({{ $post->id }});">
                                    <i id="like-icon-{{ $post->id }}" class="fa-heart fa-2x {{ $like ? 'fas' : 'far' }}" style="color: {{ $like ? 'red' : 'black' }};"></i>
                                    <span id="like-count-{{ $post->id }}">{{ $post->likes->count() }}</span>
                                </button>
                            </form>

                            <!-- コメントアイコン -->
                            <button type="button" class="btn btn-link comment-button" data-post-id="{{ $post->id }}" id="comment-button-{{ $post->id }}">
                                <i class="fa-regular fa-comment"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 投稿の詳細と内容 -->
                <p class="modal-posted-date mb-0">{{ $post->created_at->format('Y年m月d日 H:i') }}</p>
                <div class="mb-2">
                    <strong style="font-weight: bold;" class="me-2">{{ $post->user->user_name }}</strong>
                    <span>{!! $post->content !!}</span>
                </div>

                <!-- コメントとユーザー名の表示 -->
                @foreach($post->comments as $comment)
                <div class="comment-section d-flex align-items-center mb-2 justify-content-between" style="padding-left: 16px; padding-right: 16px" id="comment-section-{{ $comment->id }}"> <!-- IDを追加しました -->
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-reply me-1"></i>
                        <strong style="font-weight: bold;" class="me-2">{{ $comment->user->user_name }}</strong>
                        <span>{{ $comment->content }}</span>
                    </div>

                    @if(Auth::id() == $comment->user_id) <!-- 現在のユーザーがコメントの投稿者であるかを確認 -->

                    <!-- 削除ボタン -->
                    <i class="fa-solid fa-trash-can delete-comment-icon" onclick="deleteComment({{ $comment->id }})"></i>
                    @endif
                </div>
                @endforeach

                <!-- コメント入力欄 -->
                <div class="comment-input-section mt-3" id="comment-input-{{ $post->id }}" style="display: none;">
                    <textarea class="form-control mb-2" id="comment-text-{{ $post->id }}" placeholder="コメントを入力"></textarea>
                    <button type="button" class="btn share-btn" onclick="submitComment({{ $post->id }});">
                        <i class="fa-regular fa-paper-plane"></i>送信
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>