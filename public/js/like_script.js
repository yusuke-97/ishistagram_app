function onLikeButtonClicked(postId) {
    var form = document.getElementById('like-form-' + postId);
    var action = form.action;
    var csrfToken = form.querySelector('input[name="_token"]').value;
    var postInput = form.querySelector('input[name="post_id"]').value;

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
    .then(response => response.json())
    .then(data => {
        var likeIcon = document.getElementById('like-icon-' + postId);
        var likeCount = document.getElementById('like-count-' + postId);
        
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
