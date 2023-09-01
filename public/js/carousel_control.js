$(document).ready(function () {

    // 各カルーセルを処理するためのループ
    $(".carousel").each(function () {
        var $carousel = $(this);

        /**
         * カルーセルの矢印の表示・非表示を切り替える関数
         * 最初と最後のアイテムでは矢印を非表示にする
         */
        function checkArrows() {
            var totalItems = $carousel.find(".carousel-item").length;
            var currentIndex = $carousel.find(".carousel-inner .active").index();

            // すべての矢印をデフォルトで表示する
            $carousel.find(".carousel-control-prev, .carousel-control-next").show();

            // 最初のアイテムの場合、前の矢印を非表示にする
            if (currentIndex === 0) {
                $carousel.find(".carousel-control-prev").hide();
            }

            // 最後のアイテムの場合、次の矢印を非表示にする
            if (currentIndex === totalItems - 1) {
                $carousel.find(".carousel-control-next").hide();
            }
        }

        // 初回ページ読み込み時とカルーセルがスライドしたたびに矢印をチェック
        checkArrows();
        $carousel.on("slid.bs.carousel", checkArrows);
    });

});
