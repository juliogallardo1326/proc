<?php 
//******************************************************************//
//  This file is part of the Zerone-consulting development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// viewReseller.php:	This admin page functions for adding  the company user. 
include("includes/sessioncheck.php");

$headerInclude="reseller";
include("includes/header.php");
include("includes/message.php");
?>
<script language="Javascript">
function deleteReseller(resellerId) {
	if (window.confirm("Do you want to delete this Reseller?")) {
		window.location = "deleteReseller.php?reseller_id="+resellerId;
	}
}
</script>
<?
$qry_select_concat="";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";

	$return_reseller_id = "";
	$reseller_id = isset($HTTP_GET_VARS["reseller_id"])?$HTTP_GET_VARS["reseller_id"]:"";
	$return_id	= isset($HTTP_GET_VARS["return_reseller_id"])?$HTTP_GET_VARS["return_reseller_id"]:"";
	if($reseller_id =="") {
		$reseller_id = split(",",$return_id);
	}
	
	if($reseller_id[0] =="A") {
		$qry_select = "select reseller_id, reseller_companyname, reseller_contactname, reseller_username, reseller_password, reseller_date_added from cs_resellerdetails where 1 order by reseller_companyname";
		$return_reseller_id = "A";
	}else {
		for($i=0;$i<count($reseller_id);$i++) {
			if($qry_select_concat =="") {
				$qry_select_concat = "reseller_id=$reseller_id[$i]";	
			} else {
				$qry_select_concat .= " or reseller_id=$reseller_id[$i]";	
			}
			if($return_reseller_id == "") {
				$return_reseller_id = $reseller_id[$i];
			} else {
				$return_reseller_id .= ",". $reseller_id[$i];			
			}
		}
		$qry_select = "select reseller_id, reseller_companyname, reseller_contactname, reseller_username, reseller_password, reseller_date_added from cs_resellerdetails where $qry_select_concat";	
	}
	
	$rst_select = mysql_query($qry_select,$cnn_cs);
	if (mysql_num_rows($rst_select) == 0) {
	?>
		<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
		<tr>
		<td width="83%" valign="top" align="center">&nbsp;
		<table border="0" cellpadding="0" cellspacing="0" width="50%" >
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Message</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
		<form>
		<table height='50%' width='90%' cellspacing='0' cellpadding='0'>
		<tr><td  width='100%'  align='center'><p><font face='verdana' size='3'></font></p>
		<table width='400' border='0' cellpadding='0' height="100">
		<tr><td align='CENTER' valign='center' height='50' ><font face='verdana' size='1'><?php print "No Reseller To display" ?></font>
		</td></tr></table></td></tr>
		</table>
		<tr>
		<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
		</tr>
		</form>
		</td></tr>
		</table>
		</td></tr>
		</table>
		<?php
		include("includes/footer.php");
		exit();
	} else {
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" height="60%" >
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
		<table border="0" cellspacing="0" cellpadding="0" height="100%">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">View Resellers</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5">
	<form name="adduser" action="addcallcenteruserfb.php"  method="post" onsubmit="javascript:return validation();">
	 <table width="100%" valign="top" align="left" class="lgnbd" cellspacing="0" cellpadding="0" border="0">
	 <tr>
		<td valign="middle" class="ltbtbd"  colspan="9">&nbsp;</td>
	 </tr>
	 <tr bgcolor="#CCCCCC" height="25">
		<td class="cl1"><span class="subhd">&nbsp;No.</span></td>
		<td class="cl1"><span class="subhd">&nbsp;Company Name</span></td>
		<td class="cl1"><span class="subhd">&nbsp;Contact Name</span></td>
		<td class="cl1"><span class="subhd">&nbsp;Username</span></td>
		<td class="cl1"><span class="subhd">&nbsp;Password</span></td>
		<td class="cl1"><span class="subhd">&nbsp;Date Added</span></td>
		<td class="cl1"><span class="subhd">&nbsp;Edit</span></td>
		<td class="cl1"><span class="subhd">&nbsp;Delete</span></td>
	</tr>
<?php
		for($i=0;$i<mysql_num_rows($rst_select);$i++)
		{
		 
			$i_reseller_id = mysql_result($rst_select,$i,0);
			$str_reseller_companyname = mysql_result($rst_select,$i,1);
			$str_contactname = mysql_result($rst_select,$i,2);
			$str_username = mysql_result($rst_select,$i,3);
			$str_password = mysql_result($rst_select,$i,4);
			$str_dateadded = mysql_result($rst_select,$i,5);
	?>
			<tr height="25">
				<td valign="middle" class='cl1'><font face="verdana" size="1">&nbsp;<?=$i+1?></font></td>
				        <td valign="middle" class="cl1" >&nbsp;<a class="link" href="viewResellerProfile.php?reseller_id=<?=$i_reseller_id?>&returnid=<?=$return_reseller_id?>"><font face="verdana" size="1"> 
                          <?=$str_reseller_companyname?>
                          </font></a> <a class="link" href="viewresell_merchants.php?reseller_id=<?=$i_reseller_id?>"><font face="verdana" size="1"> (View 
                          Merchants)</font></a></td>
				<td valign="middle" class="cl1"><font face="verdana" size="1">&nbsp;<?=$str_contactname?></font></td>
				<td valign="middle" class="cl1"><font face="verdana" size="1">&nbsp;<?=$str_username?></font></td>
				<td valign="middle" class="cl1"><font face="verdana" size="1">&nbsp;<?=$str_password?></font></td>
				<td valign="middle" class="cl1"><font face="verdana" size="1"><?=date("F j, Y <br> g:i a",strtotime($str_dateadded))?></font></td>
				<td valign="middle" class="cl1"><font face="verdana" size="1">&nbsp;<a href="modifyReseller.php?reseller_id=<?=$i_reseller_id?>&returnid=<?=$return_reseller_id?>">Edit</a></font></td>
				<td valign="middle" class="cl1"><font face="verdana" size="1">&nbsp;<a href="#" onClick="Javascript:deleteReseller('<?=$i_reseller_id?>');">Delete</a></font></td>
			</tr>
<?php 	}
	}
?>
	<tr>
		<td height="40" valign="middle" class="ltbtbd" colspan="9" align="center"><a href="viewSelectReseller.php"><img SRC="<?=$tmpl_dir?>/images/back.gif" border="0"></a></td>
	 </tr>
	</table>	
	</form>
	</td>
	</tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
	</table>

	</td>
    </tr>
	 </table>
	 </td>
	</tr>
</table>

<?php
include("includes/footer.php");
?>