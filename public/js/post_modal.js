function isAnyModalOpen() {
    return !!$('.modal.show').length;
}

function showPostDetails(postId) {
    if (isAnyModalOpen()) {
        // 他のモーダルがすでに開かれている場合、新しいモーダルを開かない
        return;
    }

    var url = getPostShowRoute(postId);

    $.get(url, function(response) {
        var modalID = "#showPostModal" + postId;

        // レスポンスから.modal-bodyの内容を取得
        var modalBodyContent = $(response).find('.modal-body').html();

        // 取得した内容でモーダルの内容を更新
        $(modalID + ' .modal-body').html(modalBodyContent);

        // モーダル内のドロップダウンメニューを非表示にする
        $(modalID + ' .user-action-dropdown .dropdown-menu').hide();

        // イベントリスナーをバインド（具体的な動作は関数内で定義）
        bindEventListeners();


        // モーダルが完全に表示された後のイベントをバインド
        $(modalID).off('shown.bs.modal').on('shown.bs.modal', function() {
            // 既存のドロップダウンの初期化を解除
            $(this).find('[data-bs-toggle="dropdown"]').dropdown('dispose');

            // 新たにドロップダウンを初期化
            var dropdownElements = $(this).find('[data-bs-toggle="dropdown"]');
            dropdownElements.each(function() {
                var dropdownInstance = new bootstrap.Dropdown(this, {
                    display: 'static'  // Popper.jsの動作を無効化
                });
                dropdownInstance.update(); // 位置を再計算
            });
        
            // transformをリセットしてドロップダウンの位置を調整
            $(this).find('.dropdown-menu').css('transform', '');
        
            // 既存のイベントリスナーを解除して、新しいイベントリスナーを追加
            $(this).find('.dropdown-toggle').off('click').on('click', function() {
                var dropdownMenu = $(this).next('.dropdown-menu');
                dropdownMenu.toggle();
            });
        
            // モーダル内のドロップダウンを閉じる
            var dropdownElement = $(this).find('.dropdown');
            dropdownElement.removeClass('show');
            dropdownElement.find('.dropdown-menu').hide();
        });
        
        // モーダルを表示
        $(modalID).modal('show');
    });
}

// 画面の任意の位置がクリックされたときの動作
$(document).click(function(event) {
    // 何らかのモーダルが開かれている場合のみ動作
    if (isAnyModalOpen()) {
        var target = $(event.target);

        // ドロップダウンメニューまたはトグルボタン外をクリックした場合、メニューを非表示にする
        if (!target.closest('.dropdown-toggle').length && !target.closest('.dropdown-menu').length) {
            $('.user-action-dropdown .dropdown-menu').hide();
        }
    }
});