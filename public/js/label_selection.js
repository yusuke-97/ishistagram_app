document.addEventListener('DOMContentLoaded', function() {
    var labelLinks = document.querySelectorAll('.dropdown-menu .dropdown-item');

    labelLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
            // クリックされたラベル名のテキストを取得
            var selectedLabelName = event.currentTarget.textContent;
        });
    });

    // ラベル選択のトリガーを取得
    var labelDropdownTrigger = document.getElementById('labelDropdown');

    // ラベル選択のドロップダウンメニューを取得
    var labelDropdownMenu = labelDropdownTrigger.nextElementSibling;

    // トリガーをクリックしたときのイベント
    labelDropdownTrigger.addEventListener('click', function(event) {
        event.preventDefault(); // デフォルトの動作をキャンセル

        // ドロップダウンメニューが表示されていれば非表示に、非表示であれば表示にする
        if (labelDropdownMenu.style.display === 'none' || labelDropdownMenu.style.display === '') {
            labelDropdownMenu.style.display = 'block';
        } else {
            labelDropdownMenu.style.display = 'none';
        }
    });

    // 他の部分をクリックしたときにドロップダウンメニューを閉じる
    document.addEventListener('click', function(event) {
        if (!labelDropdownTrigger.contains(event.target) && !labelDropdownMenu.contains(event.target)) {
            labelDropdownMenu.style.display = 'none';
        }
    });

});
