/**
 * イベントリスナーをバインドする関数
 */
function bindEventListeners() {
    // 既存のイベントリスナーを解除
    $(document).off('click', '.delete-comment-icon');
    $(document).off('click', '.comment-button');

    // コメント削除アイコンがクリックされたときのイベント
    $(document).on('click', '.delete-comment-icon', function(e) {
        e.stopPropagation();
        var commentId = $(this).data('comment-id');
        deleteComment(commentId);
    });

    // コメントボタンがクリックされたときのイベント
    $(document).on('click', '.comment-button', function() {
        var postId = $(this).data('post-id');
        var commentInputSection = $('#comment-input-' + postId);
        commentInputSection.toggle();
    });
}

$(document).ready(function() {
    // クラス名の置き換え
    $('.delete-comment-button').addClass('delete-comment-icon').removeClass('delete-comment-button');

    // 初期イベントリスナーの設定
    bindEventListeners();
});


function submitComment(postId) {
    var commentText = $('#comment-text-' + postId).val();
    var commentInputSection = $('#comment-input-' + postId);
    var csrf_token = $('meta[name="csrf-token"]').attr('content');

    // Ajaxによるコメントのサブミット
    $.ajax({
        url: '/comments',
        method: 'POST',
        data: {
            content: commentText,
            post_id: postId,
            _token: csrf_token
        },
        success: function(response) {
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

/**
 * モーダルが閉じられたときのイベント
 */
$('.modal').on('hidden.bs.modal', function () {
    $('.comment-input-section').hide();

});


function deleteComment(commentId) {
    // 既存のイベントリスナーを解除
    $(document).off('click', '.delete-comment-icon');

    // 削除の確認
    var result = confirm('削除しますか？');

    if (!result) {
        bindEventListeners();
        return;
    }

    var csrf_token = $('meta[name="csrf-token"]').attr('content');

    // Ajaxによるコメントの削除
    $.ajax({
        url: '/comments/' + commentId,
        method: 'DELETE',
        data: {
            _token: csrf_token
        },
        success: function(response) {
            $('#comment-section-' + commentId).remove();
            bindEventListeners();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            bindEventListeners();
        }
    });
}