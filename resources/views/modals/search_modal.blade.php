<!-- 検索用のモーダルウィンドウ -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <!-- モーダルのタイトル -->
                <h5 class="modal-title" id="searchModalLabel" style="font-weight: bold;">検索</h5>

                <!-- モーダルを閉じるボタン -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
            </div>
            <div class="modal-body">

                <!-- 検索フォーム -->
                <form action="{{ route('tags.search') }}" method="GET" id="search-form" autocomplete="off">

                    <!-- 検索キーワード入力欄 -->
                    <input type="text" class="form-control" name="query" id="query-input" placeholder="検索" autocomplete="off">

                    <!-- オートコンプリートの結果を表示するエリア -->
                    <div id="autocomplete-results" class="autocomplete-results"></div>

                </form>

                <!-- JS変数の定義 -->
                <script>
                    window.profileImagesUrl = "{{ Storage::disk('s3')->url('profile_images/') }}";
                </script>

                <!-- 検索関連のJavaScript -->
                <script src="{{ asset('js/search_script.js') }}"></script>
            </div>
        </div>
    </div>
</div>