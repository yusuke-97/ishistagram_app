@extends('layouts.app')

@push('scripts')
<!-- 投稿に関するJavaScriptの読み込み -->
<script src="{{ asset('/js/post_script.js') }}"></script>

<!-- Sortable.jsの読み込み -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

<!-- ラベル関連のJavaScript -->
<script src="{{ asset('js/label_script.js') }}"></script>
@endpush

<?php
// データベースからラベルの名前を全て取得
$allLabels = DB::table('labels')->pluck('name')->toArray();

// 以前入力されたラベルがある場合はそれを取得、なければ空の配列をセット
$selectedLabels = old('labels') ? explode(',', old('labels')) : [];
?>

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">

            <!-- プロフィール画像がある場合は表示、なければユーザーアイコンを表示 -->
            @if(Auth::user()->profile_image)
            <img class="card-header-profile" src="{{ Storage::disk('s3')->url('profile_images/' . Auth::user()->profile_image) }}" alt="User's Profile Image" class="profile-image">
            @else
            <i class="fas fa-user fa-5x card-header-profile-icon"></i>
            @endif
            <span style="font-weight: bold;">{{ Auth::user()->user_name }}</span>
        </div>
        <div class="card-body">
            <div class="mb-4">

                <!-- プロフィールへのリンクボタン -->
                <a href="{{ route('profile.default') }}" class="btn share-btn">戻る</a>
            </div>

            <!-- 投稿フォームの開始 -->
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">

                <!-- エラーメッセージの表示 -->
                @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                @csrf

                <!-- 画像選択ボタン -->
                <div class="form-group mb-3">
                    <input type="file" name="image[]" id="image" class="form-control" multiple style="display: none;">
                    <button id="customButton" class="btn select-image-btn me-3">
                        <i class="fa-regular fa-image"></i> 画像を選択
                    </button>
                    <span>※ 最大5枚まで</span>
                </div>

                <!-- 画像のプレビューエリア -->
                <div id="imagePreview" class="row mb-3"></div>

                <!-- 投稿文の入力エリア -->
                <div class="form-group mb-4">
                    <label for="content" class="mb-2" style="font-weight: bold;">投稿文</label>
                    <textarea name="content" id="content" class="form-control" value="{{ old('content') }}" rows="5"></textarea>
                </div>

                <!-- ラベルの編集領域 -->
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: bold;">ラベル</label>
                    @if ($errors->has('labels'))
                    <span class="text-danger">{{ $errors->first('labels') }}</span>
                    @endif
                    <input type="text" id="labelInput" class="form-control" placeholder="ラベルを入力してください" autocomplete="off">
                    <div id="labelsList" class="mt-2">

                        <!-- チェックボックスでラベルを表示 -->
                        @foreach($labels as $label)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $label->name }}" id="label-{{ $loop->index }}" name="labels[]" {{ in_array($label->name, $selectedLabels) ? 'checked' : '' }}>
                            <label class="form-check-label" for="label-{{ $loop->index }}">
                                {{ $label->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <!-- ラベル追加ボタン -->
                    <div class="mt-2" style="display: flex; align-items: center;">
                        <button type="button" class="btn add-label-btn me-3" id="addLabel">ラベル追加</button>
                        <span>※ 最大2個まで</span>
                    </div>
                </div>

                <!-- シェアボタン -->
                <button type="submit" class="btn share-btn" id="shareButton">
                    <i class="fa-regular fa-paper-plane"></i> シェア
                </button>

            </form>
        </div>
    </div>
</div>
@endsection