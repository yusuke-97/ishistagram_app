document.addEventListener('DOMContentLoaded', function () {
    var navbar = document.querySelector('#navbarSupportedContent'); // Navbarの要素を取得
    var profileDropdown = document.querySelector('.header-dropdown'); 
    var navbarToggler = document.querySelector('.navbar-toggler');
    
    var myCollapse = new bootstrap.Collapse(navbar, { toggle: false }); // BootstrapのCollapse機能を利用するためのインスタンス生成

    if (profileDropdown) {
        var profileDropdownTrigger = profileDropdown.querySelector('#navbarDropdown');
        var profileDropdownMenu = profileDropdown.querySelector('.dropdown-menu');

        var myDropdown = new bootstrap.Dropdown(profileDropdownTrigger);

        // 全体のクリックイベント
        document.addEventListener('click', function (event) {
            // ナビゲーションを閉じる条件
            if (navbar && !navbar.contains(event.target) && !event.target.classList.contains('navbar-toggler-icon')) {
                myCollapse.hide();
            }

            // ドロップダウンメニューを閉じる条件
            if (!profileDropdownTrigger.contains(event.target) && !profileDropdownMenu.contains(event.target)) {
                myDropdown.hide();
            }
        });

        // ドロップダウントリガーのクリックイベント
        profileDropdownTrigger.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
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

    adjustMainHeight();
    window.addEventListener('resize', adjustMainHeight);
});

function adjustMainHeight() {
    var headerHeight = document.querySelector('.navbar.fixed-top').offsetHeight;
    var mainElement = document.querySelector('main');

    mainElement.style.marginTop = headerHeight + 'px';
}
