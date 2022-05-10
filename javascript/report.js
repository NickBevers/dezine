let report_input = document.querySelector("#reason");

document.querySelector("#btn__add__Report").addEventListener("click", function () {

    let post_id = this.dataset.post_id;
    let reported_user_id = this.dataset.reported_user_id;
    let reason = document.querySelector("#reason").value;
    let formData = new FormData();

    formData.append("reason", reason);
    formData.append("post_id", post_id);
    formData.append("reported_user_id", reported_user_id);
    console.log(post_id);

    let form__report__error = document.getElementById("form__report__error");
    let form__report__message = document.getElementById("form__report__message");
    let btn__add__Report = document.getElementById("btn__add__Report");
    let form__report__reason = document.getElementById("form__report__reason");

    if (document.getElementById('reason').validity.valid) {

       

        console.log("er is een reden megegeven")

        fetch("ajax/ajax_report.php", {

                method: "POST",
                body: formData
            })

            .then(response => response.json())
            .then(result => {

                if (result.status === "success") {
                    console.log(result)
                };

                btn__add__Report.style.display = "none";
                form__report__reason.style.display = "none";
                form__report__message.style.display = "block";

                let newReason = document.createElement("p");
                newReason.innerHTML = result.body;
                document.querySelector("#form__report__reason__body").appendChild(newReason);
                let homeButton = document.createElement("a");
                var link = document.createTextNode("back to home");
                homeButton.appendChild(link);
                // Set the title.
                homeButton.title = "Back to Home";
                // Set the href property.
                homeButton.href = "home.php";

                document.querySelector("#report__button").appendChild(homeButton);

            })

            .catch(error => {
                console.error('Error:', error);
            });
    } 

    else {
        form__report__error.style.display = "block";
    };

});