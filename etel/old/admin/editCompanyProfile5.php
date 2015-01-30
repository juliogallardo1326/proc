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
// editCompanyProfile5.php:	This admin page functions for editing the company details. 

$allowBank=true;
include("includes/sessioncheck.php");

$headerInclude = "companies";
include("includes/header.php");


include("includes/message.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$str_update = isset($HTTP_POST_VARS['update'])?$HTTP_POST_VARS['update']:"";
if($sessionAdmin!="")
{
if ($str_update == "yes") {
	$userid = (isset($HTTP_POST_VARS['userid'])?quote_smart($HTTP_POST_VARS['userid']):"");
	$str_wire_money = isset($HTTP_POST_VARS['wire_money'])?$HTTP_POST_VARS['wire_money']:"N";
	$str_merchant_contract = isset($HTTP_POST_VARS['merchant_contract'])?$HTTP_POST_VARS['merchant_contract']:"No";
	$str_merchant_application = isset($HTTP_POST_VARS['merchant_application'])?$HTTP_POST_VARS['merchant_application']:"0";
	$str_document_ids = isset($HTTP_POST_VARS['document_ids'])?$HTTP_POST_VARS['document_ids']:"";
	if ($str_document_ids != "") {
		$arr_document_ids = split(",", $str_document_ids);
		for ($i_loop=0;$i_loop<count($arr_document_ids);$i_loop++) {
			$i_document_id = $arr_document_ids[$i_loop];
			$str_status = isset($HTTP_POST_VARS["reject".$i_document_id])?$HTTP_POST_VARS["reject".$i_document_id]:"P";
			//print("status= $str_status");
			$str_reject_reason = isset($HTTP_POST_VARS["reject_reason".$i_document_id])?quote_smart($HTTP_POST_VARS["reject_reason".$i_document_id]):"";
			$str_query = "update cs_uploaded_documents set status = '$str_status', reject_reason = '$str_reject_reason' where file_id = $i_document_id";
			//print($str_query);
			//if ($adminInfo['li_level'] == 'full') 
			mysql_query($str_query) or dieLog(mysql_errno().": ".mysql_error()."<BR>");

			$i_num_documents = 0;
			$str_query = "select count(distinct(file_type)) from cs_uploaded_documents where user_id = $userid and status = 'A'";
			if(!($show_sql =mysql_query($str_query,$cnn_cs)))
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			}
			if(mysql_num_rows($show_sql)>0)
			{
				$i_num_documents = mysql_result($show_sql, 0, 0);
			}

			$str_query = "update cs_companydetails set num_documents_uploaded = $i_num_documents where userId = $userid";

			//if ($adminInfo['li_level'] == 'full') 
			mysql_query($str_query) or dieLog(mysql_errno().": ".mysql_error()."<BR>");


			//print($i_document_id." - ". $str_status ." - ". $str_reject_reason ."<br>");
		}
	}
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
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

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

		$myLicenceFileArray = array();
		$myArticlesFileArray = array();
		$myHistoryFileArray = array();
		$myContract = array();
		$str_document_ids = "";
		$str_qry = "select file_type, file_name, file_id, status, reject_reason from cs_uploaded_documents where user_id = $company_id";
		if(!($show_sql1 =mysql_query($str_qry,$cnn_cs))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		if(mysql_num_rows($show_sql1)>0) {
			while($showval1 = mysql_fetch_row($show_sql1)) {
				if ($showval1[0] == "License") {
					$myLicenceFileArray[] = $showval1[1] ."#!_" .$showval1[2] ."#!_" .$showval1[3] ."#!_" .$showval1[4];
				} else if ($showval1[0] == "Articles") {
					$myArticlesFileArray[] = $showval1[1] ."#!_" .$showval1[2] ."#!_" .$showval1[3] ."#!_" .$showval1[4];
				} else if ($showval1[0] == "History") {
					$myHistoryFileArray[] = $showval1[1] ."#!_" .$showval1[2] ."#!_" .$showval1[3] ."#!_" .$showval1[4];
				} else if ($showval1[0] == "Contract") {
					$myContract[] = $showval1[1] ."#!_" .$showval1[2] ."#!_" .$showval1[3] ."#!_" .$showval1[4];
				}
				$str_document_ids .= $showval1[2] .",";
			}
			$str_document_ids = substr($str_document_ids, 0, strlen($str_document_ids) - 1);
		}			
	 ?>
<script language="javascript" src="../scripts/general.js"></script>

<script language="javascript">
function showUploadWindow(documentType) {
	window.open ("uploadDocuments.php?document_type="+documentType+"&company=<?= $company_id?>",'',"'scrollbars=no,title=no,resizable=no,width=400, height=300'");
}
</script>
<table border="0" align="center" cellpadding="0" width="88%" cellspacing="0" height="80%">
  <tr>
       <td width="100%" valign="top" align="center"  >
    &nbsp;
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">View / Edit&nbsp; 
            Uploaded Documents</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">
	<form action="editCompanyProfile5.php" name="Frmcompany" method="post">
	<table style="margin-top:10" align="center">
	<tr>
	<td align="center">
	<a href="editCompanyProfile1.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/merchantdetails_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
	<a href="editCompanyProfile2.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/bankprocessing_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
	<a href="editCompanyProfile3.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/ratesandfees_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
	<IMG SRC="<?=$tmpl_dir?>/images/uploadown_tab1.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT="">
	<!--<a href="completeAccounting.php?company_id=<?= $company_id?>&script_display=<?= $script_display?>"><IMG SRC="<?=$tmpl_dir?>/images/completeaccounting_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>-->
	</td>
	</tr>
	</table>
	<input type="hidden" name="userid" value="<?=$showval[0]?>"></input>
	<input type="hidden" name="update" value="yes"></input>
	<input type="hidden" name="document_ids" value="<?= $str_document_ids?>"></input>
		<table width="98%" cellpadding="0" cellspacing="0" align="center" border="0">
		<tr>
		<td align="center" width="50%" valign="top">
			
		<table cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="center">
		  <tr> 
			<td align="center" valign="middle" height="30" width="25%" class='cl1' ><font face="verdana" size="1"><b>Document Type</b></font></td>
			<td align="center" valign="middle" height="30" width="8%" class='cl1' ><font face="verdana" size="1"><b>Upload</b></font></td>
			<td align="center" height="30" width="75%" class='cl1'>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr><td align='center' class='right'><font face="verdana" size="1"><b>Document Name</b></font></td>
				<td align='center' valign='middle' height='30' width='130' class='right'><font face='verdana' size='1'><b>Approval Status</b></font></td>
				<td align='center' valign='middle' height='30' width='150'><font face='verdana' size='1'><b>Comments</b></font></td></tr>
			</table>
			</td>
		  </tr>

		  <tr> 
			<td align="left" valign="middle" height="30" width="25%" class='cl1'><font face="verdana" size="1"><strong>Drivers 
			  License/Passport&nbsp;</strong></font></td>
			<td align='left' valign='middle' height='30' width='8%' class='cl1'>&nbsp;<a href="Javascript:showUploadWindow('License');"><font face='verdana'size='1'>Upload</font></a></td>
			<td align="left" height="30" width="75%" class='cl1'>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
<?php					for($i_loop=0;$i_loop<count($myLicenceFileArray);$i_loop++) {
				$str_document_details = split("#!_", $myLicenceFileArray[$i_loop]);
				print "<tr><td align='left' class='right'><a href='../gateway/".$_SESSION['gw_folder']."UserDocuments/License/$str_document_details[0]' target='_blank'><font face='verdana' size='1'>$str_document_details[0]</font></a>&nbsp;</td>";
				print "<td align='right' valign='middle' height='30' width='130' class='right'><font face='verdana' size='1'>Approve<input type='radio' name='reject$str_document_details[1]' value='A'"; print $str_document_details[2] == "A" ? "checked" : "";
				print ">&nbsp;Reject<input type='radio' name='reject$str_document_details[1]' value='R'";
				print $str_document_details[2] == "R" ? "checked" : "";
				print "></font></td>";
				print "<td align='right' valign='middle' height='30' width='150'><font face='verdana' size='1'><textarea name='reject_reason$str_document_details[1]' rows='2' cols='15'>".  $str_document_details[3] ."</textarea></font></td></tr>";
				
			}
?>						
			</table>
			</td>
		  </tr>
		  <tr> 
			<td align="left" valign="middle" height="30" width="25%" class='cl1'><font face="verdana" size="1"><strong>Articles 
			  of Incorporation&nbsp;</strong></font></td>
			<td align='left' valign='middle' height='30' width='8%' class='cl1'>&nbsp;<a href="Javascript:showUploadWindow('Articles');"><font face='verdana'size='1'>Upload</font></a></td>
			<td align="left" height="30" width="75%" class='cl1'>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
<?php					for($j_loop=0;$j_loop<count($myArticlesFileArray);$j_loop++) {
				$str_document_details = split("#!_", $myArticlesFileArray[$j_loop]);
				print "<tr><td align='left' class='right'><a href='../gateway/".$_SESSION['gw_folder']."UserDocuments/Articles/$str_document_details[0]' target='_blank'><font face='verdana' size='1'>$str_document_details[0]</font></a>&nbsp;</td>";
				print "<td align='right' valign='middle' height='30' width='130' class='right'><font face='verdana' size='1'>Approve<input type='radio' name='reject$str_document_details[1]' value='A'";
				print $str_document_details[2] == "A" ? "checked" : "";
				print ">&nbsp;Reject<input type='radio' name='reject$str_document_details[1]' value='R'";
				print $str_document_details[2] == "R" ? "checked" : "";
				print "></font></td>";
				print "<td align='right' valign='middle' height='30' width='150'><font face='verdana' size='1'><textarea name='reject_reason$str_document_details[1]' rows='2' cols='15'>". $str_document_details[3] ."</textarea></font></td></tr>";
			}
?>						
			</table>
			</td>
		  </tr>
		  <tr> 
			<td align="left" valign="middle" height="30" width="25%" class='cl1'><font face="verdana" size="1"><strong>Previous 
			  processing history / Bank Statement <br>
			  (if applicable) &nbsp;</strong></font></td>
			<td align='left' valign='middle' height='30' width='8%' class='cl1'>&nbsp;<a href="Javascript:showUploadWindow('History');"><font face='verdana'size='1'>Upload</font></a></td>
			<td align="left" height="30" width="75%" class='cl1'>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
<?php					for($k_loop=0;$k_loop<count($myHistoryFileArray);$k_loop++) {
				$str_document_details = split("#!_", $myHistoryFileArray[$k_loop]);
				print "<tr><td align='left' class='right'><a href='../gateway/".$_SESSION['gw_folder']."UserDocuments/History/$str_document_details[0]' target='_blank'><font face='verdana' size='1'>$str_document_details[0]</font></a>&nbsp;</td>";
				print "<td align='right' valign='middle' height='30' width='130' class='right'><font face='verdana' size='1'>Approve<input type='radio' name='reject$str_document_details[1]' value='A'";
				print $str_document_details[2] == "A" ? "checked" : "";
				print ">&nbsp;Reject<input type='radio' name='reject$str_document_details[1]' value='R'";
				print $str_document_details[2] == "R" ? "checked" : "";
				print "></font></td>";
				print "<td align='right' valign='middle' height='30' width='150'><font face='verdana' size='1'><textarea name='reject_reason$str_document_details[1]' rows='2' cols='15'>". $str_document_details[3] ."</textarea></font></td></tr>";
			}
?>						
		</table>
		</td>
		  </tr>
		 <tr> 
			<td align="left" valign="middle" height="30" width="25%" class='cl1'><font face="verdana" size="1"><strong>Contract&nbsp;</strong></font></td>
			<td  align='left' valign='middle' height='30' width='8%' class='cl1'>&nbsp;<a href="Javascript:showUploadWindow('Contract');"><font face='verdana' size='1'>Upload</font></a></td>
			<td align="left" height="30" width="75%" class='cl1'>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
<?php					for($l_loop=0;$l_loop<count($myContract);$l_loop++) {
				$str_document_details = split("#!_", $myContract[$l_loop]);
				print "<tr><td align='left' class='right'><a href='../gateway/".$_SESSION['gw_folder']."UserDocuments/Contract/$str_document_details[0]' target='_blank'><font face='verdana' size='1'>$str_document_details[0]</font></a>&nbsp;</td>";
				print "<td align='right' valign='middle' height='30' width='130' class='right'><font face='verdana' size='1'>Approve<input type='radio' name='reject$str_document_details[1]' value='A'";
				print $str_document_details[2] == "A" ? "checked" : "";
				print ">&nbsp;Reject<input type='radio' name='reject$str_document_details[1]' value='R'";
				print $str_document_details[2] == "R" ? "checked" : "";
				print "></font></td>";
				print "<td align='right' valign='middle' height='30' width='150'><font face='verdana' size='1'><textarea name='reject_reason$str_document_details[1]' rows='2' cols='15'>". $str_document_details[3] ."</textarea></font></td></tr>";
			}
?>						
			</table>
			</td>
		  </tr>
		<tr>
		<td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Merchant Contract Accepted
		  &nbsp;</font></strong></td>
		<td align="left" height="25" class='cl1'><font face="verdana" size="1"> 
		  <input type="checkbox" name="merchant_contract" class="normaltext" <?=$showval[50] == "Yes" ? "checked" : ""?> value="Yes">
		  &nbsp;</font></td>
		  <td class='cl1'>&nbsp;</td>
		</tr>
		<tr>
		<td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Completed Merchant Application?
		  &nbsp;</font></strong></td>
		<td align="left" height="25" class='cl1'><font face="verdana" size="1"> 
		  <input type="checkbox" name="merchant_application" class="normaltext" <?=$showval[77] == "1" ? "checked" : ""?> value="1">
		  &nbsp;</font></td>
		  <td class='cl1'>&nbsp;</td>
		</tr>
		</table>
		</td></tr></table>
		<center>
		<table align="center">
		<tr><td align="center" valign="center" height="30" colspan="2" ><a href=<?= $script_display == "yes" ? "editCompanyProfile4.php?company_id=". $company_id : "editCompanyProfile3.php?company_id=". $company_id  ?>><img  SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>&nbsp;&nbsp;<input type="image" id="modifycompany" SRC="<?=$tmpl_dir?>/images/modifycompanydetails.jpg"></input></td></tr>	
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