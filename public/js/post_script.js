window.onload = function() {

    // 選択された画像を保持する配列
    let selectedImages = [];

    // 画像選択時の動作を定義
    document.getElementById("image").onchange = function (e) {
        // 画像の選択上限
        const MAX_ALLOWED_IMAGES = 5;

        // 選択されたファイルを配列形式に変換
        let files = Array.from(e.target.files);
        
        // 選択された画像とこれまでの画像を結合
        selectedImages = selectedImages.concat(files);

        // 選択された画像の数が上限を超えた場合の処理
        if (selectedImages.length > MAX_ALLOWED_IMAGES) {
            alert('最大5枚の画像を選択できます。');
            selectedImages = selectedImages.slice(0, MAX_ALLOWED_IMAGES);
        }

        // 以前のプレビューをクリア
        document.getElementById("imagePreview").innerHTML = "";

        // 各画像のプレビューを表示
        for (let i = 0; i < selectedImages.length; i++) {
            let file = selectedImages[i];
            let reader = new FileReader();

            // 画像のプレビューを表示するための要素を作成
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

            // 画像を削除するためのクローズボタンの動作を定義
            closeButton.addEventListener("click", function(event) {
                event.stopPropagation();
                imageDiv.remove(); // プレビューを削除
                selectedImages.splice(i, 1); // 画像リストから該当の画像を削除
            });

            // 要素をDOMに追加
            imageContainerDiv.appendChild(closeButton);
            imageContainerDiv.appendChild(imageElement);
            imageDiv.appendChild(imageContainerDiv);
            document.getElementById("imagePreview").appendChild(imageDiv);

            // 画像データを読み込み終わったら、プレビューに表示
            reader.onloadend = function () {
                imageElement.setAttribute("src", reader.result);
            }

            if (file) {
                reader.readAsDataURL(file); // ファイルをデータURLとして読み込む
            }
        }
    };

    // カスタムボタンをクリックしたときの動作を定義
    // 画像選択のインプット要素をクリックすることで、ファイル選択ダイアログを表示
    document.getElementById('customButton').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('image').click();
    });
};