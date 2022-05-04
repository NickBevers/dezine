let hearts = document.querySelectorAll(".hearts");
if(hearts !== null){
    for(let i = 0; i < hearts.length; i++){
        hearts[i].addEventListener("click", (e) => {
            // console.log(e);
            let postId = e.target.dataset.id;
            // console.log(postId + " pid");
            let sibling = e.target;
            let data = new FormData();
            data.append("postId", postId);   
            data.append("addToShowcase", 1);          
            
            fetch("ajax/addToShowcase.php", {
                method: "POST",
                body: data,
            }).then(response => response.json())
            .then(res => {
                // console.log("Success: ", res);
                // console.log(sibling.nextElementSibling);
                sibling.nextElementSibling.classList.remove("hidden");                    
                sibling.classList.add("hidden");
            }).catch((error) =>{
                console.error("Error: ", error);
            });        
        });
    }
}
    
let heartsfull = document.querySelectorAll(".heartsfull");
if(heartsfull !== null){
    for(let i = 0; i < heartsfull.length; i++){
        heartsfull[i].addEventListener("click", (e) =>{
            let img = e.target.parentElement;
            let post = img.parentElement;
            // console.log(post);
            let postId = e.target.dataset.id;
            // console.log(postId + " pid");
            let sibling = e.target;
            let data = new FormData();
            data.append("postId", postId);   
            data.append("removeFromShowcase", 1);          
            
            fetch("ajax/removeFromShowcase.php", {
                method: "POST",
                body: data,
            }).then(response => response.json())
            .then(res => {
                // console.log("Success: ", res);
                // console.log(sibling.previousElementSibling);
                sibling.previousElementSibling.classList.remove("hidden");                   
                sibling.classList.add("hidden");
                console.log(post.classList.contains("post__showcase"));
                if(post.classList.contains("post__showcase")){
                    post.classList.add("hidden");
                }
            }).catch((error) =>{
                console.error("Error: ", error);
            });        
        });
    }
}