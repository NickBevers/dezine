let agreement_button = document.querySelectorAll(".agreement_button");
let warning_message = document.querySelectorAll(".warning_message");

if (agreement_button) {
    for (i = 0; i < agreement_button.length; i++) {
        agreement_button[i].addEventListener("click", (e) => {          
            let warning_id = e.target.dataset.warning_id;
            // console.log(warning_id)
            let data = new FormData();
            data.append("warning_id", warning_id);

            fetch("ajax/ajax_warning.php", {
                method: "POST",
                body: data,
            }).then(response => response.json())
            .then(res => {
                if (res.status === "success") {
                    e.target.parentElement.style.display = "none";
                } else {
                    console.error(`Something has gone wrong: ${res.message}`)
                }
            })
            .catch((error) => {
                console.error("Error: rfeffefrefefefeferfefe ", error);
            });
        })
    }
    e.preventDefault();
}


