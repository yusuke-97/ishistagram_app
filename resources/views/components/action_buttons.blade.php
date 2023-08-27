<!-- アクションボタンのコンテナ -->
<div class="action-buttons" style="margin-bottom: 8px;">
    <div class="like-and-comment-container">
        <div class="like-comment-buttons">

            <!-- いいねボタン -->
            <form id="like-form-{{ $post->id }}" method="POST" action="{{ route('likes.store') }}" style="display: inline;">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <button class="button-like" type="submit" onclick="event.preventDefault(); onLikeButtonClicked({{ $post->id }});">
                    <i id="like-icon-{{ $post->id }}" class="fa-heart {{ $like ? 'fas' : 'far' }}" style="color: {{ $like ? 'red' : 'black' }}; font-size: 1.5rem;"></i>
                    <span id="like-count-{{ $post->id }}">{{ $post->likes->count() }}</span>
                </button>
            </form>

            <!-- コメントアイコンボタン -->
            <button type="button" class="btn btn-link comment-button" data-post-id="{{ $post->id }}" id="comment-button-{{ $post->id }}">
                <i class="fa-regular fa-comment" style="font-size: 1.5rem;"></i>
            </button>
        </div>
    </div>
</div>