var lastID=null;

function toggle_visibility(id) {
	if (lastID == null) {
		lastID = id;
	}
	else {
		document.getElementById(lastID).style.display = 'none';
		lastID = id;
	}
    var e = document.getElementById(id);
    if(e.style.display == 'block')
        e.style.display = 'none';
    else
        e.style.display = 'block';
}