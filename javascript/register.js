let email_input = document.querySelector(".register--email");
let username_input = document.querySelector(".register--username");
let input_message_email = document.querySelector(".message--email");
let input_message_username = document.querySelector(".message--username");
let regex = /[a-zA-Z0-9_.+-]+@(student\.)?thomasmore\.be/;

email_input.addEventListener("keyup", (e) => {
    let email_value = email_input.value;
    if(regex.test(email_value)){
        let data = new FormData();
        data.append("email", email_value);
    
        fetch("ajax/register_email.php", {
            method: "POST",
            body: data,
        })
        .then(response => response.json())
        .then(res => {
            if(res.status === "error"){
                input_message_email.classList.add("show");
                input_message_email.innerHTML = `${res.message}`;
            } else if(res.status === "success"){
                input_message_email.classList.remove("show");
                input_message_email.innerHTML = `${res.message}`;
            }
        })
        .catch((error) =>{
            console.error("Error: ", error);
        });
    } else{
        console.log("not a valid email");
        input_message_email.innerHTML = "This is not a valid email.";
    }

    e.preventDefault();
})

username_input.addEventListener("keyup", (e) => {
    let username_value = username_input.value;
    let data = new FormData();
    data.append("username", username_value);

    fetch("ajax/register_username.php", {
        method: "POST",
        body: data,
    })
    .then(response => response.json())
    .then(res => {
        if(res.status === "error"){
            input_message_username.classList.add("show");
            input_message_username.innerHTML = `${res.message}`;
        } else if(res.status === "success"){
            input_message_username.classList.remove("show");
            input_message_username.innerHTML = `${res.message}`;
        }
    })
    .catch((error) =>{
        console.error("Error: ", error);
    });

    e.preventDefault();
})