<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-Consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// viewApplicationStatus.php:	This admin page functions for displaying the company details. 

include ("includes/sessioncheck.php");
$headerInclude="merchant";
include("includes/header.php");
require_once("../includes/function.php");
include("includes/message.php");
require_once("../includes/completion.php");

if($_REQUEST['cd_completion']!=-2) $cd_completion = "and cd_completion=".intval($_REQUEST['cd_completion']);

$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";
if($resellerLogin!="")
{
	$str_active_color = "#000000";
//	$str_non_active_color = "#000000";
	$str_non_active_color = "#444444";
	$chk_profes_button ="No";
	$chk_license_button ="No";
	$chk_articles_button ="No";
	$chk_history_button ="No";
	
	$companytrans_type = isset($HTTP_GET_VARS['merchant_type'])?$HTTP_GET_VARS['merchant_type']:"";
if($companytrans_type=="A") {
	$companyid = isset($HTTP_GET_VARS['nonactive_merchants'])?quote_smart($HTTP_GET_VARS['nonactive_merchants']):"";
	if($companyid=="A") {
		$qry_select_companies = "select * from cs_companydetails where  reseller_id=$resellerLogin $cd_completion order by transaction_type asc, date_added desc";
	} else {
		$qry_select_companies = "select * from cs_companydetails where  reseller_id=$resellerLogin and userId=$companyid $cd_completion order by transaction_type asc, date_added desc";
	}
} else if($companytrans_type !="")  {
	$companyid = isset($HTTP_GET_VARS['nonactive_merchants'])?quote_smart($HTTP_GET_VARS['nonactive_merchants']):"";
	if($companyid=="A") {
		$qry_select_companies = "select * from cs_companydetails where  reseller_id=$resellerLogin $cd_completion and transaction_type='$companytrans_type' order by  date_added desc";
	} else {
		$qry_select_companies = "select * from cs_companydetails where  reseller_id=$resellerLogin and userId=$companyid $cd_completion and transaction_type='$companytrans_type' order by  date_added desc";
	}
} else {
	$qry_select_companies = "select * from cs_companydetails where  reseller_id=$resellerLogin $cd_completion order by transaction_type asc, date_added desc";
}

//	print $qry_select_companies;
	if($qry_select_companies != "")
	{
		if(!($show_sql =mysql_query($qry_select_companies)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>$qry_select_companies");

		}
	} else {
		$msgtodisplay="Select a company name";
		$outhtml="y";
		message($msgtodisplay,$outhtml,$headerInclude);					
		exit();
	}
?>
<script language="javascript">
function emailsubmit() {
	//document.Frmcompany.action="viewBottom.php";
	document.Frmcompany.method="POST";
	document.Frmcompany.submit();
}	
function openWindow(company){
   advtWnd=window.open("updateMonthlyVolume.php?companyId="+company,"advtWndName","'status=1,scrollbars=1,width=300,height=175,left=0,top=0'");
   advtWnd.focus();

}

</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
       <td width="95%" valign="top" align="center"  >
    &nbsp;
	<table width="90%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">View&nbsp; 
            Details </span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">
	<form action="viewCompanyNext.php" name="Frmcompany" method="post">
<?	
		if(mysql_num_rows($show_sql)>0)
		{
?>		<table cellspacing="0" cellpadding="0" style="margin-top:5" width="100%">
		<tr>
		<td align="center">&nbsp;</td>
		</tr>
		</table>
		<table class='lefttopright' cellpadding='0' cellspacing='0' width='95%'  valign="center" align="center"  style='margin-top: 5; margin-bottom: 5;margin-left: 8;margin-right: 5;'>
		<tr height='30' bgcolor='#CCCCCC'>
		<td align='center' width='100' class='cl1'><span class="subhd">Company Name</span></td>
		 <td align='center' width='180' class='cl1'><span class="subhd">URL</span></td>
		 <td align='center' width='150' class='cl1'><span class="subhd">Total Monthly Volume</span></td>
	 	 <td align='center' width='80' class='cl2'><span class="subhd">Status </span></td> 
		 <td align='center' width='80' class='cl2'><span class="subhd">Drivers License/Passport </span></td> 
	 	 <td align='center' width='80' class='cl2'><span class="subhd">Articles of Incorporation</span></td> 
	 	 <td align='center' width='80' class='cl2'><span class="subhd">Previous processing history / Bank Statement (if applicable)  </span></td> 
	 	 <td align='center' width='80' class='cl2'><span class="subhd">Contract</span></td> 
	 	 <td align='center' width='80' class='cl2'><span class="subhd">Discount Rate</span></td> 
	 	 <td align='center' width='80' class='cl2'><span class="subhd">Transaction Fee</span></td> 
		 <td align='center' width='200' class='cl2'><span class="subhd">Signup Date & Time</span></td>
		 <td align='center' width='200' class='cl2'><span class="subhd">Change Monthly Voulme</span></td>
		 <td align='center' width='200' class='cl2'><span class="subhd">Resend Email</span></td>
		 </tr>
<?php		
			$i_num_documents=0;
			while($showval = mysql_fetch_array($show_sql)) 
			{
					$chk_wire_money ="No";
					$chk_license_button ="Not Uploaded";
					$chk_articles_button ="Not Uploaded";
					$chk_history_button ="Not Uploaded";
					$chk_profes_button ="Not Uploaded";
				$qrt_uploaded_docu = "Select file_type,status from cs_uploaded_documents where user_id= $showval[0]";
				if(!($show_uploaded_sql =mysql_query($qrt_uploaded_docu)))
				{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

				}else {
					while($show_uploaded_val = mysql_fetch_row($show_uploaded_sql)) 
					{
						$status = "/Not Yet Approved";
						if($show_uploaded_val[1]=="A") 
							$status = "/Approved";
						else if($show_uploaded_val[1]=="R") 
							$status = "/Declined";
						if($showval['completed_uploading']) {
							$chk_wire_money ="Yes";
						} 
						if($show_uploaded_val[0] =="License") {
							$chk_license_button ="Uploaded".$status;
						} else if($show_uploaded_val[0] =="Articles") {
							$chk_articles_button ="Uploaded".$status;
						} else if($show_uploaded_val[0]=="History") {
							$chk_history_button ="Uploaded".$status;
						} else if($show_uploaded_val[0]=="Contract") {
							$chk_profes_button ="Uploaded".$status;
						} 
					}
				}
	
				
				$i_num_documents = $showval[78];
				
				if ($showval[50] == "Yes" && $showval[77] == 1 && $i_num_documents == 4) {
						$i_num_documents += 2;
				}
				if($showval[28] == 0) { 
					$str_bg_color = $str_non_active_color;
				} else { 
					$str_bg_color = $str_active_color;	
				}
				if($showval[77] == 1){
					//$chk_license_button ="Yes";
					//$chk_articles_button ="Yes";
					//$chk_history_button ="Yes";
					//$chk_profes_button ="Yes";
				}
?>
			<tr height='30'>
                  <td align='center' class='cl1'><font face='verdana' size='1' color="<?=$str_bg_color?>">&nbsp;<strong>
                    <?=$showval[3]?>
                    </strong></font></td>
			<td align='center' class='cl1'>&nbsp;<font face='verdana' size='1' color="<?= $str_bg_color ?>"><?= $showval[43] == "" ? "" : "$showval[43]";?><?= $showval[44] == "" ? "" : "<br>$showval[44]";?><?= $showval[45] == "" ? "" : "<br>$showval[45]";?></font></td>
			<td align='center' class='cl1'>&nbsp;<font face='verdana' size='1' color="<?= $str_bg_color ?>"><?=$showval[30]?></font></td>
			<td align='center' class='cl1'>&nbsp;<font face='verdana' size='1' color="<?= $str_bg_color ?>" style='<?=$etel_completion_array[$showval['cd_completion']]['style']?>'><?=$etel_completion_array[$showval['cd_completion']]['txt']?></font></td> 
<!--			<td align='center' class='cl1' >&nbsp;<font face='verdana' size='1' color="<?= $str_bg_color ?>"><?= $i_num_documents == 6 ? "Completed" : "Not Completed"?>
			</font></td> -->
			<td align='center' class='cl1' >&nbsp;<font face='verdana' size='1' color="<?= $str_bg_color ?>"> <?=$chk_license_button?></font></td>
			<td align='center' class='cl1' >&nbsp;<font face='verdana' size='1' color="<?= $str_bg_color ?>"> <?=$chk_articles_button?></font></td>
			<td align='center' class='cl1' >&nbsp;<font face='verdana' size='1' color="<?= $str_bg_color ?>"> <?=$chk_history_button?></font></td>
			<td align='center' class='cl1' >&nbsp;<font face='verdana' size='1' color="<?= $str_bg_color ?>"> <?=$chk_profes_button?></font></td>
			<td align='center' class='cl1' >&nbsp;<font face='verdana' size='1' color="<?= $str_bg_color ?>">Reseller:<?=$showval['cc_reseller_discount_rate']?>%<BR><?=$_SESSION['gw_title']?>:<?=$showval['cc_total_discount_rate']?>%<BR>Merchant:<?=$showval['cc_merchant_discount_rate']?>%</font></td>
			<td align='center' class='cl1' >&nbsp;<font face='verdana' size='1' color="<?= $str_bg_color ?>">Reseller:<?=$showval['cc_reseller_trans_fees']?><BR><?=$_SESSION['gw_title']?>:<?=$showval['cc_total_trans_fees']?><BR>Merchant:<?=$showval['cc_merchant_trans_fees']?></font></td>
			<td align='center' class='cl1' >&nbsp;<font face='verdana' size='1' color="<?= $str_bg_color ?>"><?=$showval[46] == "0000-00-00 00:00:00" ? "Not Available" : func_get_date_time_12hr($showval[46])?></font></td>
			<td align='center' class='cl1' >&nbsp;<font face='verdana' size='1' color="<?= $str_bg_color ?>"><a href="#" onClick="openWindow(<?=$showval[0]?>);">Edit</a></font></td>
			<td align='center' class='cl1' ><font face='verdana' size='1' color="<?= $str_bg_color ?>"><a href="sendMerchantEmail.php?companyId=<?=$showval[0]?>">Resend Referral Email</a></font></td>
			</tr>
<?		
			}
?>			</table>
 <?php 		
		}
		else
		{
	?>
			<p align="center">
			<font face="verdana" size="1" style="margin-left:30"><b>No Companies to display</b></font>
			</p>
	<?
		}
	?>
		<center>
		<table align="center"  ><tr>
		<td align="center" valign="center" height="30" colspan="2"><a href="#" onclick="window.history.back()"><img  id="emailr" src="../images/back.jpg" border="0"></a></td></tr>	
		</table></center>
	  
    </form>
		</td>
	</tr>
	<tr>
	<td width="1%"><img src="../images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="../images/menubtmright.gif"></td>
	</tr>
	</table><br>
    </td>
    </tr>
</table>
<?php
include("includes/footer.php");
}
?>