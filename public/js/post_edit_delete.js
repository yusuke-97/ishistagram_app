document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('[id^="editPostModal"]').forEach(modal => {
        modal.addEventListener('show.bs.modal', (event) => {
            let editButton = event.relatedTarget;
            let postId = editButton.getAttribute('data-post-id');
            let postTitle = editButton.getAttribute('data-post-title');
            let postContent = editButton.getAttribute('data-post-content');
            let editPostForm = modal.querySelector('form[name="editPostForm"]'); // モーダル内のフォームを取得

            editPostForm.action = `posts/${postId}`;
            editPostForm.title.value = postTitle;
            editPostForm.content.value = postContent;
        });
    });

    document.querySelectorAll('[id^="deletePostModal"]').forEach(modal => {
        modal.addEventListener('show.bs.modal', (event) => {
            let deleteButton = event.relatedTarget;
            let postId = deleteButton.dataset.postId;
            let postTitle = deleteButton.dataset.postTitle;
            let deleteMessage = modal.querySelector('#deletePostModalLabel'); // モーダル内のメッセージ要素を取得
        });
    });
});
