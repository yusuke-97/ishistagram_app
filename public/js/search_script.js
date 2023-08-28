$(document).ready(function() {
    // 初期状態で非表示にする
    $("#autocomplete-results").hide();

    $("#query-input").keyup(function() {
        let query = $(this).val();
        let resultsDiv = $("#autocomplete-results");
        let url = "";

        if (query.length < 2) {
            resultsDiv.html("");
            resultsDiv.hide();
            return;
        }

        if (query.startsWith('#')) {
            url = `/autocomplete/tags?query=${query.substring(1)}`;
        } else {
            url = `/autocomplete/users?query=${query}`;
        }

        $.ajax({
            url: url,
            method: 'GET',
            success: function(results) {
                resultsDiv.html("");

                // 結果が空の場合、結果をクリアして非表示にする
                if (results.length === 0) {
                    resultsDiv.hide();
                    return;
                }

                results.forEach(result => {
                    let resultItem = $("<div class='result-item'></div>");
                    if (query.startsWith('#')) {
                        resultItem.text('#' + result.name);
                        resultItem.click(function() {
                            window.location.href = `/search/tags?query=${result.name}`;
                        });
                    } else {
                        let profileImageDiv = $("<div class='profile-image-container'></div>");
                        if (result.profile_image) {
                            profileImageDiv.append($("<img class='autocomplete-profile-image' src='/ishistagram/storage/app/public/profile_images/" + result.profile_image + "' alt='profile image'>"));
                        } else {
                            profileImageDiv.append($("<i class='fas fa-user autocomplete-profile-icon'></i>"));
                        }
                        let userInfoContainer = $("<div class='user-info-container'></div>");
                        let userName = $("<span class='user-name'></span>").text(result.user_name);
                        let name = $("<span class='user-fullname'></span>").text(result.name);
                        userInfoContainer.append(userName, name);
                        resultItem.append(profileImageDiv, userInfoContainer);
                        resultItem.click(function() {
                            window.location.href = `/profile/${result.id}`;
                        });
                    }
                    resultsDiv.append(resultItem);
                });

                // 結果が存在する場合、結果を表示する
                resultsDiv.show();
            },
            error: function(error) {
                console.error('Ajax error:', error);
            }
        });
    });
});