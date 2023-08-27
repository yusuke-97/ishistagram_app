<!-- フォロワー情報を表示するためのモーダルウィンドウ -->
<div class="modal fade" id="followersModal{{ $user->id }}" tabindex="-1" aria-labelledby="followersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <!-- モーダルのタイトル -->
                <h5 class="modal-title" id="followersModalLabel" style="font-weight: bold;">フォロワー</h5>

                <!-- モーダルを閉じるボタン -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
            </div>
            <div class="modal-body" id="followers-list">
                <!-- ここにユーザーのフォロワーのリストが表示される -->
            </div>
        </div>
    </div>
</div>

<!-- フォロー情報を表示するためのモーダルウィンドウ -->
<div class="modal fade" id="followingModal{{ $user->id }}" tabindex="-1" aria-labelledby="followingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <!-- モーダルのタイトル -->
                <h5 class="modal-title" id="followingModalLabel" style="font-weight: bold;">フォロー中</h5>

                <!-- モーダルを閉じるボタン -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
            </div>
            <div class="modal-body" id="following-list">
                <!-- ここにユーザーがフォローしている人のリストが表示される -->
            </div>
        </div>
    </div>
</div>