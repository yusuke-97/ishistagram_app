// ページのコンテンツが完全に読み込まれたら実行
document.addEventListener("DOMContentLoaded", function() {
    // モーダル要素をすべて選択
    const modals = document.querySelectorAll('.modal');

    // 各モーダルに対してイベントリスナーを設定
    modals.forEach(modal => {
        // モーダルが表示されたときのイベント
        modal.addEventListener('shown.bs.modal', function() {
            // モーダル内のカルーセル要素を選択
            const carousel = this.querySelector('.carousel');
            
            // カルーセルが存在しない場合、関数から退出
            if (!carousel) return;

            // 現在のカルーセルアイテムの位置に基づいて矢印を表示または非表示にする関数
            function checkArrows() {
                const totalItems = carousel.querySelectorAll('.carousel-item').length;
                const activeItem = carousel.querySelector('.carousel-inner .active');
                const currentIndex = Array.from(activeItem.parentNode.children).indexOf(activeItem);

                // 初めにすべての矢印を表示
                carousel.querySelectorAll('.carousel-control-prev, .carousel-control-next').forEach(arrow => arrow.style.display = 'block');

                // アクティブなアイテムが最初の場合、前の矢印を非表示にする
                if (currentIndex === 0) {
                    carousel.querySelector('.carousel-control-prev').style.display = 'none';
                } 

                // アクティブなアイテムが最後の場合、次の矢印を非表示にする
                if (currentIndex === totalItems - 1) {
                    carousel.querySelector('.carousel-control-next').style.display = 'none';
                }
            }

            // 初回のモーダル表示時に矢印を確認
            checkArrows();

            // カルーセルがスライドされた後、再度矢印を確認
            carousel.addEventListener('slid.bs.carousel', checkArrows);
        });
    });
});