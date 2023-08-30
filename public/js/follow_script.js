document.addEventListener('DOMContentLoaded', (event) => {
    var userId = window.userId;

    // クローズボタンのイベントハンドラを外部に定義
    function handleCloseButtonClick(modal) {
        modal.hide();
    }    

    // Function to setup modal and close button
    function setupModalAndCloseButton(modalId) {
        console.log("Setting up modal with ID:", modalId);
        var modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();

        var closeBtn = document.querySelector('#' + modalId + ' .btn-close');
        if (closeBtn) {
            // 既存のイベントリスナーを削除
            closeBtn.removeEventListener('click', handleCloseButtonClick);
            // 新たなイベントリスナーを追加
            closeBtn.addEventListener('click', function() {
                handleCloseButtonClick(modal);
            });
        }
    }

    function createUserLink(element, user) {
        var userLink = document.createElement('a');
        userLink.href = '/profile/' + user.id;
        userLink.style.textDecoration = 'none'; // 下線を削除
        userLink.className = 'd-flex align-items-center'; // 横並びに
    
        // 画像またはアイコン用のdiv
        var imageDiv = document.createElement('div');
        var profileImage = user.profile_image;
        if (profileImage === null || profileImage === '') {
            var iconDiv = document.createElement('i');
            iconDiv.className = 'fas fa-user small-profile-icon'; 
            imageDiv.appendChild(iconDiv);
        } else {
            var img = document.createElement('img');
            img.className = 'profile-image small-profile-image';
            img.src = window.followProfileImagesUrl + profileImage;
            imageDiv.appendChild(img);
        }
        userLink.appendChild(imageDiv);

        // ユーザー名と名前用のdiv
        var infoDiv = document.createElement('div');
        infoDiv.style.marginLeft = '10px'; // 画像との間にスペースを追加
    
        var usernameP = document.createElement('p');
        usernameP.textContent = user.user_name;
        usernameP.style.color = 'black'; // 色を黒に
        infoDiv.appendChild(usernameP);

        var nameP = document.createElement('p');
        nameP.textContent = user.name;
        nameP.style.color = 'black'; // 色を黒に
        infoDiv.appendChild(nameP);
    
        userLink.appendChild(infoDiv);
    
        element.appendChild(userLink);
    }    

    // フォロワーの表示
    var showFollowersButton = document.getElementById('show-followers');
    if (showFollowersButton) {
        showFollowersButton.addEventListener('click', function(e) {
            console.log("showFollowersButton clicked");
            e.preventDefault();

            var url = '/user/' + userId + '/followers';

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var followers = JSON.parse(xhr.responseText);
                    var followersListDiv = document.getElementById('followers-list');

                    if (followersListDiv) {
                        followersListDiv.innerHTML = '';

                        if (followers.length === 0) {
                            followersListDiv.textContent = 'フォロワーがいません';
                        } else {
                            followers.forEach(function(follower) {
                                var followerDiv = document.createElement('div');
                                followerDiv.className = 'd-flex align-items-center justify-content-between';
                                createUserLink(followerDiv, follower);

                                console.log("Processing follower:", follower.user_name);
                                console.log("Is followed by current user:", follower.is_followed_by_current_user);
                                
                                if (follower.id !== window.loggedInUserId) {
                                    if (follower.is_followed_by_current_user) {
                                        var unfollowButton = document.createElement('button');
                                        unfollowButton.className = 'btn btn-danger';
                                        unfollowButton.textContent = 'フォロー中';
                                        unfollowButton.addEventListener('click', function() {
                                            unfollow(follower.id);
                                        });
                                        followerDiv.appendChild(unfollowButton);
                                    } else {
                                        var followButton = document.createElement('button');
                                        followButton.className = 'btn btn-primary';
                                        followButton.textContent = 'フォロー解除';
                                        followButton.addEventListener('click', function() {
                                            follow(follower.id);
                                        });
                                        followerDiv.appendChild(followButton);
                                    }
                                }
                                followersListDiv.appendChild(followerDiv);
                            });                            
                        }

                        var modalElem = document.getElementById('followersModal' + userId);
                        if (!modalElem) {
                            console.error('Modal element not found for ID: ', 'followersModal' + userId);
                            return;
                        }

                        setupModalAndCloseButton('followersModal' + userId);
                    }
                }
            };

            xhr.open('GET', url, true);
            xhr.send();
        });
    } else {
        console.error("'show-followers' element not found!");
    }

    // フォローしている人々の表示
    var showFollowingButton = document.getElementById('show-following');
    if (showFollowingButton) {
        showFollowingButton.addEventListener('click', function(e) {
            console.log("showFollowingButton clicked");
            e.preventDefault();

            var url = '/user/' + userId + '/following';
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var following = JSON.parse(xhr.responseText);
                    var followingListDiv = document.getElementById('following-list');

                    if (followingListDiv) {
                        followingListDiv.innerHTML = '';

                        if (following.length === 0) {
                            followingListDiv.textContent = 'フォローしていません';
                        } else {
                            following.forEach(function(follow) {
                                var followDiv = document.createElement('div');
                                followDiv.className = 'd-flex align-items-center justify-content-between';

                                createUserLink(followDiv, follow);

                                // フォロー解除ボタンの追加
                                if (follow.id !== window.loggedInUserId) {
                                    var unfollowButton = document.createElement('button');
                                    unfollowButton.className = 'btn btn-danger';
                                    unfollowButton.textContent = 'フォロー解除';
                                    unfollowButton.addEventListener('click', function() {
                                        unfollow(follow.id);
                                    });
                                    followDiv.appendChild(unfollowButton);
                                }
                                followingListDiv.appendChild(followDiv);
                            });
                        }

                        var modalElem = document.getElementById('followingModal' + userId);
                        if (!modalElem) {
                            console.error('Modal element not found for ID: ', 'followingModal' + userId);
                            return;
                        }

                        setupModalAndCloseButton('followingModal' + userId);
                    }
                }
            };

            xhr.open('GET', url, true);
            xhr.send();
        });
    } else {
        console.error("'show-following' element not found!");
    }

    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        });
    });
});

function follow(userId) {
    console.log("follow function called with userId:", userId);
    var url = '/follow/' + userId;
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            alert('フォローしました');
            location.reload();
        }
    };

    xhr.open('POST', url, true);

    // CSRF トークンの追加
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    xhr.setRequestHeader('X-CSRF-TOKEN', token);

    xhr.send();
}


// フォロー解除機能
function unfollow(userId) {
    console.log("unfollow function called with userId:", userId);
    if (window.confirm('フォローを解除しますか？')) {
        var url = '/unfollow/' + userId;
        console.log("Generated request URL:", url);
        var xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                console.log(response.message, "for user:", response.targetUserId); // ここでサーバーからのレスポンスをコンソールに表示

                alert('フォローを解除しました');
                location.reload();
            }
        };

        xhr.open('DELETE', url, true);

        // CSRF トークンの追加
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        xhr.setRequestHeader('X-CSRF-TOKEN', token);

        xhr.send();
    }
}