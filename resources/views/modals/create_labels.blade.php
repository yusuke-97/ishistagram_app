<!-- ラベル編集用モーダル -->
<div class="modal fade" id="labelModal" tabindex="-1" aria-labelledby="labelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <!-- モーダルのタイトル -->
                <h5 class="modal-title" id="labelModalLabel">ラベル編集</h5>

                <!-- モーダルを閉じるボタン -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
            </div>
            <div class="modal-body mt-3 mb-3">

                <!-- エラーメッセージが存在する場合、その表示 -->
                @if ($errors->has('labels'))
                <span class="text-danger">{{ $errors->first('labels') }}</span>
                @endif

                <!-- ラベルの入力フォーム -->
                <input type="text" id="labelInput" class="form-control" placeholder="ラベルを入力してください" autocomplete="off">
                <div id="labelsList" class="mt-2">

                    <!-- 既存のラベルをチェックボックスとして表示 -->
                    @if(!empty($labels))
                    @foreach($labels as $label)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{ $label }}" id="label-{{ $loop->index }}" name="labels[]">
                        <label class="form-check-label" for="label-{{ $loop->index }}">
                            {{ $label }}
                        </label>
                    </div>
                    @endforeach
                    @endif
                </div>

                <!-- ラベル追加ボタン -->
                <button type="button" class="btn mt-2 add-label-btn" id="addLabel">ラベル追加</button>
            </div>
            <div class="modal-footer">

                <!-- ラベルの保存ボタン -->
                <button type="button" class="btn share-btn" id="saveLabel">保存</button>
            </div>
        </div>
    </div>
</div>

<!-- ラベル関連のJavaScript -->
<script src="{{ asset('js/label_script.js') }}"></script>