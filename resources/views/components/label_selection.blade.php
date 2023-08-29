<!-- ラベル選択表示エリア -->
<div class="dropdown d-block" style="width: 100%;">

    <hr>

    <!-- 投稿とラベル選択リンクのセクション -->
    <div class="d-flex justify-content-center">

        <!-- 現在のルート名に応じてアクティブなリンクを判定する -->
        @if (Auth::id() === $user->id)

        @php
        $isPostActive = Route::currentRouteNamed('profile.default');
        @endphp

        @else

        @php
        $isPostActive = Route::currentRouteNamed('profile.show', ['profile' => $user->id]);
        @endphp

        @endif

        @php
        $isLabelActive = Route::currentRouteNamed('profile.show.withLabel', ['profile' => $user->id, 'label' => $label->name]);
        @endphp

        <!-- 投稿リンク -->
        @if (Auth::id() === $user->id)
        <a href="{{ route('profile.default') }}" class="me-4 text-decoration-none" style="{{ $isPostActive ? 'font-weight: bold; color: black;' : 'color: gray;' }}">
            <i class="fa-solid fa-table-cells me-1" style="{{ $isPostActive ? 'color: black;' : 'color: gray;' }}"></i>
            <span style="font-size: 90%;">投稿</span>
        </a>
        @else
        <a href="{{ route('profile.show', ['profile' => $user->id]) }}" class="me-4 text-decoration-none" style="{{ $isPostActive ? 'font-weight: bold; color: black;' : 'color: gray;' }}">
            <i class="fa-solid fa-table-cells me-1" style="{{ $isPostActive ? 'color: black;' : 'color: gray;' }}"></i>
            <span style="font-size: 90%;">投稿</span>
        </a>
        @endif

        <!-- ラベル選択 -->
        <a href="#" class="text-decoration-none me-2" role="button" id="labelDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="{{ $isLabelActive ? 'font-weight: bold; color: black;' : 'color: gray;' }}">
            <i class="fa-regular fa-folder me-1" style="{{ $isLabelActive ? 'color: black;' : 'color: gray;' }}"></i>
            <span style="font-size: 90%;">ラベル選択</span>
        </a>


        <!-- ラベル選択のドロップダウンメニュー -->
        <ul class="dropdown-menu" aria-labelledby="labelDropdown">

            <!-- 「すべて表示」のリンク -->
            @if (Auth::id() === $user->id)
            <li><a class="dropdown-item" href="{{ route('profile.default') }}">すべて表示</a></li>
            @else
            <li><a class="dropdown-item" href="{{ route('profile.show', ['profile' => $user->id]) }}">すべて表示</a></li>
            @endif

            <!-- 各ラベルをループして表示 -->
            @foreach($labels as $label)
            <li><a class="dropdown-item" href="{{ route('profile.show.withLabel', ['profile' => $user->id, 'label' => $label->name]) }}">{{ $label->name }}</a></li>
            @endforeach

        </ul>
    </div>

    <hr>

    <!-- 現在選択されているラベル名を表示するセクション -->
    @php
    $currentLabel = request()->get('label'); // URLの'label'クエリからラベル名を取得
    @endphp

    <p class="mt-3 mb-0 text-center" style="font-weight: bold;">

        <!-- ラベルが選択されている場合のみアイコンを表示 -->
        @if(isset($currentLabel) && !empty($currentLabel))
        <i class="fa-solid fa-tag"></i>
        @endif

        <!-- 現在選択されているラベル名、または「すべてを表示」テキストを表示 -->
        <span style="font-size: 90%;">{{ isset($currentLabel) && !empty($currentLabel) ? $currentLabel : 'すべてを表示' }}</span>
    </p>
</div>

<!-- ラベルの選択用モーダル -->
<script src="{{ asset('js/label_selection.js') }}"></script>