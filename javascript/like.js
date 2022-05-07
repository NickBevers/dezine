let like = document.querySelectorAll(".like");    
let dislike = document.querySelectorAll(".liked");

if(like !== null){
    for(let i = 0; i < like.length; i++){
        like[i].addEventListener("click", (e) => {
          addRemoveLike(e);  
        });
    }
}

if(dislike !== null){
    for(let i = 0; i < like.length; i++){
        dislike[i].addEventListener("click", (e) => {
          addRemoveLike(e);  
        });
    }
}

function addRemoveLike(e){
    // console.log(e.target.parentElement);
    let postId = e.target.parentElement.dataset.id;
    let parent = e.target.parentElement;
    let data = new FormData();
    data.append("postId", postId);         
    
    fetch("ajax/save_like.php", {
        method: "POST",
        body: data,
    }).then(response => response.json())
    .then(res => {
        // console.log("Success: ", res);
        if(parent.querySelector(".likes_count")){
            if(res.data === 0){   
                if(parent.classList.contains("like")){
                    parent.nextElementSibling.querySelector(".likes_count").innerHTML = "No one likes this yet";
                }   
                if(parent.classList.contains("liked")){
                    parent.previousElementSibling.querySelector(".likes_count").innerHTML = res.data + " No one likes this yet";
                }                  
                parent.querySelector(".likes_count").innerHTML = "No one likes this yet";
            }
            if(res.data === 1){
                if(parent.classList.contains("like")){
                    parent.nextElementSibling.querySelector(".likes_count").innerHTML = res.data + " user likes this";
                }
                if(parent.classList.contains("liked")){
                    parent.previousElementSibling.querySelector(".likes_count").innerHTML = res.data + " user like this";
                }
                parent.querySelector(".likes_count").innerHTML = res.data + " user likes this";
            }
            if(res.data > 1){
                if(parent.classList.contains("like")){
                    parent.nextElementSibling.querySelector(".likes_count").innerHTML = res.data + " users like this";
                }
                if(parent.classList.contains("liked")){
                    parent.previousElementSibling.querySelector(".likes_count").innerHTML = res.data + " users like this";
                }
                parent.querySelector(".likes_count").innerHTML = res.data + " users like this";
            }
        }
        if(parent.classList.contains("like")){
            parent.nextElementSibling.classList.remove("hidden");
            parent.classList.add("hidden");
        }
        else if(parent.classList.contains("liked")){
            parent.previousElementSibling.classList.remove("hidden"); 
            parent.classList.add("hidden");
        }                          
    }).catch((error) =>{
        console.error("Error: ", error);
    });        
}