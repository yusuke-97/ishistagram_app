document.addEventListener('DOMContentLoaded', (event) => {
    // グローバル変数の初期化
    var userId = window.userId;
    window.loggedInUserId = parseInt(window.loggedInUserId);

    // モーダルを閉じる関数
    function handleCloseButtonClick(modal) {
        modal.hide();
    }    

    // モーダルとその閉じるボタンの設定関数
    function setupModalAndCloseButton(modalId) {
        var modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();

        var closeBtn = document.querySelector('#' + modalId + ' .btn-close');
        if (closeBtn) {
            closeBtn.removeEventListener('click', handleCloseButtonClick);
            closeBtn.addEventListener('click', function() {
                handleCloseButtonClick(modal);
            });
        }
    }

    // ユーザーへのリンクを作成する関数
    function createUserLink(element, user) {
        var userLink = document.createElement('a');
        userLink.href = '/profile/' + user.id;
        userLink.style.textDecoration = 'none';
        userLink.className = 'd-flex align-items-center';
    
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
        infoDiv.style.marginLeft = '10px';
    
        var usernameP = document.createElement('p');
        usernameP.textContent = user.user_name;
        usernameP.style.color = 'black';
        infoDiv.appendChild(usernameP);

        var nameP = document.createElement('p');
        nameP.textContent = user.name;
        nameP.style.color = 'black';
        infoDiv.appendChild(nameP);
    
        userLink.appendChild(infoDiv);
    
        element.appendChild(userLink);
    }    

    // フォロワーを表示するイベント
    var showFollowersButton = document.getElementById('show-followers');
    if (showFollowersButton) {
        showFollowersButton.addEventListener('click', function(e) {
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
                                followerDiv.style.marginBottom = '16px';
                                createUserLink(followerDiv, follower);
                                
                                if (follower.id !== window.loggedInUserId) {
                                    if (follower.is_followed_by_current_user) {
                                        var unfollowButton = document.createElement('button');
                                        unfollowButton.className = 'btn btn-danger btn-following';
                                        unfollowButton.textContent = 'フォロー中';
                                        unfollowButton.addEventListener('click', function() {
                                            unfollowUser(follower.id);
                                        });
                                        followerDiv.appendChild(unfollowButton);
                                    } else {
                                        var followButton = document.createElement('button');
                                        followButton.className = 'btn btn-primary btn-follow';
                                        followButton.textContent = 'フォローする';
                                        followButton.addEventListener('click', function() {
                                            followUser(follower.id);
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

    // フォローしているユーザーを表示するイベント
    var showFollowingButton = document.getElementById('show-following');
    if (showFollowingButton) {
        showFollowingButton.addEventListener('click', function(e) {
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
                                followDiv.style.marginBottom = '16px';

                                createUserLink(followDiv, follow);

                                // フォロー解除ボタンの追加
                                if (follow.id !== window.loggedInUserId) {
                                    if (follow.is_followed_by_current_user) { 
                                        var unfollowButton = document.createElement('button');
                                        unfollowButton.className = 'btn btn-danger btn-following';
                                        unfollowButton.textContent = 'フォロー中';
                                        unfollowButton.addEventListener('click', function() {
                                            unfollowUser(follow.id);
                                        });
                                        followDiv.appendChild(unfollowButton);
                                    } else {
                                        var followButton = document.createElement('button');
                                        followButton.className = 'btn btn-primary btn-follow';
                                        followButton.textContent = 'フォローする';
                                        followButton.addEventListener('click', function() {
                                            followUser(follow.id);
                                        });
                                        followDiv.appendChild(followButton);
                                    }
                                }
                                followingListDiv.appendChild(followDiv);
                            });
                        }

                        var modalElem = document.getElementById('followingModal' + userId);
                        if (!modalElem) {
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

    // モーダルが非表示になったときのイベント
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

// ユーザーをフォローする関数
function followUser(userId) {
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


// ユーザーのフォローを解除する関数
function unfollowUser(userId) {
    if (window.confirm('フォローを解除しますか？')) {
        var url = '/unfollow/' + userId;
        var xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);

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