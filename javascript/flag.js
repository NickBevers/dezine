let report = document.querySelectorAll(".post__info__report a");

for(let i = 0; i < report.length; i++){
    report[i].addEventListener("mouseenter", (e) => {
        e.target.querySelector(".report_icon").src = "./assets/report_flag_icon.svg";
    });
}

for(let i = 0; i < report.length; i++){
    report[i].addEventListener("mouseleave", (e) => {        
        e.target.querySelector(".report_icon").src = "./assets/report_icon.svg";
    });
}