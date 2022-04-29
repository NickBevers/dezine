document.querySelector(".post__comment__form__btn").addEventListener("click", function (e) {
    e.preventDefault();

    let postId = this.dataset.postid;
    let inputField = document.querySelector(".post__comment__form__input");
    let comment = inputField.value;

    let formData = new FormData();

    formData.append('text', comment);
    formData.append('postId', postId);

    fetch('ajax/savecomment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            let commentList = document.querySelector(".post__comment__list");

            let commentWrapper = document.createElement('li');
            let commentLeftColumn = document.createElement('div');
            commentLeftColumn.classList.add('comment--left');

            let pfpLink = document.createElement('a');
            pfpLink.href = "profile.php?id=" + result.userId;
            let pfpImg = document.createElement('img');
            pfpImg.src = this.dataset.pfplink;

            let commentRightColumn = document.createElement('div');
            commentRightColumn.classList.add('comment--right');

            let usernameLink = document.createElement('a');
            usernameLink.href = "profile.php?id=" + result.userId;
            usernameLink.innerHTML = "Bailey";
            let commentText = document.createElement('p');
            commentText.innerHTML = result.text;

            pfpLink.appendChild(pfpImg);
            commentLeftColumn.appendChild(pfpLink);

            commentRightColumn.appendChild(usernameLink);
            commentRightColumn.appendChild(commentText);

            commentWrapper.appendChild(commentLeftColumn);
            commentWrapper.appendChild(commentRightColumn);
        
            commentList.appendChild(commentWrapper);

            inputField.value = "";
        })
        .catch(error => {
            console.error('Error:', error);
        });
});