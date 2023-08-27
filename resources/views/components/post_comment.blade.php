<!-- 各投稿コメントをループで表示 -->
@foreach($post->comments as $comment)
<div class="comment-section d-flex align-items-center mb-2 justify-content-between" style="padding-left: 16px; padding-right: 16px" id="comment-section-{{ $comment->id }}">

    <!-- コメントの表示 -->
    <div class="d-flex align-items-center">
        <i class="fa-solid fa-reply me-1"></i>
        <strong style="font-weight: bold;" class="me-2">{{ $comment->user->user_name }}</strong>
        <span>{{ $comment->content }}</span>
    </div>

    <!-- コメントの削除ボタンを表示(投稿者のみ) -->
    @if(Auth::id() == $comment->user_id)
    <i class="fa-solid fa-trash-can delete-comment-button" data-comment-id="{{ $comment->id }}"></i>
    @endif
</div>
@endforeach

<!-- コメント入力セクション -->
<div class="comment-input-section p-3" id="comment-input-{{ $post->id }}" style="display: none;">
    <textarea class="form-control mb-2" id="comment-text-{{ $post->id }}" placeholder="コメントを入力"></textarea>
    <button type="button" class="btn share-btn" onclick="submitComment({{ $post->id }});">
        <i class="fa-regular fa-paper-plane"></i>送信
    </button>
</div>