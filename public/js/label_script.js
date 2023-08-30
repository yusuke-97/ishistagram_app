$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

let labels = [];
const MAX_ALLOWED_CHECKBOXES = 2;

function countCheckedCheckboxes() {
    return document.querySelectorAll('#labelsList input[type="checkbox"]:checked').length;
}

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

$('#labelsList').on('change', 'input[type="checkbox"]', function() {
    toggleCheckboxDisabledState();
});

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

document.getElementById('shareButton').addEventListener('click', function() {
    const uncheckedLabels = document.querySelectorAll('#labelsList input[type="checkbox"]:not(:checked)');
    
    uncheckedLabels.forEach(label => {
        $.ajax({
            url: '/labels',  // 他の投稿で使用されているか確認するエンドポイント
            method: 'GET',
            data: { label: label.value },
            success: function(isUsed) {
                if (!isUsed) {
                    labels = labels.filter(l => l !== label.value);
                    label.closest('.form-check').remove();
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', (event) => {
    toggleCheckboxDisabledState();
});
