  function win(txt) { window.status = txt; return true;}
  function CheckAll() {
	for (var i=0;i<document.oForm.elements.length;i++) {
		var e = document.oForm.elements[i];
		if ((e.name != 'allbox') && (e.name != 'nocheck') && (e.type=='checkbox')) {
			e.checked = document.oForm.allbox.checked;
		}
	}
  }
  function CheckCheckAll() {
	var TotalBoxes = 0;
	var TotalOn = 0;
	for (var i=0;i<document.oForm.elements.length;i++) {
		var e = document.oForm.elements[i];
		if ((e.name != 'allbox') && (e.name != 'nocheck') && (e.type=='checkbox')) {
			TotalBoxes++;
			if (e.checked) {
				TotalOn++;
			}
		}
	}
	if (TotalBoxes==TotalOn) {
		document.oForm.allbox.checked=true;
	} else {
		document.oForm.allbox.checked=false;
	}
  }
  

	function toggleTable(el) {
	
	  var myelement = document.getElementById(el);
	  var myimg	= document.getElementById(el + "-img");
	  var myul = document.getElementById(el + "-ul");

	
	  if( !myelement.style.display || myelement.style.display == "none" ) {
	  	myimg.className = "min";
		myelement.style.display = "inline";
		myul.className = "white";
	  } else {
	  	myimg.className = "max";
		myelement.style.display = "none";
		myul.className = "grey";
	  }
	} 
	
	function showHide(id, img) {
		if (document.getElementById(id).style.display == "none") {
			document.getElementById(id).style.display = "inline";
			document.getElementById(img).className = "min";
		} else {
			document.getElementById(id).style.display = "none";
			document.getElementById(img).className = "max";
		}
	}
	
	function showBox(id, img) {
		if (document.getElementById(id).style.display == "none") {
			document.getElementById(id).style.display = "inline";
			document.getElementById(img).className = "min";
		}
	}	
	
	function cColor(id, num) {
		return alert("mouseover");
		if(num == 1) {
			document.getElementById(id).className = "titleOver";
		} else {
			document.getElementById(id).className = "titleOut";		
		}
	}
	
	
	function checkState() {
		var mValue = document.newCampaign.costType[document.newCampaign.costType.selectedIndex].value;

		if(mValue == "5") {
			document.getElementById("timeFrame").style.display = "inline";
			document.getElementById("sdmm").focus();
		} else {
			document.getElementById("timeFrame").style.display = "none";		
		}
	}
	
	function doTab(id, next, num) {
		var mLength = document.getElementById(id).value.length;
		if(mLength == num) {
			document.getElementById(next).focus();
		}	
	}
	
	function showHideLanding() {
		if(document.getElementById("landing").style.display == "none") {
			document.getElementById("landing").style.display = "inline";
		} else {
			document.getElementById("landing").style.display = "none";
		}
	}
	
	function changeTimeDate(id1, id2) {
		document.getElementById(id1).style.display = "inline";
		document.getElementById(id2).style.display = "none"; 
	}
		
	function changeAction() {
		if(document.getElementById('actionType').style.display == "inline") {
			document.getElementById('actionType').style.display = "none";
			document.getElementById('cost').disabled = false;
		} else {
			document.getElementById('actionType').style.display = "inline";
			document.getElementById('cost').disabled = true;
		}
	}
	
	function changeSA() {
		if(document.getElementById('action').style.display == "inline") {
			document.getElementById('action').style.display = "none";
			document.getElementById('sale').style.display = "inline";
		} else {
			document.getElementById('action').style.display = "inline";
			document.getElementById('sale').style.display = "none";
		}
	}	
	
	function doPrint() {
		window.print();  
	}
	
	function pw(){
		window.open('?action=forgot','','width=270,height=120,left=10,top=10,status=no,toolbar=no,menubar=no,scrollbars=yes');
	}
	
	function ip(ip){
		window.open('?action=ip_check_sm&ip='+ip,'ip_search','width=270,height=75,left=10,top=10,status=no,toolbar=no,menubar=no,scrollbars=no,resize=no,alwaysRaised');
	}	