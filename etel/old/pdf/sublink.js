w=(document.body.clientWidth/2)-390

if(w<0)
{
 w=0;
}

function openpop(ff)
{
var w=window.open("popup/"+ff+".htm",ff,"width=480, height=300, top=20, left=100, scrollbars=yes")
}  


function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}

//document.writeln("<span name='cast' id='cast' style='LEFT: 0px; POSITION: absolute; TOP: 0px; VISIBILITY: visible'>")
document.writeln('<div id="Layer1" style="LEFT: '+(w+226)+'px; POSITION: absolute; TOP: 147px; VISIBILITY: hidden; z-index:1"><table border="0" cellspacing="0" cellpadding="0" width="103"><tr>')
//document.writeln("<div name='drop' id='drop' style='LEFT: 91px; POSITION: absolute; TOP: 97px; VISIBILITY: hidden'>")
document.writeln("<td bgcolor='#ff9700'><table border='0' cellspacing='1' cellpadding='3' width='100%'>")
document.writeln("<tr><td align='left' valign='top' class='sublinks' bgcolor='#ffffff'><a href='#' class='black'>1st link</a></td></tr>")
document.writeln("<tr><td align='left' valign='top' class='sublinks' bgcolor='#ffffff'><a href='#' class='black'>2nd link</a></td></tr>")
document.writeln("<tr><td align='left' valign='top' class='sublinks' bgcolor='#ffffff'><a href='#' class='black'>3rd link</a></td></tr>")
document.writeln("</table>")
document.writeln("</td></tr></table>")
document.writeln("</div>")

document.writeln('<div id="Layer2" style="LEFT: '+(w+507)+'px; POSITION: absolute; TOP: 171px; VISIBILITY: hidden; z-index:1"><table border="0" cellspacing="0" cellpadding="0" width="130"><tr>')
//document.writeln("<div name='drop1' id='drop1' style='LEFT: 310px; POSITION: absolute; TOP: 97px; VISIBILITY: hidden'>")
document.writeln("<td bgcolor='#ff9700'><table border='0' cellspacing='1' cellpadding='3' width='100%'>")
document.writeln("<tr><td align='left' valign='top' class='sublinks' bgcolor='#ffffff'><a href='Demo/ecommercedemo.htm' class='black'>eCommerce Demo</a></td></tr>")
document.writeln("<tr><td align='left' valign='top' class='sublinks' bgcolor='#ffffff'><a href='Demo/teledemo.htm' class='black'>Telemarketing Demo</a></td></tr>")
document.writeln("</table>")
document.writeln("</td></tr></table>")

//document.writeln("<table border='0' cellspacing='0' cellpadding='0' width='350'><tr>")
//document.writeln("<td align='left' valign='top' class='sublinks'><img src='images/square.gif' align='absmiddle'>&nbsp;&nbsp;&nbsp;<a href='#' class='sublinks'>Help Desk</a> | <a href='#' class='sublinks'>FAq / KB</a></td>")
//document.writeln("</tr></table>")
document.writeln("</div>")

document.writeln('<div id="Layer3" style="LEFT: '+(w+397)+'px; POSITION: absolute; TOP: 97px; VISIBILITY: hidden; z-index:1"><table border="0" cellspacing="0" cellpadding="0" width="80"><tr>')
//document.writeln("<div name='drop2' id='drop2' style='LEFT: 396px; POSITION: absolute; TOP: 97px; VISIBILITY: hidden'>")
document.writeln("<td bgcolor='#ff9700'><table border='0' cellspacing='1' cellpadding='3' width='100%'>")
document.writeln("<tr><td align='left' valign='top' class='sublinks' bgcolor='#404740'><a href='about-us.htm' class='sublinks'>About Us</a></td></tr>")
document.writeln("<tr><td align='left' valign='top' class='sublinks' bgcolor='#404740'><a href='news-room.htm' class='sublinks'>News Room</a></td></tr>")
document.writeln("<tr><td align='left' valign='top' class='sublinks' bgcolor='#404740'><a href='contact-us.htm' class='sublinks'>Contact Us</a></td></tr>")
document.writeln("</table>")
document.writeln("</td></tr></table>")
//document.writeln("<table border='0' cellspacing='0' cellpadding='0' width='350'><tr>")
//document.writeln("<td align='left' valign='top' class='sublinks'><img src='images/square.gif' align='absmiddle'>&nbsp;&nbsp;&nbsp;<a href='#' class='sublinks'>News Room</a> | <a href='contact-us.htm' class='sublinks'>Contact Us</a></td>")
//document.writeln("</tr></table>")
document.writeln("</div>")

//document.writeln("</span>")

function validation(){
  var b_correct = true;
  if(document.Frmlogin.username.value==""){
    alert("Please enter the username");
    document.Frmlogin.username.focus();
	return false;  
  }
  if(document.Frmlogin.password.value==""){
    alert("Please enter the password");
	document.Frmlogin.password.focus();	
   	return false;
 }
  if(document.Frmlogin.securitycode.value==""){
    alert("Please enter the security code");
    document.Frmlogin.securitycode.focus();	
	return false;
  }
  return true;
}


function contact_validation() {
	if(document.contact_form.contact_help.value =="") {
		alert("Please select the contact type.");
		document.contact_form.contact_help.value="";
		return false;
	}
	
	if(document.contact_form.contact_company_name.value =="") {
		alert("Please enter the cmpany name.");
		document.contact_form.contact_company_name.focus();
		return false;
	}
	
	if(document.contact_form.contact_name.value =="") {
		alert("Please enter the contact name.");
		document.contact_form.contact_name.focus();
		return false;
	}
	
	if(document.contact_form.contact_email.value =="") {
		alert("Please enter the contact email.");
		document.contact_form.contact_email.focus();
		return false;
	}

   if (document.contact_form.contact_email.value  != "") 
	{
		if (document.contact_form.contact_email.value .indexOf('@')==-1) 
		{
			alert("Please enter valid contact email id");
			document.contact_form.contact_email.focus();
			return(false);
		}
	}
	
	if (document.contact_form.contact_email.value  != "") 
	{
		if (document.contact_form.contact_email.value .indexOf('.')==-1) 
		{
			alert("Please enter valid contact email id");
			document.contact_form.contact_email.focus();
			return(false);
		}
	}
	
	if (document.contact_form.contact_email.value.length > 100)
	{
		alert("Please enter email max upto 100 characters")
		document.contact_form.contact_email.focus();
		return(false);
	}

	if(document.contact_form.contact_email_confirm.value =="") {
		alert("Please enter the confirm email.");
		document.contact_form.contact_email_confirm.focus();
		return false;
	}

	if(document.contact_form.contact_email.value != document.contact_form.contact_email_confirm.value) {
		alert("Please enter the correct confirm email.");
		document.contact_form.contact_email_confirm.focus();
		return false;
	}
	
	if(document.contact_form.contact_phone.value =="") {
		alert("Please enter the contact phone.");
		document.contact_form.contact_phone.focus();
		return false;
	}
	

}