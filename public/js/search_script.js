$(document).ready(function() {
    // 初期状態でオートコンプリートの結果を非表示にする
    $("#autocomplete-results").hide();

    // ユーザーがキーを入力するたびに発火するイベント
    $("#query-input").keyup(function() {
        let query = $(this).val();
        let resultsDiv = $("#autocomplete-results");
        let url = "";

        // クエリが2文字未満の場合、結果を非表示にして終了
        if (query.length < 2) {
            resultsDiv.html("");
            resultsDiv.hide();
            return;
        }

        // クエリがハッシュタグで始まる場合はタグのオートコンプリートを行う、そうでない場合はユーザーのオートコンプリートを行う
        if (query.startsWith('#')) {
            url = `/autocomplete/tags?query=${query.substring(1)}`;
        } else {
            url = `/autocomplete/users?query=${query}`;
        }

        // オートコンプリートの結果を取得するAjaxリクエスト
        $.ajax({
            url: url,
            method: 'GET',
            success: function(results) {
                resultsDiv.html("");

                // 結果が空の場合、結果を非表示にして終了
                if (results.length === 0) {
                    resultsDiv.hide();
                    return;
                }

                // オートコンプリートの結果を表示する領域に結果を追加する
                results.forEach(result => {
                    let resultItem = $("<div class='result-item'></div>");
                    
                    // ハッシュタグのオートコンプリートの場合
                    if (query.startsWith('#')) {
                        resultItem.text('#' + result.name);
                        resultItem.click(function() {
                            window.location.href = `/search/tags?query=${result.name}`;
                        });
                    } else {
                        // ユーザーのオートコンプリートの場合
                        let profileImageDiv = $("<div class='profile-image-container'></div>");
                        
                        // プロフィール画像が存在する場合は画像を、そうでない場合はデフォルトのアイコンを追加
                        if (result.profile_image) {
                            profileImageDiv.append($("<img class='autocomplete-profile-image' src='" + window.profileImagesUrl + result.profile_image + "' alt='profile image'>"));
                        } else {
                            profileImageDiv.append($("<i class='fas fa-user autocomplete-profile-icon'></i>"));
                        }
                        let userInfoContainer = $("<div class='user-info-container'></div>");
                        let userName = $("<span class='user-name'></span>").text(result.user_name);
                        let name = $("<span class='user-fullname'></span>").text(result.name);
                        userInfoContainer.append(userName, name);
                        resultItem.append(profileImageDiv, userInfoContainer);
                        
                        // ユーザーの結果項目をクリックしたときの動作
                        resultItem.click(function() {
                            window.location.href = `/profile/${result.id}`;
                        });
                    }
                    
                    resultsDiv.append(resultItem);
                });

                // オートコンプリートの結果を表示
                resultsDiv.show();
            },
            error: function(error) {
                console.error('Ajax error:', error);
            }
        });
    });
});