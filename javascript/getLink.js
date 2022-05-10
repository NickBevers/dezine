let link_input = document.querySelector(".specialRegisterLink");
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
            link_input.value = res.link;
            console.log(res.link);
            navigator.clipboard.writeText(`${window.location.origin}/dezine/register.php?token=${res.link}`);
        } else{
            console.error(`Something has gone wrong: ${res.message}`)
        }
    })
    .catch((error) =>{
        console.error("Error: ", error);
    });

    e.preventDefault();
})