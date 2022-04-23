let follow_button = document.querySelector(".follow");
let unfollow_button = document.querySelector(".unfollow");
let profile_id = follow_button.dataset.profile_id;
let user_id = unfollow_button.dataset.user_id;

follow_button.addEventListener("click", (e) => {
    let data = new FormData();
    data.append("follower_id", profile_id);
    data.append("user_id", user_id);

    fetch("ajax/follow.php", {
        method: "POST",
        body: data,
    })
    .then(response => response.json())
    .then(res => {
        if(res.status === "success"){
            follow_button.style.display = "none";
            unfollow_button.style.display = "block";
        } else{
            console.error(`Something has gone wrong: ${res.message}`)
        }
    })
    .catch((error) =>{
        console.error("Error: ", error);
    });

    e.preventDefault();
})

unfollow_button.addEventListener("click", (e) => {
    let data = new FormData();
    data.append("follower_id", profile_id);
    data.append("user_id", user_id);

    fetch("ajax/unfollow.php", {
        method: "POST",
        body: data,
    })
    .then(response => response.json())
    .then(res => {
        if(res.status === "success"){
            unfollow_button.style.display = "none";
            follow_button.style.display = "block";
        } else{
            console.error(`Something has gone wrong: ${res.message}`)
        }
    })
    .catch((error) =>{
        console.error("Error: ", error);
    });

    e.preventDefault();
})