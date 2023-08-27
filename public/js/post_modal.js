function isAnyModalOpen() {
    return !!$('.modal.show').length;
}

function showPostDetails(postId) {
    if (isAnyModalOpen()) {
        // すでに開いているモーダルがあれば、新しいモーダルは表示しない
        return;
    }

    var url = getPostShowRoute(postId);

    $.get(url, function(response) {
        var modalID = "#showPostModal" + postId;

        // .modal-bodyの内容だけを抽出
        var modalBodyContent = $(response).find('.modal-body').html();

        // モーダルの内容を更新
        $(modalID + ' .modal-body').html(modalBodyContent);

        // モーダル内の特定のドロップダウンメニューを非表示にする
        $(modalID + ' .user-action-dropdown .dropdown-menu').hide();

        // イベントリスナーを再度バインド
        bindEventListeners();


        // モーダルが表示された直後のイベントをバインド
        $(modalID).off('shown.bs.modal').on('shown.bs.modal', function() {
            // モーダル内のドロップダウン初期化前に既存の初期化を解除
        $(this).find('[data-bs-toggle="dropdown"]').dropdown('dispose');

        var dropdownElements = $(this).find('[data-bs-toggle="dropdown"]');
        dropdownElements.each(function() {
            var dropdownInstance = new bootstrap.Dropdown(this, {
                display: 'static'  // Popper.jsの挙動を止める
            });
            dropdownInstance.update(); // 位置を再計算
        });
        
            // transformのリセット
            $(this).find('.dropdown-menu').css('transform', '');
        
            // イベントリスナーの追加前に既存のイベントリスナーを解除
            $(this).find('.dropdown-toggle').off('click').on('click', function() {
                var dropdownMenu = $(this).next('.dropdown-menu');
                dropdownMenu.toggle();
            });
        
            // モーダル内のドロップダウンを強制的に閉じる
            var dropdownElement = $(this).find('.dropdown');
            dropdownElement.removeClass('show');
            dropdownElement.find('.dropdown-menu').hide();
        });
        
        $(modalID).modal('show');
        
    });
}


$(document).click(function(event) {
    if (isAnyModalOpen()) {
        var target = $(event.target);

        if (!target.closest('.dropdown-toggle').length && !target.closest('.dropdown-menu').length) {
            $('.user-action-dropdown .dropdown-menu').hide();
        }
    }
});