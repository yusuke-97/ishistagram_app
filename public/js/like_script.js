function onLikeButtonClicked(postId) {
    // いいねを行うためのフォームを取得
    var form = document.getElementById('like-form-' + postId);
    
    // フォームのアクション(URL)を取得
    var action = form.action;

    // CSRFトークンを取得 (Laravelのセキュリティ対策のため)
    var csrfToken = form.querySelector('input[name="_token"]').value;
    
    // ポストIDを取得
    var postInput = form.querySelector('input[name="post_id"]').value;

    // フェッチAPIを使用して非同期のHTTPリクエストを実行
    fetch(action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            post_id: postInput
        })
    })
    .then(response => response.json()) // レスポンスをJSON形式で解析
    .then(data => {
        // いいねのアイコンとカウントの要素を取得
        var likeIcon = document.getElementById('like-icon-' + postId);
        var likeCount = document.getElementById('like-count-' + postId);
        
        // サーバーからのレスポンスに応じて、アイコンとカウントを更新
        if (data.status === 'liked') {
            likeIcon.style.color = 'red';
            likeIcon.classList.remove('far');
            likeIcon.classList.add('fas');
            likeCount.textContent = Number(likeCount.textContent) + 1;
        } else if (data.status === 'unliked') {
            likeIcon.style.color = 'black';
            likeIcon.classList.remove('fas');
            likeIcon.classList.add('far');
            likeCount.textContent = Number(likeCount.textContent) - 1;
        }
    });    
}
