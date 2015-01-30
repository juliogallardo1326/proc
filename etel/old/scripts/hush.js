// css detect
var css = (document.getElementById) ? 1 : 0;
var ns4 = (document.layers) ? 1 : 0;
var moz = (navigator.product == "Gecko") ? 1 : 0;
var ie5 = (navigator.userAgent.indexOf('MSIE 5') > -1) ? 1 : 0;


// for use with addHushIdentityFavorite()
var browserApp = navigator.appName;
var browserNum = navigator.appVersion;
var ie = (browserApp=="Microsoft Internet Explorer") ? 1 : 0; 

// paypal
function payPal(ppForm, hushForm)
{
	ppForm.elements['os0'].value = hushForm.elements['hush_alias'].value;
	var country = hushForm.elements['country'].value;
	ppForm.elements['os1'].value = country;
	var price = ppForm.elements['a3'].value;
	var totalPrice = price;

	if (country == "AT" || country == "BE" || country == "DK" || country == "FI" ||
		country == "FR" || country == "DE" || country == "GB" || country == "GR" ||
		country == "IE" || country == "IT" || country == "LU" || country == "NL" ||
		country == "PT" || country == "ES" || country == "SE" || country == "UK")
	{
		totalPrice = price * 1.21;
		totalPrice = "" + Math.round(totalPrice*100)/100;
		if(totalPrice.substring(totalPrice.indexOf(".") + 1).length == 1)
		{
			totalPrice = totalPrice + "0";
		}
		ppForm.elements['a3'].value = totalPrice;
	}
}

// egold
function egold(egForm, hushForm)
{

	var country = hushForm.elements['country'].value;
	egForm.elements['itemDetails'].value = hushForm.elements['hush_alias'].value;
	egForm.elements['country'].value = country;
	//egForm.elements['SUGGESTED_MEMO'].value 
	//= egForm.elements['itemGeneral'].value + ": " 
	//	+ egForm.elements['itemDetails'].value + ": " + country;
        
	egForm.elements['SUGGESTED_MEMO'].value =
	egForm.elements['itemDetails'].value + " ["
	+ country + "] "
	+ egForm.elements['itemGeneral'].value;

	if (country == "AT" || country == "BE" || country == "DK" || country == "FI" ||
		country == "FR" || country == "DE" || country == "GB" || country == "GR" ||
		country == "IE" || country == "IT" || country == "LU" || country == "NL" ||
		country == "PT" || country == "ES" || country == "SE" || country == "UK")
	{
		var price = egForm.elements['PAYMENT_AMOUNT'].value;
		var vatPrice = price * 1.21;
		vatPrice = "" + Math.round(vatPrice*100)/100;
		if(vatPrice.substring(vatPrice.indexOf(".") + 1).length == 1)
		{
			vatPrice = vatPrice + "0";
		}
		egForm.elements['PAYMENT_AMOUNT'].value = vatPrice;
	}
}

// mainmenu ----------------------------------------

var loc;
var menuItems = {
	"main":{
		"login":{"signup":null,"log-in":null,"upgrade":null},
		// "about":{"affiliate":null,"newfeatures":null,"how":null,"who":null,"news":null,"testimonials":null,"tell":null,"corporatesite":null},
		"about":{"affiliate":null,"newfeatures":null,"how":null,"who":null,"testimonials":null,"tell":null,"corporatesite":null},
		"services":{"mail":null,"imap":null,"extrastorage":null,"identity":null,"secureforms":null,"messenger":null,"tools":null,"corporate":null,"downloads":null},
		"help":{"faqs":null,"pgp":null}
	}
}
// var NS_menuWidths = {'main':118,'about':158,'services':158,'help':158};

// main menu coords 
var NS_menuWidths = {'main':118,'login':158,'about':158,'services':158,'help':158};

function makeMainMenu(l){
	for(i in menuItems){
		makeMenu(i,menuItems[i],l);
	}
}

function makeMenu(name,mi,l){
	var morem = [];
	var mm = "";
	mm += '<div id="'+name+'Menu"><table cellpadding="0" cellspacing="3" width="120" border="0" bgcolor="white">';
	for(i in mi){
		if(mi[i]) morem.push([i,mi[i]]);
//		if(l==i){ var active = 1; loc = 
		mm += MainMenuItem(i,(l!=i),name);
	}
	mm += '</table></div>';
	document.write(mm);
	var j = morem.length;
	while(j--){ 
		makeMenu(morem[j][0],morem[j][1],l);
		// link the buttons to their menus
		var butt = (ns4) ? document[name+'Menu'].document.layers["mm"+morem[j][0]] : getItem("mm"+morem[j][0]);
		butt.menu = getItem(morem[j][0]+"Menu");
		butt.menu.mname = morem[j][0]+"Menu";
	};
	// init stuff
	if(ns4){
		if(l){ loc = document[name+'Menu'].document.layers["mm"+l]; }
//		loc.bgColor = "#eeeeee";
		var c = 0;
		var b,p = getItem(name+'Menu');
		for(i in mi){
			b = document[name+'Menu'].document.layers["mm"+i];
			b.top = b.y = 4 + (c++)*25;
			b.left = b.x = 4;
			b.width = NS_menuWidths[name];
			b.pmenu = p;
//			n.visibility = "visible";
//			if(i != l){
				b.message = i;
				b.onMouseOver = NS_over;
				b.onMouseOut = NS_out;
//			}
		}
	}
	else{
		if(l){
			loc = getItem("mm"+l);
		}
		var b,p = getItem(name+"Menu");
		var c = 0;
		for(i in mi){
			b = getItem("mm"+i);
			b.message = i;
			b.pmenu = p;
			b.y = (moz || ie5) ? 7 + (c++)* 23 : 8 + (c++)* 25;
		}
	}
	makeMainMenuArrow();
}

function MainMenuItem(label,active,menuName){
	var mm = "<tr><td>";
	if(ns4) mm += "<table bgcolor=\"#fe650c\" cellpadding=1 cellspacing=0 width="+(NS_menuWidths[menuName]+2)+" border=0><tr><td>";
	mm += "<table cellpadding=0 height=20 cellspacing=0 width=\"100%\" border=0><tr><td id=\"mm"+label+"\"";
	/*if(active) */mm +=  "class=\"MMenu\" onMouseUp=\"CSS_up(this);\" onMouseOver=\"CSS_over(this);\" onMouseOut=\"CSS_out(this);\">";
	//else mm += "class=\"MMenu\" onMouseOver=\"CSS_over(this);\" onMouseOut=\"CSS_out(this);\">";
	if(ns4){
		mm += "<layer id=\"mm"+label+"\" class=\"mMenu\" width="+NS_menuWidths[menuName]+" height=20 bgColor=\"#ffffff\">";
		if(active) mm += "<a href=\"javascript:CSS_up();\">";
	}
	mm += "<img src=\"pics/mm_"+label+".gif\" border=0>";
	if(ns4) mm += "</layer>"+((active)? "</a>":"") + "</td></tr></table>";
	mm += "</td></tr></table></td></tr>";
	return(mm);
}

var mainArrow;
function makeMainMenuArrow(){
	document.write('<div id="mainArrow00"><img src="pics/sm_arrow.gif"></div>');
	mainArrow = getItem('mainArrow00');
	if(ns4) mainArrow.style = mainArrow;
	mainArrow.style.visibility = "hidden"; //"visible";
	mainArrow.style.left = (ns4) ? 20 : "20px";
}

document.NSstore = {};
function NS_over(){
	if(returnArrowTimer) clearTimeout(returnArrowTimer);
	document.NSstore.message = this.message;
	document.NSstore.pmenu = this.pmenu;
	document.onMouseUp = CSS_up;
	document.captureEvents(Event.MOUSEUP);
	loc.bgColor = "#ffffff";
	this.bgColor = "#f1f6fa";
	mainArrow.style.top = this.y + this.pmenu.top + 4;
	mainArrow.style.left = this.pmenu.left + 4;
	var submenu = (this.menu) ? this.menu : (this.pmenu) ? this.pmenu : null;
	if(hideTarget && hideTarget != submenu.mname){ var t = hideTarget; hideTarget=0; hideMenu(t); };
	if(submenu.mname){
		submenu.style.visibility = "visible";
		submenu.up = 1;
		if(submenu.hideTimer) clearTimeout(submenu.hideTimer);
	}
}


function NS_out(){
	document.onMouseUp = null;
	document.releaseEvents(Event.MOUSEUP);
	this.bgColor = "#ffffff";
	var submenu = (this.menu) ? this.menu : (this.pmenu) ? this.pmenu : null;
	if(submenu.up){
		hideTarget = submenu.mname;
		submenu.hideTimer = setTimeout("hideMenu(\""+submenu.mname+"\");",600);
	}
	if(this != loc) returnArrowTimer = setTimeout("returnArrow()",600);
}

var returnArrowTimer;

function CSS_over(t){
	if(returnArrowTimer) clearTimeout(returnArrowTimer);
	// t.style.background = "#f1f6fa";
	t.style.background = "#FAEFD9";
	mainArrow.style.top = (t.y + parseInt(t.pmenu.style.top)) + "px";
	mainArrow.style.left = (parseInt(t.pmenu.style.left)+4)+"px";
	mainArrow.style.visibility = "visible";
//	t.style.border="1px solid #fe660d";
	var submenu = (t.menu) ? t.menu : (t.pmenu) ? t.pmenu : null;
	window.status = t.message;
	if(hideTarget && hideTarget != submenu.mname){ hideMenu(hideTarget); hideTarget=0; };
	if(submenu.mname){
		submenu.style.visibility = "visible";
		submenu.up = 1;
		if(submenu.hideTimer) clearTimeout(submenu.hideTimer);
	}
}

function CSS_up(t){
	// if ns4 this is called by document.
	if (ns4) t = document.NSstore;
	var l = (t.pmenu.mname) ? t.pmenu.mname.substring(0,t.pmenu.mname.indexOf("M")) : t.message;
	window.location = l + '.php?' + G_sess + '&' + 'subloc=' + t.message;
}

var hideTarget;
function CSS_out(t){
	var submenu = (t.menu) ? t.menu : (t.pmenu) ? t.pmenu : null;
	if(submenu.up){
//		alert(t.menu.name);
		hideTarget = submenu.mname;
		submenu.hideTimer = setTimeout("hideMenu(\""+submenu.mname+"\");",600);
	}
	window.status = '';
	if(t != loc) returnArrowTimer = setTimeout("returnArrow()",600);
//	t.style.border = "1px solid #dddddd";
	t.style.background = "#ffffff";
/*	if(t==loc){
		t.style.backgroundImage = "url('pics/mm_arrow.gif')";
		t.style.backgroundRepeat = "no-repeat";
	}*/
//	mainArrow.style.visibility = "hidden";
}

function hideMenu(t){
	var tt = getItem(t);
	tt.style.visibility = "hidden";
	tt.up=0;
}

function returnArrow(){
	if(ns4){
//		if(hideTarget) loc.bgColor = "#f1f6fa";
		mainArrow.top = loc.y + loc.pmenu.top + 4;
		mainArrow.left = loc.pmenu.left + 4;
	}
	else{
		mainArrow.style.top = (loc.y + parseInt(loc.pmenu.style.top)) + "px";
		mainArrow.style.left = (parseInt(loc.pmenu.style.left)+4)+"px";
	}
}

function init(){
	// hush script checking for old browsers
	checkCompat();
	// var items = ["mainMenu","aboutMenu","servicesMenu","helpMenu"];
	// var coord = [{x:250,y:34},{x:371,y:(moz)?57:59},{x:371,y:(moz)?80:84},{x:371,y:(moz)?103:109}];

	// main menu coords 
	var coord = [{x:250,y:34},{x:371,y:(moz)?34:34},{x:371,y:(moz)?57:59},{x:371,y:(moz)?80:84},{x:371,y:(moz)?103:109}];
	var items = ["mainMenu","loginMenu","aboutMenu","servicesMenu","helpMenu"];

	for(i=0;i<items.length;i++){
		var m = getItem(items[i]);
		if(ns4) m.style = m;
		moveToX(m,coord[i].x);
		moveToY(m,coord[i].y);
	}
	var mm = getItem('mainMenu');
	mm.style.visibility = "visible";
	if(!moz && !ie5) initScrollingNews();
	if(ns4){
		window.onresize = function(evt){ location.reload(); }
		if(isSideMenu) NS_positionSideMenu();
	}
	else if(isSideMenu){
		CSS_smOut();
		if(subLoc) sideArrow.style.visibility = "visible";
	}
	// getItem('mainMenu').zIndex = 100;
	getItem('aboutMenu').zIndex = 101;
	getItem('servicesMenu').zIndex = 102;
	getItem('helpMenu').zIndex = 103;
	mainArrow.style.zIndex = 104;
	mainArrow.style.visibility = "visible";
	returnArrow();
}

var isSideMenu = 0;
var subLoc = null;
function makeSideMenu(l,sl){
	isSideMenu = l;
	var mm;
	mm = '<table width="216" height="355" cellpadding=0 cellspacing=0 border=0><tr valign="top"><td background="pics/menuImg_login_all.jpg">';
	mm += '<table cellpadding=0 cellspacing=0 border=0 background="pics/spacer.gif"><tr><td rowspan=2><img src="pics/spacer.gif" width=7></td><td><img src="pics/spacer.gif" height=4></td><tr valign="top"><td><table cellpadding=0 cellspacing=2 border=0>';
	var a = menuItems.main[l];
	var c = 0;
	for(i in a){
//		if(ns4) mm += '<tr><td class="sideMenu" background="pics/sm_bg'+(c++)+'.jpg"><img src="pics/spacer.gif" width="139" height="18"><layer id="sm'+i+'" width="153" height"18"><img src="pics/mm_'+i+'.gif"></layer></td></tr>';
		if(ns4) mm += '<tr><td class="sideMenu" background="pics/sm_bg'+(c++)+'.jpg"><img src="pics/spacer.gif" width="139" height="18"></td></tr>';
		else mm += '<tr><td id="sm'+i+'" class="sideMenu" background="pics/sm_bg'+(c++)+'.jpg" onMouseOver="CSS_smOver(this);" onMouseUp="CSS_smUp(this);" onMouseOut="CSS_smOut(this);"><img src="pics/mm_'+i+'.gif"></td></tr>';
	}
	mm += '</table></td></tr></table>';
	mm += '</td></tr></table>';
	document.write(mm);
	var m;
	c = 0;
	for(i in a){
		if(ns4) document.write('<layer id="sm'+i+'" class="sMenu" width="153"><a href="javascript:CSS_up();"><img src="pics/mm_'+i+'.gif" border=0></a></layer>');
		m = getItem("sm"+i);
		m.visibility = "hide";
		m.yval = (ns4) ? 153 + 20*(c++) : (153 + 20*(c++)) + "px";
		m.pmenu = {"mname":l+"M"};
		m.message = i;
		if(i == sl) subLoc = m;
		if(ns4){
			m.onMouseOver = NS_smOver;
			m.onMouseOut = NS_smOut;
		}
	}
}

function NS_positionSideMenu(){
	CSS_smOut();
	var m,i,a = menuItems.main[isSideMenu];
	var c=0;
	for(i in a){
		m = getItem("sm"+i);
		m.top = 149 + (c++)*20;
		m.left = 34;
	}
	if(subLoc) sideArrow.visibility = "visible";
}

function NS_smOver(){
	document.onMouseUp = CSS_smUp;
	document.captureEvents(Event.MOUSEUP);
	document.NSstore.message = this.message;
	document.NSstore.pmenu = this.pmenu;
	CSS_smOver(this);
}

function NS_smUp(){
	alert(document.NSstore.message);
}

function NS_smOut(){
	document.onMouseUp = null;
	document.releaseEvents(Event.MOUSEUP);
	CSS_smOut();
}

function CSS_smOver(t){
	sideArrow.style.visibility = 'visible';
	sideArrow.style.top = t.yval;
}

function CSS_smUp(t){
	CSS_up(t);	
//	alert(t.message);
}

function CSS_smOut(t){
	if(subLoc) sideArrow.style.top = subLoc.yval;
	else sideArrow.style.visibility = 'hidden';
}

function makeSideMenuArrow(){
	document.write('<div id="sideArrow00"><img src="pics/sm_arrow.gif"></div>');
	sideArrow = getItem('sideArrow00');
	if(ns4) sideArrow.style = sideArrow;
	sideArrow.style.visibility = "hidden";
	sideArrow.style.left = (ns4) ? 19 : "19px";
}

function makeSideFooter(){
	document.write('<table width="100%" cellpadding=0 cellspacing=3 border=0><tr><td width="2"><img src="pics/spacer.gif" height="15" width="2"></td><td><a href="login.php?'+G_sess+'&subloc=privacy"><img src="pics/txt_privacy.gif" border=0></a></td><td align="center"><a href="login.php?'+G_sess+'&subloc=terms"><img src="pics/txt_terms.gif" border=0></a></td><td align="right"><a href="login.php?'+G_sess+'&subloc=disclaimer"><img src="pics/txt_disclaimer.gif" border=0></a></td></tr><tr><td rowspan=2><img src="pics/spacer.gif" width="2"></td><td colspan=3 background="pics/bg_dotted.gif"><img src="pics/spacer.gif"></td></tr><tr><td colspan=3><table width="100%" cellpadding=0 cellspacing=3 border=0><tr><td><a target="_blank" href="contact/"><img src="pics/contact.gif" border=0></a></td><td>&nbsp;</td><td><a href="help.php?' + G_sess + '&subloc=help"><img src="pics/help.gif" border=0></a></td><td width="100%" align="right"><a href="http://www.kinkylogic.com" target="_blank"><img src="pics/kinkyCredit.gif" border=0></a></td></tr></table></td></tr></table>');
}

function makeLoginWindow(lite,mailserver,username,domain){
	if(lite){
		var formAction = "https://"+mailserver+"/hushmail/index.php";
		var altLogin = "login_premiumUsers.gif";

// old popunder script - remove when login script is moved to standard include 
/*
		document.write('<script> var popunder=new Array(); popunder[0]="/popunders.php?subloc=upgrade&login_popunder=1"; var url = popunder[Math.floor(Math.random()*(popunder.length))]; win2=window.open(url, "hushpremiumpopup", "location=no,width=790,height=500,scrollbars=yes,resizable=yes,status=yes"); win2.blur(); window.focus(); </script>');

*/
	}else{
		var formAction = "https://"+mailserver+"/hushmail/index.php";
		var altLogin = "login_standardUsers.gif";
	}
	document.write('<table width="216" height="355" cellpadding=0 cellspacing=0 border=0><tr valign="top"><td bgcolor="#0d4697" background="pics/menuImg_login_all.jpg"><table cellpadding=0 cellspacing=0 border=0 background="pics/spacer.gif"><tr valign="top"><td width="46" rowspan="3" align="left"><img src="pics/login_1.gif"><img src="pics/login_arrow1.gif"></td><td colspan="2"><img src="pics/spacer.gif" height="12" width="170"><img src="pics/txt_haveAccount.gif"></td></tr><tr><td><img src="pics/spacer.gif" height="16" width="10"><img src="pics/login_login.gif"></td><td rowspan="2" valign="bottom" width="65" align="center"><a href="javascript:submitLogin()" onMouseOver="window.status=\'Type Username then Click here to login\'; return true;" onMouseOut="window.status=\'\';"><img src="pics/go.gif" border="0" alt="Login"></a><img src="pics/spacer.gif" height="23"></td></tr><tr valign="top"><td><form method="post" action="' + formAction + '" name="loginform" class="login"><input type="text" name="hush_username" VALUE="'+username+'" size="'+((ns4) ? '6' : '15')+'"><br><SELECT class="input" NAME="hush_domain"><OPTION VALUE="hushmail.com" ' + (domain=='hushmail.com'?'SELECTED':'') + '>@hushmail.com</OPTION><OPTION VALUE="hush.com" ' + (domain=='hush.com'?'SELECTED':'') + '>@hush.com</OPTION><OPTION VALUE="hush.ai" ' + (domain=='hush.ai'?'SELECTED':'') + '>@hush.ai</OPTION><OPTION VALUE="mac.hush.com" ' + (domain=='mac.hush.com'?'SELECTED':'') + '>@mac.hush.com</OPTION></SELECT><input type="hidden" name="hush_customerid" value="0000000000000000"><input type="hidden" name="hush_exitpage" value="https://www.hushmail.com"><input type="hidden" name="hush_exittarget" value="_self"><img src="pics/spacer.gif" height="11"><a href="login.php?'+ G_sess + ((lite) ? '' : '&lite=1') + '"><img src="pics/'+altLogin+'" border=0></a></form></td></tr><tr><td colspan="3"><img src="pics/spacer.gif" height="'+((moz) ? 28 : (ns4) ? 1 : 28)+'"></td></tr><tr valign="top"><td rowspan="3"><img src="pics/login_2.gif"><img src="pics/login_arrow2.gif"></td><td colspan="2"><img src="pics/spacer.gif" height="11" width="170"><img src="pics/login_needAnAccount.gif"></td></tr><td colspan="2"><img src="pics/spacer.gif" height="16" width="10"><img src="pics/login_signup.gif"></td></tr><tr><td colspan="2"><br><table cellpadding=0 cellspacing=0 border=0 background="pics/spacer.gif"><tr><td><img src="pics/login_txtGo.gif"></td><td><img src="pics/spacer.gif" width="10"></td><td><a href="login.php?subloc=signup&'+G_sess+'"><img src="pics/go.gif" border=0></a></td></tr></table></td></tr><tr><td colspan="3"><table cellpadding=0 cellspacing=0 border=0 background="pics/spacer.gif"><tr><td><img src="pics/spacer.gif" width="10"></td><td><img src="pics/spacer.gif" height="44"</td></tr><tr><td rowspan=2><img src="pics/spacer.gif" width="10" height="71"></td><td valign="top"><a href="login.php?subloc=upgrade&'+G_sess+'"><img src="pics/login_upgrade.gif" border=0></a></td></tr></tr><tr><td><img src="pics/login_poweredBy.gif"></td></tr></table></td></tr></table></td></tr></table>');
	document.write('<script language="JavaScript"> window.document.forms[\'loginform\'].elements[\'hush_username\'].focus(); </script>');
}

function makeHeader(banner,url){
	spacer('100','45');
	if(!css) document.write('<table width="100%" cellpadding=0 cellspacing=0 border=0><tr><td width="7"><img src="pics/spacer.gif" width="7"></td><td width="1" bgcolor="#dddddd"><img src="pics/spacer.gif"></td><td><img src="pics/ns/header_bg.gif"></td><td background="pics/ns/header_bg.gif">');
	document.write('<table cellspacing=0 border=0 cellpadding=' + ((css) ? '0 id="header"' : '1 background="pics/spacer.gif"') + '<tr><td><a href="login.php?' + G_sess + '"><img class="logo" src="pics/logo.gif" border=0></a></td><td><img src="pics/spacer.gif" width="140" height="1"></td><td width="100%" align="center"><a href="'+url+'"><img src="banners/'+banner+'" border=0></a></td></tr></table>');
	if(!css) document.write('</td><td width="1" bgcolor="#dddddd"><img src="pics/spacer.gif" width=1></td></tr></table>');
}

/*
function makeFooter(){
	document.write('<table bgcolor="#a8d2e5" width="100%"><tr><td><p style="font-size:9px;letter-spacing:1px;">&#169; 1999-2003 HUSH COMMUNICATIONS CORP &nbsp;&nbsp;&nbsp;&nbsp; ALL RIGHTS RESERVED</p></td></tr></table>');
}
*/

function makeFooter() {
	var footer = '<br><table width="100%" cellspacing="0" cellpadding="2" class="linkTable"><tr>' 
		+ '<td class="linkTable"><p class="newsItem">'
		+ '<a href="login.php">Login</a>'
		+ '&nbsp;|&nbsp;'
		+ '<a href="about.php?subloc=about">About</a>'
		+ '&nbsp;|&nbsp;'
		+ '<a href="services.php?subloc=services">Services</a>'
		+ '&nbsp;|&nbsp;'
		+ '<a href="help.php?subloc=help">Help</a>'
		+ '</p></td>'
		+ '<td align="right" class="linkTable"><p class="newsItem">'
		+ '&#169; 1999-2004 Hush Communications Corp'
		+ '&nbsp;&nbsp;|&nbsp;&nbsp;'
		+ '<a href="#">top ^</a>'
		+ '</p></td>'
		+ '</tr></table><br>';
	document.write(footer);
}




// table makers ------------------------------------------------- */

bgColours = {'content':'ffffff','subTitle':'c7dbeb','title':'f1f6fa','subContent':'e5f1f7'};

function startTable(style,content){
	if(!css) document.write('<table bgcolor="#aad2e6" width="100%" cellpadding=0 cellspacing=0 border=0><tr><td>');
	document.write('<table class="content" width="100%" cellpadding=0 cellspacing='+((!css)? 1: 0)+' border=0>');
//	document.write('<tr><td bgcolor="#'+bgColours[style]+'"><p class="'+style+'">'+content+'</p></td></tr>');
}

function addCell(style,content){
	document.write('<tr><td bgcolor="#'+bgColours[style]+'" class="borderTop"><div class="'+style+'">'+content+'</div></td></tr>');
}

function endTable(){
	document.write('</table>');
	if(!css) document.write('</td></tr></table>');
}


// graphic content header 
function addImgHeader(bgcolor,img,img2,bgimg,content) {
	if (!ns4) {
	document.write('<tr><td class="borderTop" bgcolor="#'+bgcolor+'"><table cellspacing="0" cellpadding="0" border="0" width="100%" class="headerBG" style="background-image: url('+bgimg+');">');
        document.write('<tr>'); 
	document.write('<td valign="top"><a href="services.php?subloc=upgrade"><img src="'+img+'" alt="" height="50" border="0"></a></td>');
	document.write('<td width="100%"><div class="imgTitle" style="background-image: url('+img2+');">'+content+'</div></td></tr>');
	document.write('</table></td></tr>');
	}
}





function spacer(w,h){
	document.write('<img src="pics/spacer.gif" width="'+w+'"height="'+h+'">');
}

function moreLink(linkto){
	document.write('<tr valign="bottom"><td bgcolor="#e5f1f7" align="right"><a href="'+linkto+'"><img class="more" src="pics/more.gif" border=0></a></td></tr>');
}


var linkId = 0;

function startLinkTable(content){
	if(!css) document.write('<table width="100%" bgcolor="#aad2e6" cellpadding=0 cellspacing=0 border=0><tr><td>');
	document.write('<table class="content" width="100%" cellpadding=0 cellspacing='+((!css)? 1: 0)+' border=0>');
}

function addLinkCell(lnk, content){
	document.write('<tr><td id="link'+(linkId++)+'" class="linkTable" jonMouseOver=\"CSS_linkover(this);\" jonMouseOut=\"CSS_linkout(this);\">');
	if(ns4) document.write('<span class="linkTable"><a href="'+lnk+'">'+content+'</a></span></td></tr>');
	// else document.write('<span class="linkTable"><a href="'+lnk+'">'+content+'</a></span></td></tr>');
	else document.write('<span class="newsItem"><a href="'+lnk+'">'+content+'</a></span></td></tr>');
}

function CSS_linkover(t){
	t.style.backgroundImage = "url('pics/mm_arrowShort.gif')";
	t.style.backgroundRepeat = "no-repeat";
	t.style.backgroundColor = "#c7dbeb";
}

function CSS_linkout(t){
	t.style.background = "#e5f1f7";
}
// misc ---------------------------------

function getItem(targ){
	if(css) return document.getElementById(targ);
	else if(ns4) return document[targ];
	else return eval(targ);
//	else return document[targ];
}

function moveToX(t,x){
	if(ns4) t.left=x;
	else t.style.left=x+"px";
}

function moveToY(t,y){
	if(ns4) t.top = y;
	else t.style.top=y+"px";
}

// array replacements for brain damaged ie 5.
if(ie5){
	Array.prototype.push = function(i){
		this[this.length] = i;
	}
	
	Array.prototype.splice = function(i,l){
		var len = this.length-l;
		var a = new Array();
		while(i < len){
			this[i] = this[i+l];
			i++;
		}
		this.length = len;
	}
}

// from the old hush site ---------------

function submitLogin(){
	window.document.forms['loginform'].submit();
}

function checkCompat(){
	var v = navigator.appVersion;	        
	var vNum = parseFloat(v.substring(0,v.indexOf(" ")));
	if ((v.indexOf("MSIE") != -1 && vNum < 4 ) || (v.indexOf("MSIE") == -1 && vNum < 4.04 )){
	 	alert("You are using a browser version that is not compatible with HushMail!\nHushMail requires Netscape Communicator 4.04 or above\nor Microsoft Internet Explorer 4.0 or above.\n");
	}
	browserName = navigator.appName;
	browserVer  = parseInt(navigator.appVersion);
	if((browserName == "Netscape" && browserVer >= 3) || (browserName == "Microsoft Internet Explorer" && browserVer >=4) || (browserName == "Opera" && browserVer >=3)){
		version = "RollOverCapable";
	}else{
		version = "x";
	}
}

function friendValidator(theForm){
        var error = "";
        var digits = "0123456789";

        if (theForm.name.value == ""){
                error += "Please enter your name\n\n";
        }

        if (theForm.hushmail_address.value == ""){
                error += "Please enter your HushMail address\n\n";
        }
        if ((theForm.hushmail_address.value.indexOf ('@',0) == -1 ||
                theForm.hushmail_address.value.indexOf ('.',0) == -1) &&
                theForm.hushmail_address.value != ""){
                error += "Please enter your full HushMail address\n\n";
        }

        if (theForm.friends_name.value == ""){
                error += "Please enter your friend's name\n\n";
        }


        if (theForm.friends_address.value == ""){
                error += "Please enter your friend's email address\n\n";
        }
        if ((theForm.friends_address.value.indexOf ('@',0) == -1 ||
                theForm.friends_address.value.indexOf ('.',0) == -1) &&
                theForm.friends_address.value != ""){
                error += "Please verify that your friend's email address is valid";
        }

        if (error != ""){
                alert(error);
                return (false);
        } else {
                return (true);
        }
}

function contactValidator(theForm){
        var error = "";
        var digits = "0123456789";

        if (theForm.name.value == ""){
                error += "Please enter your name\n\n";
        }

        if (theForm.email.value == ""){
                error += "Please enter your e-mail address\n\n";
        }
        if ((theForm.email.value.indexOf ('@',0) == -1 ||
                theForm.email.value.indexOf ('.',0) == -1) &&
                theForm.email.value != ""){
                error += "Please enter your full e-mail address\n\n";
        }

        if (theForm.comments.value == ""){
                error += "Please enter some comments\n\n";
        }

        if (error != ""){
                alert(error);
                return (false);
        } else {
                return (true);
        }
}

function paypalValidator(theForm){
        var error = "";
        var digits = "0123456789";
    
        if (theForm.hush_alias.value == ""){
                error += "Please enter your HushMail address to upgrade\n\n";
        }
        if ((theForm.hush_alias.value.indexOf ('@',0) == -1 ||
                theForm.hush_alias.value.indexOf ('.',0) == -1) &&
                theForm.hush_alias.value != ""){
                error += "Please enter your full HushMail address to upgrade\n\n";
        }
        if (error != ""){
                alert(error);
                return (false);
        } else {
                return (true);
        }
}

function egoldValidator(theForm){
        var error = "";
        var digits = "0123456789";
    
        if (theForm.hush_alias.value == ""){
                error += "Please enter your HushMail address to upgrade\n\n";
        }
        if ((theForm.hush_alias.value.indexOf ('@',0) == -1 ||
                theForm.hush_alias.value.indexOf ('.',0) == -1) &&
                theForm.hush_alias.value != ""){
                error += "Please enter your full HushMail address to upgrade\n\n";
        }
        if (error != ""){
                alert(error);
                return (false);
        } else {
                return (true);
        }
}


// subscription pages
function setItemDetails(paymentForm)
{
	var alias = paymentForm.elements['hush_username'].value;
	if ( alias == null || alias == "" )
	{
		alert("Please enter your Hushmail username");
		return false;
	}
	if ( alias.indexOf("@") == -1 )
	alias = alias + "@" +
	paymentForm.elements['hush_domain'].value;

	for (var i = 0; i < paymentForm.elements.length; i++)
	{
		if (paymentForm.elements[i].name ==
		"itemDetails[]")
		{
			paymentForm.elements[i].value =
			alias;
		}
	}
	return true;
}


function setItemQuantity(input,id) {
	var elem = id + 'Qnty';
	var qnty_old = document.getElementById(elem).value;
	var qnty_new;

	if (qnty_old == 0) {
		qnty_new = 1;
	} else {
		qnty_new = 0;
	}
	document.getElementById(elem).value = qnty_new;
}


function setItemGeneral(input,id,price) {
	document.getElementById(id).value = input.value;
	document.payment.elements[(id + 'Cost')].value = price;
}


function setTotal() {
	var costTotal = 0.00;
	var items = new Array(
		'Premium',
		'IMAP',
		'Storage',
		'Forms');

	for (var i = 0; i < items.length; i++) {
		var item = items[i];
		var itemQnty = document.getElementById((item + 'Qnty')).value;
		var itemCost = parseFloat(document.payment.elements[(item + 'Cost')].value);
		// alert(item + ' = ' + itemQnty);

		costTotal += (itemCost * itemQnty);
	}

	document.payment.itemTotal.value = costTotal.toFixed(2);
}



function affiliateValidator(theForm){
        var error = "";
        var digits = "0123456789";

        if (theForm.LastName.value == "" || theForm.FirstName.value == ""){
                error += "Please enter your full name\n\n";
        }

        if (theForm.Email.value == ""){
                error += "Please enter your e-mail address\n\n";
        } else {
		var goodEmail = theForm.Email.value.match(/\b(^(\S+@)\S+(\..{2,4})$)\b/gi);
		if (!goodEmail){
		   error += "Please enter a valid e-mail address\n\n";
		}
	}
        if (theForm.Address.value == "" || theForm.City.value == "" ||
		theForm.State.value == "" || theForm.Country.value == "" ||
		theForm.Zip.value == "" ){
                error += "Please enter your full postal address\n\n";
        }
        if (theForm.ChecksPayableTo.value == ""){
                error += "Please enter the name or company \ncheques should be issued to\n\n";
        }
        if (error != ""){
                alert(error);
                return (false);
        } else {
                return (true);
        }
}

function validateEmailAddress(emailAddress, failMsg)
{
	if (emailAddress.match(/\b(^(\S+@)\S+(\..{2,4})$)\b/gi))
	{
		return true;
	}
	alert (failMsg);
	return false;
}


function addHushIdentityFavorite()
{
	var checked = document.forms['hushid_login'].elements['chkFavorite'].checked;
	var username = document.forms['hushid_login'].elements['hush_username'].value;
	var domain = document.forms['hushid_login'].elements['hush_domain'].value;

	var favUrl = "https://www.hushmail.com/services.php?subloc=identity";
	var favTitle = "HushIdentity - ";

	if (ie && checked && document.all)
	{
		favUrl += "&hush_username=" + username;
		favUrl += "&hush_domain=" + domain;
		favTitle += username + "@" + domain;

		window.external.AddFavorite(favUrl, favTitle);
	}
}



function spawnNewWindow() {
	var string = "<b>Loading data...</b>"
	var NewWindow
	NewWindow = open('','affiliateResources','width=640,height=480,location=no,menubar=yes,resizable=yes,scrollbars=yes,status=no,toolbar=no') 
	NewWindow.document.write(string)
	NewWindow.document.focus()
	NewWindow.document.close()
}



function selectDomain(form,domain) 
{
	if ( document.forms[form] != null )
	{
		var dropdown = "hush_domain";
		var max = document.forms[form].elements[dropdown].options.length;
	
		for (var i = 0; i < max; i++)
		{
			value = document.forms[form].elements[dropdown].options[i].value;
			if (value == domain)
			{
				document.forms[form].elements[dropdown].selectedIndex = i;
			}
		}
	}
} 



function printJavaVersionApplet()
{
if ( navigator.appName == "Microsoft Internet Explorer" )
{
	document.write('<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" WIDTH="0" HEIGHT="0">');
}
else
{
	document.write('<applet code="JavaVersionApplet" width="0" height="0" mayscript>');
}
document.write('<PARAM NAME="CODE" VALUE="JavaVersionApplet">');
document.write('<PARAM NAME="CODEBASE" VALUE=".">');
document.write('<param name="type" value="application/x-java-applet;version=1.4">');
document.write('<param name="scriptable" value="true">');
if (navigator.appName == "Microsoft Internet Explorer" )
{
	document.write('</object>');
}
else
{
	document.write('</applet>');
}
}
