document.querySelector("#btnAddReport").addEventListener("click", function(){


    let post_id = this.dataset.post_id;
    let reason = document.querySelector("#reason").value;

 let formData = new FormData();
 formData.append("reason", reason);
 formData.append("post_id", post_id);


fetch("ajax/report.php" , {
    method: "POST" ,
    body: formData
    })

    .then(response => response.json())
    .then(result => {

    let newReason = document.createElement("li");
    newReason.innerHTML = result.body;
    document
    .querySelector(".lol")
    .appendChild(newReason);

    })
    .catch(error => {
    console.error('Error:', error);
    });


});