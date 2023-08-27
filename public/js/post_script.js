window.onload = function() {

    let selectedImages = []; // この配列は、選択された画像を保持します

    document.getElementById("image").onchange = function (e) {
        const MAX_ALLOWED_IMAGES = 5;

        let files = Array.from(e.target.files);
        
        // 既に選択された画像と新しく選択された画像を結合
        selectedImages = selectedImages.concat(files);

        // 画像の数が上限を超えている場合、エラーメッセージを表示
        if (selectedImages.length > MAX_ALLOWED_IMAGES) {
            alert('最大5枚の画像を選択できます。');
            selectedImages = selectedImages.slice(0, MAX_ALLOWED_IMAGES);
        }

        // プレビューをクリアする
        document.getElementById("imagePreview").innerHTML = "";

        // 画像をプレビューする
        for (let i = 0; i < selectedImages.length; i++) {
            let file = selectedImages[i];
            let reader = new FileReader();

            let imageDiv = document.createElement("div");
            imageDiv.classList.add("col-4", "image-div");

            let imageContainerDiv = document.createElement("div");
            imageContainerDiv.classList.add("image-container", "p-2");

            let imageElement = document.createElement("img");
            imageElement.classList.add("preview-image", "img-fluid");

            let closeButton = document.createElement("span");
            closeButton.innerHTML = "&times;";
            closeButton.classList.add("close-button");
            closeButton.style.zIndex = 1000;

            // クローズボタンをクリックした時の処理を追加
            closeButton.addEventListener("click", function(event) {
                event.stopPropagation();
                imageDiv.remove();
                selectedImages.splice(i, 1); // 選択された画像リストから画像を削除
            });

            imageContainerDiv.appendChild(closeButton);
            imageContainerDiv.appendChild(imageElement);
            imageDiv.appendChild(imageContainerDiv);
            document.getElementById("imagePreview").appendChild(imageDiv);

            reader.onloadend = function () {
                imageElement.setAttribute("src", reader.result);
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    };

    document.getElementById('customButton').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('image').click();
    });
};