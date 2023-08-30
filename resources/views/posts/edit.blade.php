@extends('layouts.app')

<?php
// データベースから一意のラベル名を取得
$allLabels = DB::table('labels')->distinct('name')->pluck('name')->toArray();

// 現在の投稿に関連するラベルを取得
$selectedLabels = $post->labels->pluck('name')->toArray();
?>

@push('scripts')
<!-- Sortable.jsの読み込み -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

<!-- 初期の選択されたラベルをJavaScript変数にセット -->
<script>
    window.initialSelectedLabels = @json($selectedLabels);
</script>

<script>
    window.appUrl = "{{ url('') }}";
</script>

<!-- ラベル編集に関するJavaScriptの読み込み -->
<script src="{{ asset('js/label_edit.js') }}"></script>
@endpush

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <!-- カードヘッダーの編集 -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="font-weight: bold;">投稿編集</h5>
                </div>

                <!-- 投稿の編集フォーム -->
                <form action="{{ route('posts.update', ['post' => $post->id]) }}" method="post" name="editPostForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">

                        <!-- 投稿画像の表示 -->
                        <div class="row">
                            @foreach($post->images as $image)
                            <div class="col-4 mb-3">
                                <div class="edit-post-image-container">
                                    <img class="card-img-top" style="border-radius: 0;" src="{{ Storage::disk('s3')->url($image->file_path) }}" alt="投稿画像" id="editImage{{ $post->id }}">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- 投稿文の編集領域 -->
                        <div class="mb-3">
                            <label for="content{{ $post->id }}" class="form-label" style="font-weight: bold;">投稿文</label>
                            <textarea class="form-control" style="resize: none;" id="content{{ $post->id }}" name="content" rows="4" required>{{ $post->content }}</textarea>
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
                                    <input class="form-check-input" type="checkbox" value="{{ $label }}" id="label-{{ $loop->index }}" name="labels[]" {{ in_array($label, $selectedLabels) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="label-{{ $loop->index }}">
                                        {{ $label }}
                                    </label>
                                </div>
                                @endforeach
                            </div>

                            <!-- ラベル追加ボタン -->
                            <button type="button" class="btn mt-2 add-label-btn" id="addLabel">ラベル追加</button>
                        </div>

                    </div>

                    <!-- フォームの送信ボタン -->
                    <div class="card-footer text-center">
                        <button type="submit" class="btn share-btn" id="updateButton">
                            <i class="fa-regular fa-paper-plane"></i> 更新
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection