<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// editCompanyProfile4.php:	This admin page functions for editing the company details. 

$allowBank=true;
include("includes/sessioncheck.php");

$headerInclude = "companies";
include("includes/header.php");


include("includes/message.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$str_update =isset($HTTP_POST_VARS["update"])?$HTTP_POST_VARS["update"]:"";
if($sessionAdmin!="")
{
	if ($str_update == "yes") {
		$userid = (isset($HTTP_POST_VARS['userid'])?quote_smart($HTTP_POST_VARS['userid']):"");
		$strMerchantName = (isset($HTTP_POST_VARS['txtMerchantName'])?quote_smart($HTTP_POST_VARS['txtMerchantName']):"");
		$strTollFreeNumber = (isset($HTTP_POST_VARS['txtTollFreeNumber'])?quote_smart($HTTP_POST_VARS['txtTollFreeNumber']):"");
		$strRetrievalNumber = (isset($HTTP_POST_VARS['txtRetrievalNumber'])?quote_smart($HTTP_POST_VARS['txtRetrievalNumber']):"");
		$strSecurityNumber = (isset($HTTP_POST_VARS['txtSecurityNumber'])?quote_smart($HTTP_POST_VARS['txtSecurityNumber']):"");
		$strProcessor = (isset($HTTP_POST_VARS['txtProcessor'])?quote_smart($HTTP_POST_VARS['txtProcessor']):"");
		$txtPackagename = (isset($HTTP_POST_VARS['txtPackagename'])?quote_smart($HTTP_POST_VARS['txtPackagename']):"");
		$txtPackageProduct= (isset($HTTP_POST_VARS['txtPackageProduct'])?quote_smart($HTTP_POST_VARS['txtPackageProduct']):"");
		$txtPackagePrice= (isset($HTTP_POST_VARS['txtPackagePrice'])?quote_smart($HTTP_POST_VARS['txtPackagePrice']):"0");
		$txtRefundPolicy= (isset($HTTP_POST_VARS['txtRefundPolicy'])?quote_smart($HTTP_POST_VARS['txtRefundPolicy']):"");
		$txtDescription= (isset($HTTP_POST_VARS['txtDescription'])?quote_smart($HTTP_POST_VARS['txtDescription']):"");

		if($txtPackagePrice=="") 
			$txtPackagePrice=0;
		
		$qry_update_user  = " update cs_companydetails set merchantName='$strMerchantName',tollFreeNumber='$strTollFreeNumber',retrievalNumber='$strRetrievalNumber', ";
		$qry_update_user .= " securityNumber='$strSecurityNumber',processor='$strProcessor',";

		$qry_update_user .= "telepackagename = '$txtPackagename', telepackageprod = '$txtPackageProduct', telepackageprice = $txtPackagePrice, ";
		$qry_update_user .= "telerefundpolicy = '$txtRefundPolicy', teledescription = '$txtDescription' ";

		$qry_update_user .= "  where userId=$userid $bank_sql_limit";

		//if ($adminInfo['li_level'] == 'full') 
		mysql_query($qry_update_user) or dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}

	$company_id = isset($HTTP_GET_VARS['company_id'])?$HTTP_GET_VARS['company_id']:"";
	if ($company_id == "") {
		$company_id = (isset($HTTP_POST_VARS['userid'])?quote_smart($HTTP_POST_VARS['userid']):"");
	}
	$companyname = isset($HTTP_GET_VARS['companyname'])?$HTTP_GET_VARS['companyname']:"";
	$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"";
	$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"";
	
	$script_display ="";
	$qry_select_companies = "select * from cs_companydetails where userid=$company_id $bank_sql_limit";
	if($qry_select_companies != "")
	{
		if(!($show_sql =mysql_query($qry_select_companies)))
		{
			print(mysql_errno().": ".mysql_error()."<BR>");
			print("Cannot execute query");
			print($qry_select_companies);
			exit();
		}
	}
?>
	<?	
		if($showval = mysql_fetch_row($show_sql)) 
		{
			if($showval[7]=="") 
			{
				$state=str_replace("\n",",\t",$showval[12]);
			} 
			else 
			{
				$state=str_replace("\n",",\t",$showval[7]);
			}
			if($showval[27] == "tele") {
				$script_display ="yes";
				$sendecommerce_diplay = "none";
			}else {
				$script_display ="none";
				$sendecommerce_diplay = "yes";
			}
			if($showval[84] == 1) {
				$sendecommerce_checked = "checked";
			}else {
				$sendecommerce_checked = "";
			}
			
		 ?>

<script language="javascript" src="../scripts/general.js"></script>

<script language="javascript">


</script>
<table border="0" align="center" cellpadding="0" width="98%" cellspacing="0" height="80%">
  <tr>
       <td width="100%" valign="top" align="center"  >
    &nbsp;
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">View Edit&nbsp; 
            Letter Templates / Verification Script </span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">
	<form action="editCompanyProfile4.php"  name="Frmcompany" method="post">
	<table style="margin-top:10" align="center">
	<tr>
	<td align="center">
	<a href="editCompanyProfile1.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/merchantdetails_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
	<a href="editCompanyProfile2.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/bankprocessing_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
	<a href="editCompanyProfile3.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/ratesandfees_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
	<?= $script_display == "yes" ? "<IMG SRC='../images/lettertemplate_tab1.gif' WIDTH='128' HEIGHT='32' BORDER='0' ALT=''>" : "" ?>
	<a href="editCompanyProfile5.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/uploadown_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
	<!--<a href="completeAccounting.php?company_id=<?= $company_id?>&script_display=<?= $script_display?>"><IMG SRC="<?=$tmpl_dir?>/images/completeaccounting_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>-->
	</td>
	</tr>
	</table>
		  <input type="hidden" name="userid" value="<?=$showval[0]?>"></input>
		  <input type="hidden" name="update" value="yes"></input>
		<table width="98%" cellpadding="0" cellspacing="0" align="center" border="0">
		<tr>
		<td align="center" width="50%" valign="top">
			
		<table cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="center">
		  <tr> 
			<td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC" width="258"><font face="verdana" size="1" color="#FFFFFF"><strong>Letter 
			  template setup</strong>&nbsp;</font></td>
			<td height="30" class='cl1' align="left">&nbsp;</td>
			</tr>
		  
		  <tr> 
			<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Merchant 
			  Name</font></strong></td>
			<td height="30" class='cl1'> &nbsp;<input type="text" name="txtMerchantName" class="normaltext" style="width:200px" value="<?=$showval[13]?>">
			</td>
		  </tr>
		  <tr> 
			<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Toll 
			  Free Number</font></strong></td>
			<td height="30" class='cl1'>&nbsp;<input type="text" name="txtTollFreeNumber" class="normaltext" style="width:200px" value="<?=$showval[14]?>">
			</td>
		  </tr>
		  <tr> 
			<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Retrieval 
			  Number </font></strong></td>
			<td height="30" class='cl1'>&nbsp;<input type="text" name="txtRetrievalNumber" class="normaltext" style="width:200px" value="<?=$showval[15]?>">
			</td>
		  </tr>
		  <tr> 
			<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Security 
			  Number </font></strong></td>
			<td height="30" class='cl1'>&nbsp;<input type="text" name="txtSecurityNumber" class="normaltext" style="width:200px" value="<?=$showval[16]?>">
			</td>
		  </tr>
		  <tr> 
			<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Processor</font></strong></td>
			<td height="30" class='cl1'>&nbsp;<input type="text" name="txtProcessor" class="normaltext" style="width:200px" value="<?=$showval[17]?>">
			</td>
		  </tr>
		  </table>
		</td>

		<td align="center" width="50%" valign="top">
		<table cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="center"  width="90%">
			<td width="100%" colspan="2"> 
		<div id="script" style="display:<?=$script_display?>">
			<table width="100%" cellpadding="0" cellspacing="0" border="0"> 
			<tr> 
			<td height="30" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Verification 
			  Script </strong>&nbsp;</font></td>
			<td height="30" align="left" class='cl1'>&nbsp;</td>
			</tr>						
			<tr>
			<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Package 
			  Name &nbsp;</font></strong></td>
			<td align="left" height="30" class='cl1'>&nbsp;<input type="text" name="txtPackagename" class="normaltext" style="width:200px" value="<?=$showval[33]?>">
			</td>
			</tr>
			<tr>
			<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Package 
			  Product Service &nbsp;</font></strong></td>
			<td align="left" height="30" class='cl1'>&nbsp;<input type="text" name="txtPackageProduct" class="normaltext" style="width:200px" value="<?=$showval[34]?>">
			</td>
			</tr>
			<tr>
			<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Package 
			  Price &nbsp;</font></strong></td>
			<td align="left" height="30" class='cl1'>&nbsp;<input type="text" name="txtPackagePrice" class="normaltext" style="width:200px" value="<?=$showval[35]?>">
			</td>
			</tr>
			<tr>
			<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Refund 
			  Policy &nbsp;</font></strong></td>
			<td align="left" height="30" class='cl1'>&nbsp;<textarea name="txtRefundPolicy" class="normaltext" style="width:200px" rows="4" cols="30"><?=$showval[36]?></textarea>
			</td>
			</tr>
			<tr>
			<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Description 
			  &nbsp;</font></strong></td>
			<td align="left" height="30" class='cl1'>&nbsp;<textarea name="txtDescription" class="normaltext" style="width:200px" rows="4" cols="30"><?=$showval[37]?></textarea>	
			</td>
			</tr>
			</table>
			</div>
			</td></tr>	
			</table>
		</td></tr></table>
		<center>
		<table align="center">
		<tr><td align="center" valign="center" height="30" colspan="2" ><a href="editCompanyProfile3.php?company_id=<?= $company_id?>"><img  SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>&nbsp;&nbsp;<input type="image" id="modifycompany" SRC="<?=$tmpl_dir?>/images/modifycompanydetails.jpg"></input></td></tr>	
		</table>
		</center>
<?php 
		}
?>
        </form>
		</td>
	</tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
	</table><br>
    </td>
    </tr>
</table>
<?php
include("includes/footer.php");
}
?>