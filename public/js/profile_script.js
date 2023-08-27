let lastToken = null;
let saveButton;

document.getElementById('customProfile').addEventListener('click', function() {
    document.getElementById('profile_image').click();
});

document.getElementById('deleteProfileImage').addEventListener('click', function(e) {
    e.preventDefault();

    lastToken = Date.now();

    let profileImageDisplay = document.getElementById('currentProfileImage');
    let existingProfileIcon = document.querySelector('.fas.fa-user.fa-2x.edit-profile-icon'); // 変更点

    if (profileImageDisplay) {
        profileImageDisplay.remove();

        if (!existingProfileIcon) {
            let profileIcon = document.createElement('i');
            profileIcon.className = "fas fa-user fa-2x edit-profile-icon";  // 変更点
            let container = document.querySelector('.d-flex.align-items-center.mb-2');
            container.insertBefore(profileIcon, document.getElementById('customProfile'));
        }
    }

    document.getElementById('delete_image_flag').value = '1';
    lastToken = 'delete';
});

console.log("script loaded");

document.addEventListener("DOMContentLoaded", function() {
    let profileImageInput = document.getElementById('profile_image');
    saveButton = document.querySelector(".btn.share-btn");  // 値を設定

    profileImageInput.addEventListener('change', async function(event) {
        let file = event.target.files[0];
        if (file) {
            // 新しいトークンを生成
            lastToken = Date.now();

            saveButton.disabled = true;  // 追加

            try {
                let result = await readImageAsDataURL(file);
                handleImageDisplay(result, lastToken);
            } catch (error) {
                console.error("Error reading the file:", error);
            }
        }
        // 画像選択操作のトークンを生成
        lastToken = 'select';
    });
    document.getElementById('profile_image').addEventListener('change', function() {
        document.getElementById('delete_image_flag').value = '0';  // 追加: フラグをリセット
    });    
});

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

function handleImageDisplay(dataURL, token) {
    if (token === lastToken) {
        let profileImageDisplay = document.getElementById('currentProfileImage');

        let profileIcon = document.querySelector('.fas.fa-user.fa-2x.edit-profile-icon');  // 変更点
        if (profileIcon) {
            profileIcon.remove();
        }

        if (profileImageDisplay) {
            profileImageDisplay.src = dataURL;
        } else {
            let newImage = document.createElement('img');
            newImage.id = 'currentProfileImage';
            newImage.src = dataURL;
            newImage.alt = "プロフィール画像";
            newImage.width = 100;
            newImage.className = "edit-profile-image";  // 変更点

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
        // 必要に応じて追加の処理を実行
    })
    .catch(error => {
        console.error('Error:', error);
    });
});