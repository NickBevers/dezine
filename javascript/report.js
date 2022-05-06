document.querySelector("#btnAddReport").addEventListener("click", function(){

    
    let post_id = this.dataset.post_id;
  

    let reported_user_id = this.dataset.reported_user_id;

    let reason = document.querySelector("#reason").value;
  

 let formData = new FormData();
 formData.append("reason", reason);
 formData.append("post_id", post_id);
 formData.append("reported_user_id", reported_user_id);
 console.log(post_id);


fetch("ajax/report.php" , {
    method: "POST" ,
    body: formData,
    })
    .then(response => response.json())
    .then(result => {


      
           
       if(result.status === "success"){
           console.log(result)
         
        }


    let newReason = document.createElement("li");
    newReason.innerHTML = result.body;
    document.querySelector(".lol").appendChild(newReason);

    })
    .catch(error => {
    console.error('Error:', error);
    });


});