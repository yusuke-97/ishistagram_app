// ページが全て読み込まれた後に実行される関数
document.addEventListener("DOMContentLoaded", function () {
    // 各「編集」モーダルの動作設定
    document.querySelectorAll('[id^="editPostModal"]').forEach(modal => {
        // モーダルが表示される前のイベントをリッスン
        modal.addEventListener('show.bs.modal', (event) => {
            // クリックされた「編集」ボタンを取得
            let editButton = event.relatedTarget;
            
            // ボタンから投稿の情報を取得
            let postId = editButton.getAttribute('data-post-id');
            let postTitle = editButton.getAttribute('data-post-title');
            let postContent = editButton.getAttribute('data-post-content');
            
            // モーダル内のフォームを取得
            let editPostForm = modal.querySelector('form[name="editPostForm"]'); // モーダル内のフォームを取得

            // フォームの各要素に取得した投稿の情報を設定
            editPostForm.action = `posts/${postId}`;
            editPostForm.title.value = postTitle;
            editPostForm.content.value = postContent;
        });
    });

    // 各「削除」モーダルの動作設定
    document.querySelectorAll('[id^="deletePostModal"]').forEach(modal => {
        // モーダルが表示される前のイベントをリッスン
        modal.addEventListener('show.bs.modal', (event) => {
            // クリックされた「削除」ボタンを取得
            let deleteButton = event.relatedTarget;
            let postId = deleteButton.dataset.postId;
            
            // ボタンから投稿の情報を取得
            let postTitle = deleteButton.dataset.postTitle;
            
            // モーダル内のメッセージ要素を取得
            let deleteMessage = modal.querySelector('#deletePostModalLabel'); // モーダル内のメッセージ要素を取得
        });
    });
});
