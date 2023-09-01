document.addEventListener('DOMContentLoaded', function () {
    // Navbarの要素を取得
    var navbar = document.querySelector('#navbarSupportedContent'); // Navbarの要素を取得
    
    // プロフィールドロップダウンの要素を取得
    var profileDropdown = document.querySelector('.header-dropdown'); 
    
    // ナビバートグルの要素を取得
    var navbarToggler = document.querySelector('.navbar-toggler');
    
    // BootstrapのCollapse機能を利用するためのインスタンス生成
    var myCollapse = new bootstrap.Collapse(navbar, { toggle: false }); // BootstrapのCollapse機能を利用するためのインスタンス生成

    // プロフィールドロップダウンが存在する場合のロジック
    if (profileDropdown) {
        var profileDropdownTrigger = profileDropdown.querySelector('#navbarDropdown');
        var profileDropdownMenu = profileDropdown.querySelector('.dropdown-menu');

        // プロフィールドロップダウンのインスタンスを作成
        var myDropdown = new bootstrap.Dropdown(profileDropdownTrigger);

        // 全体のクリックイベント
        document.addEventListener('click', function (event) {
            // クリックがNavbarかToggler以外の場合にNavbarを閉じる
            if (navbar && !navbar.contains(event.target) && !event.target.classList.contains('navbar-toggler-icon')) {
                myCollapse.hide();
            }

            // クリックがプロフィールドロップダウントリガーやメニュー以外の場合にドロップダウンを閉じる
            if (!profileDropdownTrigger.contains(event.target) && !profileDropdownMenu.contains(event.target)) {
                myDropdown.hide();
            }
        });

        // ドロップダウントリガーのクリックイベント
        profileDropdownTrigger.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            // ドロップダウンメニューの表示/非表示を切り替える
            if (profileDropdownMenu.classList.contains('show')) {
                myDropdown.hide();
            } else {
                myDropdown.show();
            }
        });
    }

    // 三本線のメニューのクリックイベント
    if (navbarToggler) {
        navbarToggler.addEventListener('click', function(event) {
            event.stopPropagation();
            
            // Navbarの表示/非表示を切り替える
            if (navbar.classList.contains('show')) {
                myCollapse.hide();
            } else {
                myCollapse.show();
            }

            // プロフィールのドロップダウンメニューが開いている場合、それを閉じる
            if (profileDropdownMenu && profileDropdownMenu.classList.contains('show')) {
                myDropdown.hide();
            }
        });
    }    

    // メインコンテンツの高さを調整
    adjustMainHeight();

    // ウィンドウサイズが変更された場合、メインコンテンツの高さを再調整
    window.addEventListener('resize', adjustMainHeight);
});

// ヘッダーの高さに合わせて、メインコンテンツの上部マージンを調整する関数
function adjustMainHeight() {
    var headerHeight = document.querySelector('.navbar.fixed-top').offsetHeight;
    var mainElement = document.querySelector('main');

    mainElement.style.marginTop = headerHeight + 'px';
}
