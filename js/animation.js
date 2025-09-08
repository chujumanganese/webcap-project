
//to scroll the container to the right
const right_scroller = document.querySelector(".agboli.two");
const left_scroller = document.querySelector(".agboli.one");
const parent_scroll = document.querySelector(".posts");

right_scroller.addEventListener("click", rights);
left_scroller.addEventListener("click", lefts);

function rights(){
    parent_scroll.scrollBy(100, 0);
}

function lefts(){
    parent_scroll.scrollBy(-100, 0);
}

