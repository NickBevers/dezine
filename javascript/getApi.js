window.addEventListener("load", (e)=>{
    fetch("./../ajax/getPosts.php", {
        method: "GET",
    })
    .then(response => response.json())
    .then(res => {
        if(res.status === "success"){
            document.body.appendChild(document.createElement('pre')).innerHTML = JSON.stringify(res, undefined, 4);
        } else{
            console.error(`Something has gone wrong: ${res.message}`)
        }
    })
    .catch((error) =>{
        console.error("Error: ", error);
    });

    e.preventDefault();
});
