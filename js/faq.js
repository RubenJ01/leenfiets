var answers = document.getElementsByClassName("Answer");
var heights = [];

for(var i=0; i<answers.length; i++) {
    heights[i] = answers[i].offsetHeight.toString() + "px";
}

hideAll();


function hideAll() {
    for(var i=0; i<answers.length; i++) {
        answers[i].style.height = "0px";
    }
}

function hideOrShow(elementID) {
    var element = answers[elementID];
    if (element.style.height == heights[elementID]) {
        element.style.height = "0px";
    } else {
        hideAll();
        element.style.height = heights[elementID];
    }
}