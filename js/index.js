const picker = document.querySelectorAll(".bottom ul li");

console.log(picker);

picker.forEach(element => {
    element.addEventListener("click", colo);
});

function colo(e){
    for(let i = 0; i < 4; i++){
        picker[i].style.background = "transparent";
        picker[i].style.color = "white";
    }
    e.target.style.background = "white";
    e.target.style.color = "black";
}

