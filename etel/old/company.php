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
// viewcompany.php:	The admin page functions for viewing the company.
include("includes/sessioncheck.php");

require_once("../includes/function.php");
$headerInclude = "companies";
include("includes/header.php");
include("includes/message.php");
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";

?>
<script language="JavaScript">
function Displaycompany(){
	if(document.dates.companymode.value=="A") {
		document.getElementById('allC').style.display = "";
		document.getElementById('active').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.dates.companymode.value=="AC") {
		document.getElementById('active').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.dates.companymode.value=="NC") {
		document.getElementById('nonactive').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('active').style.display = "none";
	}
	document.getElementById('all').selectedIndex = 0;
	document.getElementById('activename').selectedIndex = 0;
	document.getElementById('nonactivename').selectedIndex = 0;

}
function validate() {
	if(document.dates.companyname.value=="" && document.dates.activecompanyname.value=="" && document.dates.nonactivecompanyname.value=="" ) {
	 alert("Please select the company.");
	 return false;
	}
}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center" >
    &nbsp;
		<table width="50%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Company List</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5">
		<form name="dates" action="viewCompanyNext.php"  method="get" onsubmit="return validate();"><input type="hidden" name="period"></input>
			<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">  
			<tr>
			 <td height="50" valign="middle" align="center" width="50%"><font face="verdana" size="1">Company Type&nbsp;:&nbsp;</font><select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 150px" onChange="Displaycompany();">
		<?php print func_select_companytype(); ?>
				</select>&nbsp;</td>
                </tr>
				<tr><td>
				<div id="allC" style="display:none">
				<table width="100%"><tr>
				<td   height="50"  valign="top" align="center" width="50%"><font face="verdana" size="1">Select Company&nbsp;:&nbsp;</font><select id="all" name="companyname" style="font-family:verdana;font-size:10px;WIDTH: 160px">
				<?php func_company_select_alltype('view');
				?>
				</select>
				</td></tr></table>
				</div>
				<div id="active" style="display:yes">
				<table width="100%"><tr>
				<td   height="50"  valign="top" align="center" width="50%"><font face="verdana" size="1">Select Company&nbsp;:&nbsp;</font><select id="activename" name="activecompanyname" style="font-family:verdana;font-size:10px;WIDTH: 160px">
				<?php func_company_select_activetype('view');			 
				?>
				 </select>
				 </td></tr></table>
				</div>
				<div id="nonactive" style="display:none">
				<table width="100%"><tr>
				<td   height="50"  valign="top" align="center" width="50%"><font face="verdana" size="1">Select 
				Company&nbsp;:&nbsp;</font> 
				<select id="nonactivename" name="nonactivecompanyname" style="font-family:verdana;font-size:10px;WIDTH: 160px">
				<?php func_company_select_nonactivetype('view');
				?>
				</select>
				</td></tr></table>
				</div>
				</td></tr>			 
		 <tr><td align="center">&nbsp;&nbsp;&nbsp;<input type="image" id="viewcompany" src="../images/view.jpg"></input></td></tr>
		</table>												
		</form>
	 </td>
	</tr>
	<tr>
	<td width="1%"><img src="../images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="../images/menubtmright.gif"></td>
	</tr>
	</table>
   </td>
  </tr>
</table>
<?php
include("includes/footer.php");
?>
