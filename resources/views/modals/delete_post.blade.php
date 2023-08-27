<!-- 投稿削除用モーダル -->
<div class="modal fade" id="deletePostModal{{ $post->id }}" tabindex="-1" aria-labelledby="deletePostModalLabel{{ $post->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <!-- モーダルのタイトル -->
                <h5 class="modal-title" id="deletePostModalLabel{{ $post->id }}">投稿を削除</h5>

                <!-- モーダルを閉じるボタン -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
            </div>
            <div class="modal-body">

                <!-- 削除確認のメッセージ -->
                この投稿を削除してもよろしいですか？
            </div>
            <div class="modal-footer">

                <!-- キャンセルボタン -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>

                <!-- 投稿削除のためのフォーム -->
                <form action="{{ route('posts.destroy', $post) }}" method="post">
                    @csrf <!-- CSRFトークン -->
                    @method('delete') <!-- HTTPのDELETEメソッドを指定 -->

                    <!-- 削除ボタン -->
                    <button type="submit" class="btn btn-danger">削除</button>
                </form>
            </div>
        </div>
    </div>
</div>