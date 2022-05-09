let report_input = document.querySelector("#reason");



document.querySelector("#btnAddReport").addEventListener("click", function () {


    let post_id = this.dataset.post_id;


    let reported_user_id = this.dataset.reported_user_id;

    let reason = document.querySelector("#reason").value;


    let formData = new FormData();
    formData.append("reason", reason);
    formData.append("post_id", post_id);
    formData.append("reported_user_id", reported_user_id);
    console.log(post_id);

   let x = document.getElementById("lol");
   let xx = document.getElementById("loll");
   let y = document.getElementById("btnAddReport");
   let z = document.getElementById("reason--div");


    if (document.getElementById('reason').validity.valid) {

        x.style.display = "none";

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
                
                y.style.display = "none";
                z.style.display = "none";
                xx.style.display = "block";



                let newReason = document.createElement("p");
                newReason.innerHTML = result.body;
                document.querySelector("#report--reason").appendChild(newReason);

                let homeButton = document.createElement("a");

                var link = document.createTextNode("This is link");

                homeButton.appendChild(link); 
                  
                // Set the title.
                homeButton.title = "Back to Home"; 
                  
                // Set the href property.
                homeButton.href = "home.php"; 

                document.querySelector("#report--button").appendChild(homeButton);


            })
            .catch(error => {
                console.error('Error:', error);

            });
    }
    else {

        x.style.display = "block";


    };

});