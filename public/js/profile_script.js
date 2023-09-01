// 最後の操作を特定するためのトークン
let lastToken = null;
let saveButton;

// プロフィール画像のカスタム選択ボタンのクリック時の動作
document.getElementById('customProfile').addEventListener('click', function() {
    document.getElementById('profile_image').click();
});

// プロフィール画像削除ボタンのクリック時の動作
document.getElementById('deleteProfileImage').addEventListener('click', function(e) {
    e.preventDefault();

    lastToken = Date.now();
    let profileImageDisplay = document.getElementById('currentProfileImage');
    let existingProfileIcon = document.querySelector('.fas.fa-user.fa-2x.edit-profile-icon');

    // 画像が表示されている場合、それを削除し、代わりにデフォルトのアイコンを表示
    if (profileImageDisplay) {
        profileImageDisplay.remove();

        if (!existingProfileIcon) {
            let profileIcon = document.createElement('i');
            profileIcon.className = "fas fa-user fa-2x edit-profile-icon";
            let container = document.querySelector('.d-flex.align-items-center.mb-2');
            container.insertBefore(profileIcon, document.getElementById('customProfile'));
        }
    }

    document.getElementById('delete_image_flag').value = '1';
    lastToken = 'delete';
});

// ドキュメント読み込み完了時の処理
document.addEventListener("DOMContentLoaded", function() {
    let profileImageInput = document.getElementById('profile_image');
    saveButton = document.querySelector(".btn.share-btn");

    // 画像選択時の処理
    profileImageInput.addEventListener('change', async function(event) {
        let file = event.target.files[0];
        if (file) {
            // 新しいトークンを生成
            lastToken = Date.now();
            saveButton.disabled = true;

            try {
                let result = await readImageAsDataURL(file);
                handleImageDisplay(result, lastToken);
            } catch (error) {
                console.error("Error reading the file:", error);
            }
        }

        lastToken = 'select';
    });

    // 画像選択後、削除フラグをリセット
    document.getElementById('profile_image').addEventListener('change', function() {
        document.getElementById('delete_image_flag').value = '0';
    });    
});

// 画像ファイルをDataURLとして読み込む関数
function readImageAsDataURL(file) {
    return new Promise((resolve, reject) => {
        let reader = new FileReader();
        reader.onload = function(e) {
            resolve(e.target.result);
        };
        reader.onerror = function(error) {
            reject(error);
        };
        reader.readAsDataURL(file);
    });
}

// 画像を表示、または更新する関数
function handleImageDisplay(dataURL, token) {
    if (token === lastToken) {
        let profileImageDisplay = document.getElementById('currentProfileImage');
        let profileIcon = document.querySelector('.fas.fa-user.fa-2x.edit-profile-icon');

        // 既存のデフォルトアイコンを削除
        if (profileIcon) {
            profileIcon.remove();
        }

        // 既存の画像を更新、または新しい画像を追加
        if (profileImageDisplay) {
            profileImageDisplay.src = dataURL;
        } else {
            let newImage = document.createElement('img');
            newImage.id = 'currentProfileImage';
            newImage.src = dataURL;
            newImage.alt = "プロフィール画像";
            newImage.width = 100;
            newImage.className = "edit-profile-image";

            let container = document.querySelector('.d-flex.align-items-center.mb-2');
            container.insertBefore(newImage, document.getElementById('profile_image'));
        }
    }
    saveButton.disabled = false;
}

document.querySelector('form').addEventListener('submit', async function(event) {
    event.preventDefault();
    let formData = new FormData(event.target);

    // 現在のプロフィール画像をFormDataに追加
    let currentImageElement = document.getElementById('currentProfileImage');
    if (currentImageElement) {
        let response = await fetch(currentImageElement.src);
        let blob = await response.blob();
        formData.append('profile_image', blob, 'profile_image.png');
    }

    // フォームデータをサーバーに送信
    fetch(event.target.action, {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
});