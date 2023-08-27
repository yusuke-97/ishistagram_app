<!-- ヘッダーナビゲーションバー -->
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
    <div class="container">

        <!-- ロゴ部分 -->
        <a class="navbar-brand" style="margin-left: 8px;" href="{{ route('posts.index') }}">
            <img src="{{ asset('img/logo.png') }}" alt="App Logo" width="30" height="30" style="border-radius: 0.25rem; margin-right: 8px;">
            <span style="font-weight: bold;">{{ config('app.name', 'Laravel') }}</span>
        </a>

        <!-- モバイル表示時のメニューボタン -->
        <button class="navbar-toggler" style="margin-right: 8px;" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- ナビゲーションのリンク集 -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <!-- ナビゲーションの左側（現状未使用） -->
            <ul class="navbar-nav me-auto">
            </ul>

            <!-- ナビゲーションの右側 -->
            <ul class="navbar-nav ms-auto">

                <!-- 認証リンク（ログイン・登録 or プロフィール・ログアウト） -->
                @guest
                @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">ログイン</a>
                </li>
                @endif

                @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">登録</a>
                </li>
                @endif

                @else
                <!-- ログイン時のみ表示 -->
                <!-- ホームへのリンク -->
                <li class="nav-item ml-4">
                    <a class="nav-link" href="{{ route('posts.index') }}">
                        <div class="d-flex align-items-center flex-nowrap">
                            <i class="fa-solid fa-house-user header-icon icon-wrapper icon-space"></i>
                            <span>ホーム</span>
                        </div>
                    </a>
                </li>

                <!-- 検索機能 -->
                <li class="nav-item ml-4">
                    <a class="nav-link" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <div class="d-flex align-items-center flex-nowrap">
                            <i class="fas fa-search header-icon icon-wrapper icon-space"></i>
                            <span>検索</span>
                        </div>
                    </a>
                </li>

                <!-- 投稿作成ページへのリンク -->
                <li class="nav-item ml-4">
                    <a class="nav-link" href="{{ route('posts.create') }}">
                        <div class="d-flex align-items-center flex-nowrap">
                            <i class="fa-regular fa-square-plus header-icon icon-wrapper icon-space"></i>
                            <span>作成</span>
                        </div>
                    </a>
                </li>

                <!-- ログインしているユーザーのドロップダウンメニュー -->
                <li class="nav-item dropdown header-dropdown ml-4">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <div class="d-flex align-items-center">
                            @if(Auth::user()->profile_image)
                            <img class="header-profile" src="{{ asset('storage/profile_images/' . Auth::user()->profile_image) }}" alt="プロフィール画像">
                            @else
                            <i class="fas fa-user fa-lg header-profile-icon"></i>
                            @endif
                            <span>プロフィール</span>
                        </div>
                    </a>

                    <!-- ドロップダウンメニューの内容 -->
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                        <!-- プロフィールページへのリンク -->
                        <a class="dropdown-item" style="font-weight: bold;" href="{{ route('profile.default') }}">
                            <i class="fa-solid fa-user me-2"></i>
                            プロフィール
                        </a>

                        <!-- ログアウト機能 -->
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-lock me-2"></i>
                            ログアウト
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>

                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- ヘッダー関連のJavaScript -->
<script src="{{ asset('js/header_script.js') }}"></script>