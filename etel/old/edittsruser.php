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
// AddCompanyUser.php:	This admin page functions for adding  the company user. 
include("includes/sessioncheck.php");

include("includes/header.php");
$headerInclude="tsr";
include("includes/topheader.php");
include("includes/message.php");
if(	$_SESSION["sessionlogin_type"] == "tele" ) {
	$sAddedUser = "T";
} else {
	$sAddedUser = "C";
}
$sessionlogintype =isset($HTTP_SESSION_VARS["sessionlogin_type"])?$HTTP_SESSION_VARS["sessionlogin_type"]:"";
$sessioncompanyid =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$msg = (isset($HTTP_GET_VARS["msg"])?trim($HTTP_GET_VARS["msg"]):"");
$sShowMessage = "";
if ( $msg == "add" ) {
	$sShowMessage = "TSR user added successfully";
}
if ( $msg == "edit" ) {
	$sShowMessage = "TSR user edited successfully";
}
?>
<script language="JavaScript">
function funcEdit(objForm) {
	
	if(objForm.cboTsr.selectedIndex == -1) {
		alert("please select a TSR user");
	}
	else
	{
		objForm.method	=	"post";
		objForm.action	=	"addtsruser.php?id="+objForm.cboTsr.options[objForm.cboTsr.selectedIndex].value;
		objForm.submit();
	}

}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="80%" >
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="75%">
      <tr>
        <td width="100%" height="22">&nbsp;
         
        </td>
      </tr>
      <tr>
        <td width="100%" valign="top" align="left">
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Modify/View TSR User</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
			<td class="lgnbd" width="987" colspan="5" align="center">
			<!-- Form starts -->
			<form name="frmTsr" method="post" action="#">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
					<tr>
						<td align="center">
							<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
								<tr bgcolor="#78B6C2">
									<td class="cl1"><span class='subhd'>No</span></td>
									<td class="cl1" height="30"><span class='subhd'>First Name</span></td>
									<td class="cl1"><span class='subhd'>Last Name</span></td>
									<td class="cl1"><span class='subhd'>User Name</span></td>
									<td class="cl1"><span class='subhd'>Password</span></td>
									<td class="cl1" align='right'><span class='subhd'>Amount ($)</span></td>
									<td class="cl1" align='right'><span class='subhd'>Voice Auth. Fee ($)</span></td>
									<td class="cl1"><span class='subhd'>Edit</span></td>
									<td class="cl1"><span class='subhd'>Delete</span></td>
								</tr>
						<?php
							$qrySelect = "select * from cs_tsrusers where tsr_added_user_id = $sessioncompanyid and tsr_added_by = '$sAddedUser'";
							if ( !($rstSelect = mysql_query($qrySelect,$cnn_cs))) {
								print("Can not execute query");
								exit();
							}
							for ( $iLoop = 0 ; $iLoop < mysql_num_rows($rstSelect) ; $iLoop++ ) {
								$iCount = $iLoop + 1;
								echo("<tr height='25'>");
								echo("<td class='cl1'><span class='maintx'>$iCount</span></td>");
								echo("<td class='cl1' height='20'><span class='maintx'>".mysql_result($rstSelect,$iLoop,3)."</span></td>");
								echo("<td class='cl1'><span class='maintx'>".mysql_result($rstSelect,$iLoop,4)."</span></td>");
								echo("<td class='cl1'><span class='maintx'>".mysql_result($rstSelect,$iLoop,5)."</span></td>");
								echo("<td class='cl1'><span class='maintx'>".mysql_result($rstSelect,$iLoop,6)."</span></td>");
								echo("<td class='cl1' align='right'><span class='maintx'>".mysql_result($rstSelect,$iLoop,7)."</span></td>");
								echo("<td class='cl1' align='right'><span class='maintx'>&nbsp;".mysql_result($rstSelect,$iLoop,8)."</span></td>");
								echo("<td class='cl1'><span class='maintx'><a href='addtsruser.php?id=".mysql_result($rstSelect,$iLoop,0)."'>Edit</a></span></td>");
								echo("<td class='cl1'><span class='maintx'><a href='deletetsruser.php?id=".mysql_result($rstSelect,$iLoop,0)."'>Delete</a></span></td>");
								echo("</tr>");
							}
						?>
						</table>
						</td>
					</tr>
				</table>			
			</form>
			<!-- Form ends here -->
			</td>
		</tr>
	<tr>
	<td width="1%"><img src="images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="images/menubtmright.gif"></td>
	</tr>
	</table>

	</td>
      </tr>
	 <tr><td>
	<br>
	
		</table>
		</td></tr>
    </table>
	   </td>
	</tr>
</table>


<?php
	include("includes/footer.php");
?>
