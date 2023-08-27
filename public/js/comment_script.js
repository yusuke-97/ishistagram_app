function bindEventListeners() {
    // 既存のイベントリスナーを解除
    $(document).off('click', '.delete-comment-icon');
    $(document).off('click', '.comment-button');

    // 新しくイベントリスナーをバインド
    $(document).on('click', '.delete-comment-icon', function(e) {
        e.stopPropagation();
        console.log("Delete comment icon clicked!");
        var commentId = $(this).data('comment-id');
        deleteComment(commentId);
    });

    $(document).on('click', '.comment-button', function() {
        var postId = $(this).data('post-id');
        console.log('Clicked on post ID:', postId);

        var commentInputSection = $('#comment-input-' + postId);
        console.log('Comment Input Section:', commentInputSection.length > 0 ? 'Found' : 'Not Found');

        commentInputSection.toggle();
    });
}

$(document).ready(function() {
    $('.delete-comment-button').addClass('delete-comment-icon').removeClass('delete-comment-button');

    // 初期イベントリスナーの設定
    bindEventListeners();
});




function submitComment(postId) {
    var commentText = $('#comment-text-' + postId).val();
    var commentInputSection = $('#comment-input-' + postId);
    var csrf_token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/ishistagram/public/comments',
        method: 'POST',
        data: {
            content: commentText,
            post_id: postId,
            _token: csrf_token
        },
        success: function(response) {
            console.log("Comment added:", response);
    
            // コメント入力欄を非表示にする
            commentInputSection.hide();
    
            // 新しく追加されたコメントを表示
            var commentHtml = '<div class="comment-section d-flex align-items-center mb-2 justify-content-between" style="padding-left: 16px; padding-right: 16px" id="comment-section-' + response.id + '">' + 
                                  '<div class="d-flex align-items-center">' +
                                      '<i class="fa-solid fa-reply me-1"></i>' + 
                                      '<strong style="font-weight: bold;" class="me-2">' + response.user_name + '</strong>' + 
                                      '<span>' + commentText + '</span>' +
                                  '</div>' + 
                                  '<i class="fa-solid fa-trash-can delete-comment-icon" data-comment-id="' + response.id + '"></i>' +
                              '</div>';
            commentInputSection.before(commentHtml);

            $('#comment-text-' + postId).val('');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
            console.error('Response:', jqXHR.responseText);
        }
    });
}

// モーダルが閉じられたときに、コメント入力欄を非表示にするとともに、イベントのバインドを解除
$('.modal').on('hidden.bs.modal', function () {
    $('.comment-input-section').hide();

});


function deleteComment(commentId) {
    console.log('deleteComment function called with commentId:', commentId);
    
    // 既存のイベントリスナーを解除
    $(document).off('click', '.delete-comment-icon');

    // 確認メッセージを表示
    var result = confirm('削除しますか？');
    console.log("Confirm result:", result);

    // ユーザーがキャンセルを選択した場合、処理を終了
    if (!result) {
        // イベントリスナーを再バインド
        bindEventListeners();
        return;
    }

    var csrf_token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/ishistagram/public/comments/' + commentId,
        method: 'DELETE',
        data: {
            _token: csrf_token
        },
        success: function(response) {
            console.log("Comment deleted:", response);

            // 削除されたコメントをDOMから削除
            $('#comment-section-' + commentId).remove();

            // イベントリスナーを再バインド
            bindEventListeners();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
            console.error('Response:', jqXHR.responseText);

            // イベントリスナーを再バインド
            bindEventListeners();
        }
    });
}