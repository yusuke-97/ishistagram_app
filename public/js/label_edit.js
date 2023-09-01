// CSRFトークンをAjaxのヘッダにセット（Laravelのセキュリティ対策のため）
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// ラベルの配列
let labels = [];

// 選択できるチェックボックスの最大数
const MAX_ALLOWED_CHECKBOXES = 2;

// 現在選択されているチェックボックスの数をカウントする関数
function countCheckedCheckboxes() {
    return document.querySelectorAll('#labelsList input[type="checkbox"]:checked').length;
}

// チェックボックスの無効/有効状態を切り替える関数
function toggleCheckboxDisabledState() {
    if (countCheckedCheckboxes() >= MAX_ALLOWED_CHECKBOXES) {
        document.querySelectorAll('#labelsList input[type="checkbox"]:not(:checked)').forEach(function(checkbox) {
            checkbox.disabled = true;
        });
    } else {
        document.querySelectorAll('#labelsList input[type="checkbox"]:not(:checked)').forEach(function(checkbox) {
            checkbox.disabled = false;
        });
    }
}

// チェックボックスの変更を検知して、無効/有効状態を切り替える
$('#labelsList').on('change', 'input[type="checkbox"]', function() {
    toggleCheckboxDisabledState();
});

// ラベルを追加するイベント
document.getElementById('addLabel').addEventListener('click', function() {
    const labelInput = document.getElementById('labelInput');
    const labelValue = labelInput.value.trim();

    if (labelValue && !labels.includes(labelValue)) {
        labels.push(labelValue);
        const shouldCheck = countCheckedCheckboxes() < MAX_ALLOWED_CHECKBOXES;
        addLabelToDisplayArea(labelValue, shouldCheck);
        labelInput.value = '';
        toggleCheckboxDisabledState();
    }
});

// ディスプレイエリアにラベルを追加する関数
function addLabelToDisplayArea(labelValue, isCheckedByDefault = false) {
    const labelsListDiv = document.getElementById('labelsList');

    var labelContainerDiv = document.createElement('div');
    labelContainerDiv.className = 'form-check';

    var checkbox = document.createElement('input');
    checkbox.setAttribute('type', 'checkbox');
    checkbox.className = 'form-check-input label-checkbox';
    checkbox.value = labelValue;
    checkbox.name = 'labels[]';

    if (isCheckedByDefault) {
        checkbox.checked = true;
    }

    var labelElement = document.createElement('label');
    labelElement.className = 'form-check-label';
    labelElement.textContent = labelValue;

    labelContainerDiv.appendChild(checkbox);
    labelContainerDiv.appendChild(labelElement);

    labelsListDiv.appendChild(labelContainerDiv);
}

// チェックされていないラベルを更新するイベント
document.getElementById('updateButton').addEventListener('click', function() {
    const uncheckedLabels = document.querySelectorAll('#labelsList input[type="checkbox"]:not(:checked)');
    
    uncheckedLabels.forEach(label => {
        $.ajax({
            url: '/labels',
            method: 'GET',
            data: { label: label.value },
            success: function(isUsed) {
                // 使用されていない場合、ラベルを削除する
                if (!isUsed) {
                    labels = labels.filter(l => l !== label.value);
                    label.closest('.form-check').remove();
                }
            }
        });
    });
});

// ページが読み込まれたとき、チェックボックスの無効/有効状態を初期設定する
document.addEventListener('DOMContentLoaded', (event) => {
    toggleCheckboxDisabledState();
});
