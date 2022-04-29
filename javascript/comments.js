document.querySelector(".post___comments__form__btn").addEventListener("click", function () {

    let postId = this.dataset.postid;
    let comment = document.querySelector(".post___comments__form__input").value;

    let formData = new FormData();

    formData.append('text', comment);
    formData.append('postId', postId);

    fetch('ajax/savecomment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            let newComment = document.createElement('li');
            newComment.innerHTML = result.commment;
            console.log(newComment);
            document.querySelector(".post__comments__list").appendChild(newComment);
        })
        .catch(error => {
            console.error('Error:', error);
        });
});