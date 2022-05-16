let archive_buttons = document.querySelectorAll(".archive");

archive_buttons.forEach(archive_button => {archive_button.addEventListener("click", (e) => {
    let data = new FormData();
    data.append("report_id", archive_button.dataset.report_id);

    fetch("ajax/archive_report.php", {
        method: "POST",
        body: data,
    })
    .then(response => response.json())
    .then(res => {
        if(res.status === "archived"){
            archive_button.innerHTML = "Archived";
            archive_button.style.borderColor = "#120CFF";
            archive_button.style.color = "#120CFF";
        } else if(res.status === "unarchived"){
            archive_button.innerHTML = "Archive report";
            archive_button.style.borderColor = "#000";
            archive_button.style.color = "#000";
        } else {
            console.error(`${res.message}`)
        }
    })
    .catch((error) =>{
        console.error("Error: ", error);
    });

    e.preventDefault();
}) });