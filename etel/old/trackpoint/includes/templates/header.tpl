<HTML><HEAD>
<TITLE>%%LNG_ControlPanel%%</TITLE>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="styles/stylesheet.css" type="text/css">
<script language="javascript" src="includes/templates/javascript.js"></script>
<script language="javascript">
var XMLRequest;
var SpanToFill;

var RowsShown = Array();

function highlightRowNoMore() {
	this.className = "gridrow";
}

function highlightRow(e) {
// http://msdn.microsoft.com/workshop/samples/author/dhtml/refs/toElement.htm
// http://msdn.microsoft.com/library/default.asp?url=/workshop/author/dhtml/reference/properties/toelement.asp
// http://msdn.microsoft.com/library/default.asp?url=/workshop/author/dhtml/reference/events.asp
// http://www.quirksmode.org/js/events_mouse.html#mouseenter
// http://www.quirksmode.org/js/events_mouse.html#mouseover 
	this.className = "gridrowover";
}

function showContents() {
    var items = XMLRequest.responseXML.getElementsByTagName("item");

	var table = document.getElementById(SpanToFill);

    for (var i = 0; i < items.length; i++) {

		var contents = Trim(getElementTextNS("", "contents", items[i], 0));
		var revenue = getElementTextNS("", "revenue", items[i], 0);
		var visits = getElementTextNS("", "visits", items[i], 0);
		var conversions = getElementTextNS("", "conversions", items[i], 0);;
		var actionlink = Trim(getElementTextNS("", "action", items[i], 0));

		var landingpagelink = "";
		var conversionpercent = "";

		var viewall = getElementTextNS("", "viewall", items[i], 0);

		if (viewall == 0) {
			conversionpercent = getElementTextNS("", "conversionspercent", items[i], 0) + " %";
			landingpagelink = '<a href="' + actionlink + '">%%LNG_LandingPages%%</a>';
		}

		if (viewall == 1) {
			contents = '<a href="' + actionlink + '">' + contents + '</a>';
		}

		var lastRow = table.rows.length;
		var row = table.insertRow(lastRow);

		row.className = "gridrow";

		if (!document.attachEvent) {
			row.addEventListener("mouseover", highlightRow, false);
			row.addEventListener("mouseout", highlightRowNoMore, false);
		}

		var cell = row.insertCell(0);

		var textNode = document.createTextNode('');
		cell.appendChild(textNode);

		var shortcontents = contents;

		if (viewall == 0 && shortcontents.length > 75) {
			shortcontents = shortcontents.substring(0, 69) + ' ...';
		}

		if (viewall == 0) {
			// if it's a http address, turn it into a link (useful for referrers).
			if (shortcontents.substring(0, 4) == 'http') {
				contents = '<span title="' + contents + '">' + '<a href="' + contents + '" target="_blank">' + shortcontents + '</a></span>';
			};
		}

		var cell = row.insertCell(1);
		cell.innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + contents;

		if (viewall == 1) {
			cell.colSpan = 7;
			continue;
		}

		var cell = row.insertCell(2);
		var textNode = document.createTextNode(visits);
		cell.appendChild(textNode);

		var cell = row.insertCell(3);
		var textNode = document.createTextNode(conversions);
		cell.appendChild(textNode);

		var cell = row.insertCell(4);
		var textNode = document.createTextNode(conversionpercent);
		cell.appendChild(textNode);

		var cell = row.insertCell(5);
		var textNode = document.createTextNode("%%LNG_CurrencySymbol%%" + " " + revenue);
		cell.appendChild(textNode);

		var cell = row.insertCell(6);
		cell.innerHTML = landingpagelink;
	}
	document.getElementById(SpanToFill).style.display = "";
}

function showCampaignContents() {
    var items = XMLRequest.responseXML.getElementsByTagName("item");

	var table = document.getElementById(SpanToFill);

    for (var i = 0; i < items.length; i++) {

		var contents = Trim(getElementTextNS("", "contents", items[i], 0));
		var revenue = getElementTextNS("", "revenue", items[i], 0);
		var cost = getElementTextNS("", "cost", items[i], 0);
		var visits = getElementTextNS("", "visits", items[i], 0);
		var conversions = getElementTextNS("", "conversions", items[i], 0);;
		var actionlink = Trim(getElementTextNS("", "action", items[i], 0));

		var landingpagelink = "";
		var conversionpercent = "";
		var roi = "";

		var viewall = getElementTextNS("", "viewall", items[i], 0);

		if (viewall == 0) {
			conversionpercent = getElementTextNS("", "conversionspercent", items[i], 0) + " %";
			roi = getElementTextNS("", "roi", items[i], 0) + " %";
			landingpagelink = '<a href="' + actionlink + '">%%LNG_LandingPages%%</a>';
		}

		if (viewall == 1) {
			contents = '<a href="' + actionlink + '">' + contents + '</a>';
		}

		var lastRow = table.rows.length;
		var row = table.insertRow(lastRow);

		row.className = "gridrow";

		if (!document.attachEvent) {
			row.addEventListener("mouseover", highlightRow, false);
			row.addEventListener("mouseout", highlightRowNoMore, false);
		}

		var cell = row.insertCell(0);

		var textNode = document.createTextNode('');
		cell.appendChild(textNode);

		var shortcontents = contents;

		if (viewall == 0 && shortcontents.length > 45) {
			shortcontents = shortcontents.substring(0, 39) + ' ...';
		}

		if (viewall == 0) {
			// if it's a http address, turn it into a link (useful for referrers).
			if (shortcontents.substring(0, 4) == 'http') {
				contents = '<span title="' + contents + '">' + '<a href="' + contents + '" target="_blank">' + shortcontents + '</a></span>';
			};
		}

		var cell = row.insertCell(1);
		cell.innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + contents;

		if (viewall == 1) {
			cell.colSpan = 7;
			continue;
		}

		var cell = row.insertCell(2);
		var textNode = document.createTextNode(visits);
		cell.appendChild(textNode);

		var cell = row.insertCell(3);
		var textNode = document.createTextNode(conversions);
		cell.appendChild(textNode);

		var cell = row.insertCell(4);
		var textNode = document.createTextNode(conversionpercent);
		cell.appendChild(textNode);

		var cell = row.insertCell(5);
		var textNode = document.createTextNode("%%LNG_CurrencySymbol%% " + cost);
		cell.appendChild(textNode);

		var cell = row.insertCell(6);
		var textNode = document.createTextNode("%%LNG_CurrencySymbol%% " + revenue);
		cell.appendChild(textNode);

		var cell = row.insertCell(7);
		var textNode = document.createTextNode(roi);
		cell.appendChild(textNode);
	}
	document.getElementById(SpanToFill).style.display = "";
}


function Trim(s) {
	while(s.charAt(0) == ' ' || s.charAt(0) == '\n' || s.charAt(0) == '\r' || s.charAt(0) == '\t') {
		s = s.substring(1, s.length);
	}

	while(true) {
		lastchar = s.substring(s.length -1, s.length);
		if (lastchar != ' ' && lastchar != '\n' && lastchar != '\r' && lastchar != '\t') {
			break;
		}
		s = s.substring(0, (s.length - 1));
	}
	return s;
}

function FetchCampaign(url, spanid) {
	span = spanid + "_detail";
	if (inArray(spanid, RowsShown)) {
		display(spanid);
		return;
	}

	SpanToFill = span;

	url = 'index.php?Page=%%PAGE%%&Action=GenerateXML&' + url;

    // branch for native XMLHttpRequest object
    if (window.XMLHttpRequest) {
        XMLRequest = new XMLHttpRequest();
        XMLRequest.onreadystatechange = processCampaignReqChange;
        XMLRequest.open("GET", url, true);
        XMLRequest.send(null);
    // branch for IE/Windows ActiveX version
    } else if (window.ActiveXObject) {
        XMLRequest = new ActiveXObject("Microsoft.XMLHTTP");
        if (XMLRequest) {
            XMLRequest.onreadystatechange = processCampaignReqChange;
            XMLRequest.open("GET", url, true);
            XMLRequest.send();
        }
    }
	document.getElementById(spanid + "plus").style.display = "none";
	document.getElementById(spanid + "minus").style.display = "";

	arraySize = RowsShown.length;
	RowsShown[arraySize] = spanid;
}

function Fetch(url, spanid) {
	span = spanid + "_detail";
	if (inArray(spanid, RowsShown)) {
		display(spanid);
		return;
	}

	SpanToFill = span;

	url = 'index.php?Page=%%PAGE%%&Action=GenerateXML&' + url;

    // branch for native XMLHttpRequest object
    if (window.XMLHttpRequest) {
        XMLRequest = new XMLHttpRequest();
        XMLRequest.onreadystatechange = processReqChange;
        XMLRequest.open("GET", url, true);
        XMLRequest.send(null);
    // branch for IE/Windows ActiveX version
    } else if (window.ActiveXObject) {
        XMLRequest = new ActiveXObject("Microsoft.XMLHTTP");
        if (XMLRequest) {
            XMLRequest.onreadystatechange = processReqChange;
            XMLRequest.open("GET", url, true);
            XMLRequest.send();
        }
    }
	document.getElementById(spanid + "plus").style.display = "none";
	document.getElementById(spanid + "minus").style.display = "";

	arraySize = RowsShown.length;
	RowsShown[arraySize] = spanid;
}

function display(RowID) {
	Row = RowID + "_detail";

	var table = document.getElementById(Row);
	var rowCount = table.rows.length;

	for (i = 1; i < rowCount; i++) {
		table.rows[i].style.display = "";
	}

	document.getElementById(RowID + "plus").style.display = "none"
	document.getElementById(RowID + "minus").style.display = ""
}

function hide(RowID) {
	Row = RowID + "_detail";
	var table = document.getElementById(Row);
	var rowCount = table.rows.length;

	for (i = 1; i < rowCount; i++) {
		table.rows[i].style.display = "none";
	}

	document.getElementById(RowID + "plus").style.display = ""
	document.getElementById(RowID + "minus").style.display = "none"
}

function processReqChange() {
    // only if XMLRequest shows "loaded"
    if (XMLRequest.readyState == 4) {
        // only if "OK"
        if (XMLRequest.status == 200) {
			showContents();
        } else {
            alert("There was a problem retrieving the XML data:\n" +
                XMLRequest.statusText);
        }
	}
}

function processCampaignReqChange() {
    // only if XMLRequest shows "loaded"
    if (XMLRequest.readyState == 4) {
        // only if "OK"
        if (XMLRequest.status == 200) {
			showCampaignContents();
        } else {
            alert("There was a problem retrieving the XML data:\n" +
                XMLRequest.statusText);
        }
	}
}

// retrieve text of an XML document element, including
// elements using namespaces
function getElementTextNS(prefix, local, parentElem, index) {
    var result = "";
    if (prefix && isIE) {
        // IE/Windows way of handling namespaces
        result = parentElem.getElementsByTagName(prefix + ":" + local)[index];
    } else {
        // the namespace versions of this method 
        // (getElementsByTagNameNS()) operate
        // differently in Safari and Mozilla, but both
        // return value with just local name, provided 
        // there aren't conflicts with non-namespace element
        // names
        result = parentElem.getElementsByTagName(local)[index];
    }
    if (result) {
        // get text, accounting for possible
        // whitespace (carriage return) text nodes 
        if (result.childNodes.length > 1) {
            return Trim(result.childNodes[1].nodeValue);
        } else {
            return Trim(result.firstChild.nodeValue);
        }
    } else {
        return "n/a";
    }
}
</script>
</head>

<body bgcolor="#ADAEAD" text="#000000" leftmargin="10" topmargin="10" marginwidth="10" marginheight="10">
<table width="100%" height="100%"><tr><td>
<!-- 100% Height Table -->
<table border="0" height="100%" width="100%" cellpadding="0" cellspacing="0">
	<tr><td height="100%" valign=top>
			<!-- Do not delete -->

			<!-- START PAGE HEADER -->
			<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="15"><img src="images/lc.gif" width="15" height="17"></td>
						<td width="100%"><img src="images/tbg.gif" width="100%" height="17"></td>
						<td width="10"><img src="images/rc.gif" width="15" height="17"></td>
					</tr>
				</table>

			<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" height="100%">
				<tr>
					<td width="6" style="background-image: URL('images/l_bg.gif')"></td>
					<td bgcolor="#FFFFFF" class="body" style="padding:0px;" valign=top>
						<table class="PageHeader" bgcolor="#F7F7F7">
							<tr>
								<td class="Main"><A href="index.php"><IMG src="images/logo.gif" border="0" height="59" width="180"></A></td>
								<td width="100%" bgcolor="#F7F7F7" align="right" valign="bottom" style="padding-right:20; padding-bottom: 10" class="body">
									%%GLOBAL_TextLinks%%
								</td>
							</tr>
						</table>
						<table cellSpacing="0" cellPadding="0" width="100%" bgcolor="white">
							<TBODY>
								<tr>

									<td vAlign="top" bgcolor="white">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%" align="right">
						<table id="MenuTable" cellSpacing="0" cellPadding="0" width="100%" border="0">
							<tr><td width="100%" bgColor="#f7f7f7" colSpan="3" height="5"></td></tr>
							<TR><Td width="100%" class="appColor" colSpan="3" height="6"></TD></TR>
						</table>
					</td>
				</tr>
			</table>

			<!-- END PAGE HEADER -->