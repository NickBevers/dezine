let id = document.querySelector(".btn").dataset.id;

document.querySelector(".ban").addEventListener("click", e =>{
    addRemoveBan(e);
});

document.querySelector(".unban").addEventListener("click", e =>{
    addRemoveBan(e);
});

function addRemoveBan(e){
    // console.log("clicked");
    let data = new FormData();
    data.append("id", id);
    data.append("ban", "banUser");

    fetch("ajax/ban.php", {
        method: "POST",
        body: data,
    })
    .then(response => response.json())
    .then(res => {
        if(res.status === "success"){
            // console.log("Success: " + res.message);
            if(res.message === "The ban has been lifted"){
                document.querySelector(".banning").classList.remove("hidden");
                document.querySelector(".banned").classList.add("hidden");
            }else if(res.message === "User has been banned"){
                document.querySelector(".banned").classList.remove("hidden");
                document.querySelector(".banning").classList.add("hidden");
            }
            
            document.querySelector(".alert").classList.remove("hidden");
            document.querySelector(".alert").innerHTML = res.message;
        } else{
            console.error(`Something has gone wrong: ${res.message}`)
        }
    })
    .catch((error) =>{
        console.error("Error: ", error);
    });

    e.preventDefault();
}