<?php 
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// addReseller.php:	This admin page functions for adding  the company user. 

require_once("includes/function.php");
$companyname = (isset($HTTP_GET_VARS['companyname'])?Trim($HTTP_GET_VARS['companyname']):"");
	if($companyname!="")
	{ 

		$qry_select="SELECT cs_companydetails.companyname,cs_companydetails.userId FROM cs_companydetails WHERE cs_companydetails.companyname ='$companyname' ";
		$result=mysql_query($qry_select,$cnn_cs);
	
		if(mysql_num_rows($result)>0)
		{	
			if($result)
			{
				$rs=mysql_fetch_array($result);
				$sgatewayCoName=$rs[0];
				$sgatewayUserId=$rs[1];
			}
			else
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			}
		}
		else{
		echo "Invalid Url..!";
		exit();
		}
	}
	else
	{
	echo(" Invalid Url..!");
	exit();
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta http-equiv="Content-Language" content="en-us">
<title></title>

<link href="styles/comp_set.css" type="text/css" rel="stylesheet">
<script language="javascript">

function validation(){

trimSpace(document.Frmcompany.companyname)
trimSpace(document.Frmcompany.username)
trimSpace(document.Frmcompany.password)
trimSpace(document.Frmcompany.password1)

   if(document.Frmcompany.companyname.value==""){
    alert("Please enter company name")
    document.Frmcompany.companyname.focus();
	return false;
  }

 if(document.Frmcompany.username.value==""){
    alert("Please enter the username")
    document.Frmcompany.username.focus();
	return false;
  }





if (document.Frmcompany.username.value!=""&&(!func_vali_pass(document.Frmcompany.username)))
		{
		
		alert("Special characters not allowed for username");
		document.Frmcompany.username.focus();
		document.Frmcompany.username.select();
		return false;
		}





  if(document.Frmcompany.password.value==""){
    alert("Please enter the password")
    document.Frmcompany.password.focus();
	return false;
  }


if (document.Frmcompany.password.value!=""&&(!func_vali_pass(document.Frmcompany.password)))
		{
		
		alert("Special characters not allowed for password");
		document.Frmcompany.password.focus();
		document.Frmcompany.password.select();
		return false;
		}




  if(document.Frmcompany.password1.value==""){
    alert("Please retype the password")
    document.Frmcompany.password1.focus();
	return false;
  }
  if(document.Frmcompany.password1.value != document.Frmcompany.password.value){
    alert("Please enter the correct password")
    document.Frmcompany.password1.focus();
	return false;
  }
   if(document.Frmcompany.email.value==""){
    alert("Please enter the email")
    document.Frmcompany.email.focus();
	return false;
  }
  if (document.Frmcompany.email.value  != "") 
	{
		if (document.Frmcompany.email.value .indexOf('@')==-1) 
		{
			alert("Please enter valid email id");
			document.Frmcompany.email.focus();
			return(false);
		}
	}
	
	if (document.Frmcompany.email.value  != "") 
	{
		if (document.Frmcompany.email.value .indexOf('.')==-1) 
		{
			alert("Please enter valid email id");
			document.Frmcompany.email.focus();
			return(false);
		}
	}
	
	if (document.Frmcompany.email.value.length > 100)
	{
		alert("Please enter email max upto 100 characters")
		document.Frmcompany.email.focus();
		return(false);
	}
  
  	if (document.Frmcompany.email.value !=document.Frmcompany.confirmation_email.value)
	{
		alert("Please enter the correct email id")
		document.Frmcompany.email.focus();
		return(false);
	}

  if(document.Frmcompany.rad_order_type.options[document.Frmcompany.rad_order_type.selectedIndex].value=="") {
  	alert("Please select the merchant type.");
	return false;
  }

  if(document.Frmcompany.merchant_voulme.value==""){
    alert("Please select the monthly volume.")
    document.Frmcompany.merchant_voulme.focus();
	return false;
  } 
  
  if(document.Frmcompany.how_about_us.options[document.Frmcompany.how_about_us.selectedIndex].value=="") {
  	alert("Please select from where you hear?");
	return false;
  }

  if((document.Frmcompany.how_about_us.value=='rsel' || document.Frmcompany.how_about_us.value=='other') && document.Frmcompany.reseller.value=='') {
		alert("Please enter the reseller / others details");
		document.Frmcompany.reseller.focus();
		return false;
   }
}

function SelectMerchanttype() {
	if(document.Frmcompany.how_about_us.value=='rsel' || document.Frmcompany.how_about_us.value=='other') {
		document.Frmcompany.reseller.disabled=false;
	}else {
		document.Frmcompany.reseller.disabled=true;
	}
}




function trimSpace(frmElement)
{
     var stringToTrim = eval(frmElement).value;
     var len = stringToTrim.length;
     var front;
     var back;
     for(front = 0; front < len && (stringToTrim.charAt(front) == ' ' || stringToTrim.charAt(front) == '\n' || stringToTrim.charAt(front) == '\r' || stringToTrim.charAt(front) == '\t'); front++);
     for(back = len; back > 0 && back > front && (stringToTrim.charAt(back - 1) == ' ' || stringToTrim.charAt(back - 1) == '\n' || stringToTrim.charAt(back - 1) == '\r' || stringToTrim.charAt(back - 1) == '\t'); back--);

     frmElement.value = stringToTrim.substring(front, back);
}

function func_vali_pass(frmelement)
{ 
 var invalid="!`~@#$%^&*()_-+={}[]|\"':;?/>.<,";
 var inp=frmelement.value;
 var b_flag=true;
for(var i=0;((i<inp.length)&&b_flag);i++)
{
var temp= inp.charAt(i);
var j=invalid.indexOf(temp);
if(j!=-1)
{
b_flag =false;
return false;
}
}
if (b_flag==true)return true;
}



  </script>
<style>
.Button
{
    BORDER-RIGHT: #D4D0C8 1px solid;
    BORDER-TOP: #D4D0C8 1px solid;
    BORDER-LEFT: #D4D0C8 1px solid;
    BORDER-BOTTOM: #D4D0C8 1px solid;
    FONT-SIZE: 8pt;
    FONT-FAMILY: verdana;
    COLOR: black;
	FONT-WEIGHT:bold;
    BACKGROUND-COLOR: #CCCCCC 
}
</style>

</head>

<body topmargin="0" leftmargin="0">
<table border="0" cellpadding="0" cellspacing="0" width="772" align="center" class="blkbd1">
  <tr>
    <td class="whitebtbd" ><img border="0" src="images/cards.gif" width="199" height="23"></td>
  </tr>
</table>


<table border="0" cellpadding="0" cellspacing="0" width="772" align="center" bgcolor="#658343" class="blkbd1">
  <tr>
    <td height="15" class="blackbtbd" bgcolor="#4A9FA6"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="772" align="center" class="blkbd1" height="460">
  <tr>
    <td height="25" valign="top" align="center" width="165" bgcolor="#FFFFFF">
      <table border="0" cellpadding="0" width="100%" height="249">
        <tr>
          <td width="99%" bgcolor="#B7D0DD" height="14"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
          <td width="1%" ><img border="0" src="images/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td width="99%" bgcolor="#85AFBC" height="16"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
          <td width="1%" ><img border="0" src="images/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td width="99%" bgcolor="#85AFBC" height="18"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
          <td width="1%" ><img border="0" src="images/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td width="100%" height="178" colspan="2" valign="top"><!--<img border="0" src="images/service.jpg" width="160" height="176">-->&nbsp;</td>
        </tr>
      </table>
    </td>
    <td height="25" valign="top" align="center">
&nbsp;
<table border="0" cellpadding="0" width="99%" cellspacing="0"  align="center" bgcolor="#3B6707">
<tr><td height="10px" bgcolor="#3D8287">&nbsp;</td></tr>
  <tr> 
    <td width="100%" valign="middle" align="center" bgcolor="#3D8287" > 
    <table width="98%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid white">
              <tr >
    <td valign="middle" height="40" bgcolor="#FFFFFF" align="center"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="350" height="80">
        <param name="movie" value="flash/applynow.swf">
        <param name="quality" value="high">
        <embed src="flash/applynow.swf" quality="high" pluginspage="https://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="350" height="80"></embed></object></td>
  </tr>

        <tr > 
          <td align="center" valign="middle" bgcolor="#3D8287" height="25" style="border-bottom:1px solid black;border-top:1px solid black"><font face="Verdana" size="1" color="white"><strong>TO 
            GET STARTED, PLEASE COMPLETE THE FOLLOWING PRE APPLICATION FORM.<strong></strong></strong></font></td>
        </tr>
		<tr > 
          <td align="center" valign="middle" bgcolor="#FFFFFF" height="25" style="border-bottom:1px solid black"> 
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="450" height="20">
              <param name="movie" value="flash/offshoremerchants.swf">
              <param name="quality" value="high">
              <embed src="flash/offshoremerchants.swf" quality="high" pluginspage="https://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="450" height="20"></embed></object> 
          </td>
        </tr>
        <tr> 
          <td class="lgnbd" bgcolor="#F7F7F7"> 
          <form action="addgatewaymerchant.php" method="post" onsubmit="return validation()" name="Frmcompany" >
            <input type="hidden" name="gateway" value="gateway">
			<input type="hidden" name="gatewayid" value="<?php echo $sgatewayUserId?>">
              <table height="100%" width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr> 
                  <td  width="100%" valign="center" align="center" >
                    &nbsp;
                    <table border="0" cellpadding="0" width="410" style="border:1px solid #50860F;background-color:white">
                         <tr>
                              <td align="right" valign="center" height="30" width="148"><font face="verdana" size="1">Company 
                                Name &nbsp;</font></td>
                        <td align="left" height="30" width="252"><input type="text" maxlength="100" name="companyname" style="font-family:arial;font-size:10px;width:240px"></td>
                      </tr>
                      <tr>
                              <td align="right" valign="center" height="30" width="148"><font face="verdana" size="1">User 
                                Name &nbsp;</font></td>
                        <td align="left" height="30" width="252"><input type="text" maxlength="30" name="username" style="font-family:arial;font-size:10px;width:240px" ></td>
                      </tr>
                      <tr>
                              <td align="right" valign="center" height="30" width="148"><font face="verdana" size="1">Password 
                                &nbsp;</font></td>
                        <td align="left" height="30" width="252"><input type="text" maxlength="30" name="password" style="font-family:arial;font-size:10px;width:240px"></td>
                      </tr>
					 <tr>
                              <td align="right" valign="center" height="30" width="153"><font face="verdana" size="1">Confirm 
                                Password &nbsp;</font></td>
                        <td align="left" height="30" width="252"><input type="text" maxlength="30" name="password1" style="font-family:arial;font-size:10px;width:240px"></td>
                      </tr>  
					   <tr> 
                              <td align="right" valign="center" height="19" width="153"><font face="verdana" size="1">Email 
                                &nbsp;</font></td>
                              <td align="left" height="19" width="252">
                                <input type="text" maxlength="100" name="email" style="font-family:arial;font-size:10px;width:240px"></td>
                            </tr>
                            <tr> 
                              <td align="right" valign="center" height="30" width="153"><font face="verdana" size="1">Confirm 
                                Email &nbsp;</font></td>
                              <td align="left" height="30" width="252"><input type="text" maxlength="100" name="confirmation_email" style="font-family:arial;font-size:10px;width:240px"></td>
                            </tr>                    
					  </table>
                    &nbsp;
                    
 						<table border="0" cellpadding="0"  height="100" width="410" style="border:1px solid #50860F;background-color:white">
                             
                      <tr>
                              <td align="right" valign="middle" height="30"  width="153"><font face="verdana" size="1">Merchant 
                                Type &nbsp;</font></td>
						  <td align="left" height="30"  width="252" valign="middle"><select name="rad_order_type" style="font-family:arial;font-size:10px;width:100px">
							<option value="">Select</option>
							<option value="ecom">General Ecommerce</option>
							<option value="trvl">Travel</option>
							<option value="phrm">Pharmacy</option>
							<option value="game">Gaming</option>
							<option value="adlt">Adult</option>
							<option value="tele">Telemarketing</option>
							<!--option value="crds">Card swipe</option-->
						  </select>
						  </td>
                      </tr>
                      <tr> 
                       <td align="right" valign="center" height="30"  width="153"><font face="verdana" size="1">Expected 
                                Monthly Volume &nbsp;</font></td>
                        <td align="left" height="30" width="252" >
						<select name="merchant_voulme" style="font-family:arial;font-size:10px;width:100px">
							<option value="">Select</option>
							<option value="0-$5,000">0-$5,000</option>
							<option value="$5,000-$10,000">$5,000-$10,000</option>
							<option value="$10,000-$25,000">$10,000-$25,000</option>
							<option value="$25,000-$50,000">$25,000-$50,000</option>
							<option value="$50,000-$100,000">$50,000-$100,000</option>
							<option value="$100,000-$250,000">$100,000-$250,000</option>
							<option value="$250,000-$500,000">$250,000-$500,000</option>
							<option value="$500,000-1MIL">$500,000-1MIL</option>
							<option value="1Mil-2Mil">1Mil-2Mil</option>
							<option value="2Mil-5Mil">2Mil-5Mil</option>
							<option value="5 Mil+">5 Mil+</option>
						  </select>
						</td>
                      </tr>
						<tr>
                              <td align="right" valign="middle" height="30" width="153"><font face="verdana" size="1">How 
                                you hear about us? &nbsp;</font></td>
						  <td align="left" height="30"  width="252" valign="middle"><select name="how_about_us" style="font-family:arial;font-size:10px;width:100px" onchange="SelectMerchanttype();">
							<option value="">Select</option>
							<option value="http://www.about.com">About.com</option>
							<option value="http://www.altavista.com">AltaVista</option>
							<option value="http://www.alltheweb.com">AllTheWeb.com</option>
							<option value="http://www.aolsearch.aol.com">AOL Search</option>
							<option value="http://www.askjeeves.com">Ask Jeeves</option>
							<option value="http://www.britannica.com">Britannica.com</option>
							<option value="http://www.excite.com">Excite</option>
							<option value="http://www.google.com">Google</option>
							<option value="http://www.hotbot.com">HotBot</option>
							<option value="http://www.inktomi.com">Inktomi</option>
							<option value="http://www.iwon.com">iWon</option>
							<option value="http://www.looksmart.com">LookSmart</option>
							<option value="http://www.lycos.com">Lycos</option>
							<option value="http://www.search.msn.com">MSN Search</option>
							<option value="http://www.search.netscape.com">Netscape Search</option>
							<option value="http://www.overture.com">Overture</option>
							<option value="http://www.searchking.com">SearchKing</option>
							<option value="http://www.teoma.com">Teoma</option>
							<option value="http://www.webcrawler.com">WebCrawler</option>
							<option value="http://www.wisenut.com">WiseNut</option>
							<option value="http://www.yahoo.com">Yahoo</option>
							<option value="rsel">Reseller</option>
							<option value="other">Others</option>
						  </select></td>
                      </tr>
					<tr> 
                      <td align="right" valign="center" height="30"  width="153"><font face="verdana" size="1">If reseller/others specify &nbsp; details&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="252" >
						<input type="text" maxlength="75" name="reseller" style="font-family:arial;font-size:10px;width:100px"></td>
                      </tr> 
					  <tr>
                        <td align="center" valign="center" height="30" colspan="2" width="404"><input type="submit" name="addcompany" value="Submit" class="Button"></td>
                      </tr>
                  <!--    <tr>
                        <td colspan="2" height="50" align="justify" valign="bottom"><font face="Verdana" size="1" color="#FF6600">"ALL 
                          YOUR DATA ENTRIES ARE ENCRYPTED AND TRANFERRED WITH 
                          SSL THROUGH OUR SECURED SERVER! SO YOU WILL BE SURE, 
                          THAT ONLY .com CAN READ YOUR CONFIDENTIAL DATA"</font></td>
                      </tr> -->
                    </table>
                    <br>
                    </td>
                </tr>
              </table></form>
              </td>
        </tr>
      </table>
      <br> </td>
  </tr>
</table>
<p>
    </td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="772" align="center" class="blkbd1" height="20">
  <tr>
    <td height="25" bgcolor="#2F6468"></td>
  </tr>
</table>
</body>

</html>
