let like = document.querySelectorAll(".like");
if(like !== null){
    for(let i = 0; i < like.length; i++){
        like[i].addEventListener("click", (e) => {
            // console.log(element.parentElement);
            let postId = e.target.parentElement.dataset.id;
            // console.log(postId + " pid");
            let parent = e.target.parentElement;
            let data = new FormData();
            data.append("postId", postId);   
            data.append("liked", 1);          
            
            fetch("ajax/save_like.php", {
                method: "POST",
                body: data,
            }).then(response => response.json())
            .then(res => {
                // console.log("Success: ", res);
                if(parent.querySelector(".likes_count")){
                    parent.nextElementSibling.querySelector(".likes_count").innerHTML = res.data + " people like this";
                    parent.querySelector(".likes_count").innerHTML = res.data + " people like this";
                }
                parent.nextElementSibling.classList.remove("hidden");                    
                parent.classList.add("hidden");
            }).catch((error) =>{
                console.error("Error: ", error);
            });        
        });
    }
}
    
let dislike = document.querySelectorAll(".liked");
if( dislike !== null){
    for(let i = 0; i < dislike.length; i++){
        dislike[i].addEventListener("click", (e) =>{
            // console.log(element.parentElement);
            let postId = e.target.parentElement.dataset.id;
            // console.log(postId + " pid");
            let parent = e.target.parentElement;
            let data = new FormData();
            data.append("postId", postId);   
            data.append("unliked", 1);          
            
            fetch("ajax/save_dislike.php", {
                method: "POST",
                body: data,
            }).then(response => response.json())
            .then(res => {
                // console.log("Success: ", res);
                if(parent.querySelector(".likes_count")){
                    parent.querySelector(".likes_count").innerHTML = res.data + " people like this";
                    parent.previousElementSibling.querySelector(".likes_count").innerHTML = res.data + " people like this";
                }
                // console.log(parent.previousElementSibling); 
                parent.previousElementSibling.classList.remove("hidden");                   
                parent.classList.add("hidden");
            }).catch((error) =>{
                console.error("Error: ", error);
            });        
        });
    }
}