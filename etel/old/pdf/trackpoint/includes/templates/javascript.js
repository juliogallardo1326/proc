	function ShowHelp(div, title, desc)
	{
		div = document.getElementById(div);
		div.style.display = 'inline';
		div.style.position = 'absolute';
		div.style.width = '300';
		div.style.backgroundColor = 'lightyellow';
		div.style.border = 'dashed 1px black';
		div.style.padding = '10px';
		div.innerHTML = '<span class=body><b>' + title + '</b></span><br><img src=images/1x1.gif width=1 height=5><br><div style="padding-left:10; padding-right:5" class=body>' + desc + '</div>';
	}

	function HideHelp(div)
	{
		div = document.getElementById(div);
		div.style.display = 'none';
	}

	function doCustomDate(myObj) {
		if (myObj.options[myObj.selectedIndex].value == "Custom") {
			document.getElementById("customDate").style.display = ""
			document.getElementById("showDate").style.display = "none"
		} else {
			document.getElementById("customDate").style.display = "none"
			document.getElementById("showDate").style.display = ""
		}
	}

function inArray(id, arraylist) {
	for (i = 0; i < arraylist.length; i++) {
		val = arraylist[i].toString();
		if (id == val) return true;
	}
	return false;
}

