$(document).ready(function () {
    $(".carousel").each(function () {
        var $carousel = $(this);

        function checkArrows() {
            var totalItems = $carousel.find(".carousel-item").length;
            var currentIndex = $carousel
                .find(".carousel-inner .active")
                .index();

            // すべての矢印を表示
            $carousel
                .find(".carousel-control-prev, .carousel-control-next")
                .show();

            if (currentIndex === 0) {
                $carousel.find(".carousel-control-prev").hide();
            }

            if (currentIndex === totalItems - 1) {
                $carousel.find(".carousel-control-next").hide();
            }
        }

        // 初回ページ読み込み時とカルーセルがスライドしたたびに矢印のチェック
        checkArrows();
        $carousel.on("slid.bs.carousel", checkArrows);
    });
});