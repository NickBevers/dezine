document.querySelector(".post__comment__form__input").addEventListener("keypress", function(event) {
    if (event.keyCode === 13) {
        event.preventDefault();
        document.querySelector(".post__comment__form__btn").click();
    }
});
document.querySelector(".post__comment__form__btn").addEventListener("click", function (e) {
    e.preventDefault();

    let postId = this.dataset.postid;
    let userId = this.dataset.uid;
    let inputField = document.querySelector(".post__comment__form__input");
    let comment = inputField.value;

    let formData = new FormData();

    formData.append('text', comment);
    formData.append('postId', postId);
    formData.append('userId', userId);

    fetch('ajax/savecomment.php', {
            method: 'POST',
            body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'failure') {
            document.querySelector(".post__comment__form__input").style.borderColor = 'red';
            document.querySelector(".post__comment__form__input").style.borderWidth = '2px';
            document.querySelector(".post__comment__form__input").setAttribute('placeholder', result.message);
            return;
        }
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
        usernameLink.innerHTML = this.dataset.username;
        let commentText = document.createElement('p');
        commentText.innerHTML = result.text;

        pfpLink.appendChild(pfpImg);
        commentLeftColumn.appendChild(pfpLink);

        commentRightColumn.appendChild(usernameLink);
        commentRightColumn.appendChild(commentText);

        commentWrapper.appendChild(commentLeftColumn);
        commentWrapper.appendChild(commentRightColumn);
    
        commentList.prepend(commentWrapper);

        document.querySelector(".post__comment__form__input").style.borderColor = 'black';
        inputField.value = "";
        
    }).catch(error => {
        console.error('Error:', error);
    });
});