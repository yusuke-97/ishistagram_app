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
<script src="{{ asset('js/post_edit_delete.js') }}"></script>
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

                        <!-- ユーザープロフィール画像の表示 -->
                        @if($post->user->profile_image)
                        <img class="modal-profile-image" src="{{ Storage::disk('s3')->url('profile_images/' . Auth::user()->profile_image) }}" alt="プロフィール画像">
                        @else

                        <!-- プロフィール画像がない場合、プロフィールアイコンを表示 -->
                        <i class="fas fa-user fa-2x modal-profile-icon"></i> <!-- プロフィール画像がない場合の代替表示 -->
                        @endif

                        <!-- ユーザーネームを表示 -->
                        <p class="modal-user">{{ $post->user->user_name }}</p> <!-- ユーザーネームを表示 -->
                    </div>

                    <!-- ︙ボタンとドロップダウンメニュー -->
                    @if(Auth::id() == $post->user_id)
                    <div class="dropdown user-action-dropdown" data-bs-boundary="window">
                        <a href="#" class="dropdown-toggle px-1 fs-5 fw-bold link-dark text-decoration-none" id="dropdownPostMenuLink{{ $post->id }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">︙</a>
                        <ul class="dropdown-menu dropdown-menu-end text-center" aria-labelledby="dropdownPostMenuLink{{ $post->id }}">
                            <li>

                                <!-- 編集ボタン -->
                                <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="text-dark text-decoration-none" style="font-weight: bold;">
                                    <i class="fa-solid fa-pen-to-square" style="margin-right: 0.5rem;"></i> 編集
                                </a>
                            </li>
                            <div class="dropdown-divider"></div>
                            <li>

                                <!-- 削除ボタン -->
                                <a href="#" class="text-danger text-decoration-none" style="font-weight: bold;" data-bs-toggle="modal" data-bs-target="#deletePostModal{{ $post->id }}">
                                    <i class="fa-solid fa-trash-can" style="margin-right: 0.5rem;"></i> 削除
                                </a>
                            </li>
                        </ul>
                    </div>
                    @endif
                </div>

                <!-- モーダルを閉じるボタン -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>

            </div>
            <div class="modal-body">

                <!-- 投稿画像のカルーセル -->
                <div id="postImagesCarousel{{ $post->id }}" class="carousel slide" data-wrap="false" data-interval="false">

                    <!-- カルーセルのドットナビゲーションを表示 -->
                    @if(count($post->images) > 1)
                    <ol class="carousel-indicators">
                        @foreach($post->images as $index => $image)
                        <li data-target="#postImagesCarousel{{ $post->id }}" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></li>
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
                    <button class="carousel-control-prev" type="button" data-bs-target="#postImagesCarousel{{ $post->id }}" data-bs-slide="prev">
                        <i class="fa-solid fa-circle-chevron-left" style="color: black;"></i>
                        <span class="visually-hidden">前へ</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#postImagesCarousel{{ $post->id }}" data-bs-slide="next">
                        <i class="fa-solid fa-circle-chevron-right" style="color: black;"></i>
                        <span class="visually-hidden">次へ</span>
                    </button>
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

<!-- 投稿の削除用モーダル -->
@include('modals.delete_post')