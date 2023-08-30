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


// $.ajaxSetup({
//     headers: {
//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//     }
// });

// let labels = [];
// const initialSelectedLabels = window.initialSelectedLabels || []; // もしundefinedなら空の配列を設定
// const MAX_ALLOWED_CHECKBOXES = 2;

// function countCheckedCheckboxes() {
//     return document.querySelectorAll('#labelsList input[type="checkbox"]:checked').length;
// }

// function toggleCheckboxDisabledState() {
//     const shouldBeDisabled = countCheckedCheckboxes() >= MAX_ALLOWED_CHECKBOXES;
//     document.querySelectorAll('#labelsList input[type="checkbox"]:not(:checked)').forEach(function(checkbox) {
//         checkbox.disabled = shouldBeDisabled;
//     });
// }

// $('#labelsList').on('change', 'input[type="checkbox"]', function() {
//     toggleCheckboxDisabledState();
// });

// $(document).ready(function() {
//     fetchUserLabels();
//     displayInitialLabels();
// });

// function fetchUserLabels() {
//     $.ajax({
//         url: '/labels',
//         method: 'GET',
//         success: function(response) {
//             if (response.labels) {
//                 labels = [...new Set([...labels, ...response.labels])];
//                 displayLabels();
                
//                 const selectedLabels = document.getElementById('hiddenLabels').value.split(',');
//                 labels.forEach(function(labelValue) {
//                     const checkbox = document.querySelector(`#labelsList input[value="${labelValue}"]`);
//                     if (checkbox && selectedLabels.includes(labelValue)) {
//                         checkbox.checked = true;
//                     }
//                 });
//                 toggleCheckboxDisabledState();
//             }
//         }
//     });
// }

// function displayInitialLabels() {
//     const selectedLabelsDiv = document.getElementById('selectedLabels'); 

//     if (Array.isArray(initialSelectedLabels)) {
//         initialSelectedLabels.forEach(function(label) {
//             if (labels.includes(label)) {
//                 const labelDiv = document.createElement('div');
//                 labelDiv.className = 'selected-label-item';

//                 const labelSpan = document.createElement('span');
//                 labelSpan.textContent = label;
//                 labelDiv.appendChild(labelSpan);

//                 const deleteButton = document.createElement('button');
//                 deleteButton.className = 'delete-label-btn';
//                 var deleteIcon = document.createElement('i');
//                 deleteIcon.className = 'fa-solid fa-xmark';
//                 deleteButton.appendChild(deleteIcon);

//                 deleteButton.addEventListener('click', function() {
//                     selectedLabelsDiv.removeChild(labelDiv);
//                     labels = labels.filter(labelValue => labelValue !== label);
//                     document.getElementById('hiddenLabels').value = labels.join(',');
//                 });

//                 labelDiv.appendChild(deleteButton);
//                 selectedLabelsDiv.appendChild(labelDiv);
//             }
//         });
//     }
// }

// function displayLabels() {
//     const labelsListDiv = document.getElementById('labelsList');
//     labelsListDiv.innerHTML = '';
//     labels.forEach(function(labelValue) {
//         addLabelToDisplayArea(labelValue, false);
//     });
// }

// function addLabelToDisplayArea(labelValue, isCheckedByDefault = false) {
//     const labelsListDiv = document.getElementById('labelsList');

//     var labelContainerDiv = document.createElement('div');
//     labelContainerDiv.className = 'form-check';

//     var checkbox = document.createElement('input');
//     checkbox.setAttribute('type', 'checkbox');
//     checkbox.className = 'form-check-input label-checkbox';
//     checkbox.value = labelValue;

//     if (isCheckedByDefault) {
//         checkbox.checked = true;
//     }

//     var labelElement = document.createElement('label');
//     labelElement.className = 'form-check-label';
//     labelElement.textContent = labelValue;

//     labelContainerDiv.appendChild(checkbox);
//     labelContainerDiv.appendChild(labelElement);

//     labelsListDiv.appendChild(labelContainerDiv);
// }

// $('#labelModal').on('show.bs.modal', function() {
//     fetchUserLabels();
// });

// document.getElementById('addLabel').addEventListener('click', function() {
//     const labelInput = document.getElementById('labelInput');
//     const labelValue = labelInput.value.trim();

//     // ラベルが空かどうかをチェック
//     if (!labelValue) {
//         alert("ラベル名を入力してください。");
//         return;
//     }

//     if (!labels.includes(labelValue)) {
//         labels.push(labelValue);
//         const shouldCheck = countCheckedCheckboxes() < MAX_ALLOWED_CHECKBOXES;
//         addLabelToDisplayArea(labelValue, shouldCheck);
//         labelInput.value = '';
//         toggleCheckboxDisabledState();
//     }
// });


// const saveLabelButton = document.getElementById('saveLabel');

// saveLabelButton.addEventListener('click', function() {
//     const selectedCheckboxes = document.querySelectorAll('#labelsList input[type="checkbox"]:checked');
//     labels = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);

//     const hiddenLabels = document.getElementById('hiddenLabels');
//     hiddenLabels.value = labels.join(',');
    
//     const selectedLabelsDiv = document.getElementById('selectedLabels');
//     selectedLabelsDiv.innerHTML = '';
//     labels.forEach(function(label) {
//         const labelDiv = document.createElement('div');
//         labelDiv.className = 'selected-label-item';

//         const labelSpan = document.createElement('span');
//         labelSpan.textContent = label;
//         labelDiv.appendChild(labelSpan);

//         const deleteButton = document.createElement('button');
//         deleteButton.className = 'delete-label-btn';
//         var deleteIcon = document.createElement('i');
//         deleteIcon.className = 'fa-solid fa-xmark';
//         deleteButton.appendChild(deleteIcon);

//         deleteButton.addEventListener('click', function() {
//             selectedLabelsDiv.removeChild(labelDiv);
//             labels = labels.filter(labelValue => labelValue !== label);
//             hiddenLabels.value = labels.join(',');
//         });

//         labelDiv.appendChild(deleteButton);
//         selectedLabelsDiv.appendChild(labelDiv);
//     });
//     $('#labelModal').modal('hide');

//     $('#labelModal').on('hidden.bs.modal', function() {
//         if ($('.modal-backdrop').length) {
//             $('.modal-backdrop').remove();
//         }
//     });    
// });

