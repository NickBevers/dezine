let link_button = document.querySelector(".getRegisterLinkBtn");

link_button.addEventListener("click", (e) => {
    let data = new FormData();
    data.append("empty", "");

    fetch("ajax/getSpecialRegisterLink.php", {
        method: "POST",
        body: data,
    })
    .then(response => response.json())
    .then(res => {
        if(res.status === "success"){
            navigator.clipboard.writeText(`https://weared-zine.be/register.php?token=${res.link}`);
        } else{
            console.error(`Something has gone wrong: ${res.message}`)
        }
    })
    .catch((error) =>{
        console.error("Error: ", error);
    });

    e.preventDefault();
})