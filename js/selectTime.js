var collectionTime = document.getElementById("collectionTime");
var returnTime = document.getElementById("returnTime");

var collectionDate = null;
var returnDate = null;

function Init(collectionDate_, returnDate_) {
  var c = collectionDate_.split("-");
  collectionDate= new Date(c[0], c[1]-1, c[2]);
  var r = returnDate_.split("-");
  returnDate= new Date(r[0], r[1]-1, r[2]);
  SetupOptions(collectionTime);
  SetupOptions(returnTime);
  if (collectionDate.getTime() == returnDate.getTime()) {
    collectionTime.removeChild(collectionTime.childNodes[collectionTime.childNodes.length-1]);
    returnTime.removeChild(returnTime.childNodes[1]);
    collectionTime.setAttribute("onchange", "UpdateTime()");
    returnTime.setAttribute("onchange", "UpdateTime()");
  }
}

function SetupOptions(t) {
  var documentFragment = document.createDocumentFragment();
  for (var i = 0; i < 24; i++) {
    var h = i;
    if (h<10) { h=("0"+h); }
    for (var j = 0; j < 4; j++) {
      var m = j*15;
      if (m<10) { m=("0"+m); }
      var option = document.createElement("OPTION");
      option.innerHTML = (h+":"+m);
      documentFragment.appendChild(option);
    }
  }
  t.appendChild(documentFragment);
}

function UpdateTime() {
  var cIndex = (collectionTime.selectedIndex+1);
  var rIndex = (returnTime.selectedIndex+1);
  console.log(rIndex);
  if (cIndex > rIndex) {
    returnTime.childNodes[rIndex].removeAttribute("selected");
    returnTime.childNodes[cIndex].setAttribute("selected", "selected");
    rIndex = returnTime.selectedIndex+1;
    if (cIndex > rIndex) {
      collectionTime.childNodes[cIndex].removeAttribute("selected");
      collectionTime.childNodes[rIndex].setAttribute("selected", "selected");
    }
  }
}
