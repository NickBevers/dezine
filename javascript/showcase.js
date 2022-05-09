let hearts = document.querySelectorAll(".hearts");
let heartsfull = document.querySelectorAll(".heartsfull");

if(hearts !== null){
    for(let i = 0; i < hearts.length; i++){
        hearts[i].addEventListener("click", (e) => {
            addRemoveShowcase(e);
        });
    }
}

if(heartsfull !== null){
    for(let i = 0; i < heartsfull.length; i++){
        heartsfull[i].addEventListener("click", (e) =>{
            addRemoveShowcase(e);
        });
    }
}

function addRemoveShowcase(e){
    let postId = e.target.dataset.id;
    let sibling = e.target;
    let img = e.target.parentElement;
    let post = img.parentElement;
    let data = new FormData();
    data.append("postId", postId);            
    
    fetch("ajax/addToShowcase.php", {
        method: "POST",
        body: data,
    }).then(response => response.json())
    .then(res => {
        // console.log("Success: ", res);
        if(sibling.classList.contains("hearts")){
            sibling.nextElementSibling.classList.remove("hidden");                    
            sibling.classList.add("hidden");
        } else if(sibling.classList.contains("heartsfull")){
            sibling.previousElementSibling.classList.remove("hidden");                   
            sibling.classList.add("hidden");
            if(post.classList.contains("post__showcase")){
                post.classList.add("hidden");
            }
        }        
    }).catch((error) =>{
        console.error("Error: ", error);
    });  
}