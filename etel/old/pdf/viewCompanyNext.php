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
// viewcompanyNext.php:	This admin page functions for displaying the company details. 
include("includes/sessioncheck.php");

include("includes/header.php");
$headerInclude = "companies";
require_once("../includes/function.php");

	die("You may not view this page.");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{
	$str_active_color = "green";
//	$str_active_color = "red";
	$str_non_active_color = "red";
	$emailsingle = (isset($HTTP_POST_VARS['emailsingle'])?Trim($HTTP_POST_VARS['emailsingle']):"");
	$username = (isset($HTTP_POST_VARS['username'])?Trim($HTTP_POST_VARS['username']):"");
	$password = (isset($HTTP_POST_VARS['password'])?Trim($HTTP_POST_VARS['password']):"");
	$email = (isset($HTTP_POST_VARS['email'])?Trim($HTTP_POST_VARS['email']):"");
	$companyname = isset($HTTP_GET_VARS['companyname'])?$HTTP_GET_VARS['companyname']:"";
	$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"";
	$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?Trim($HTTP_GET_VARS['companytrans_type']):"";

if($emailsingle !="")
	{
		$headers = "";
		$headers .= "From: Companysetup <sales@etelegate.com>\n"; // <admin@companysetup.com>\n";
		$headers .= "X-Sender: Admin Companysetup\n"; 
		$headers .= "X-Mailer: PHP\n"; // mailer
		$headers .= "X-Priority: 1\n"; // Urgent message!
		$headers .= "Return-Path: <sales@etelegate.com>\n"; // <admin@companysetup.com>\n";  // Return path for errors
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n"; // Mime type
		$emailmessage ="<br>Find the details for accessing the site.<table style='border:1px solid #d1d1d1;width:150px'><tr><td>Username : $username </td></tr><tr><td>Password : $password</td></tr></table>";
		mail($email,"Company user details",$emailmessage,$headers);
		$msgtodisplay="Email Send to the Company.";
		$outhtml="y";
		message($msgtodisplay,$outhtml,$headerInclude);					
		exit();
	}
	$qry_select_companies = "";
	if($companyname != "")
	{
		if($companyname== "A" && $companytype== "A") 
		{
			if ($companytrans_type == "A") {
				$qry_select_companies = "select * from cs_companydetails ";
			} else {
				$qry_select_companies = "select * from cs_companydetails where transaction_type = '$companytrans_type' ";
			}
		}
		else if($companyname== "A"  && $companytype== "AC") 
		{
			if ($companytrans_type == "A") {
				$qry_select_companies = "select * from cs_companydetails where activeuser=1 ";
			} else {
				$qry_select_companies = "select * from cs_companydetails where transaction_type = '$companytrans_type' and activeuser=1 ";
			}
		}
		else if($companyname== "A" &&  $companytype== "NC") 
		{
			if ($companytrans_type == "A") {
				$qry_select_companies = "select * from cs_companydetails where activeuser=0 ";
			} else {
				$qry_select_companies = "select * from cs_companydetails where transaction_type = '$companytrans_type' and activeuser=0 ";
			}
		}
		else if($companyname== "A" &&  $companytype== "RE") 
		{
			if ($companytrans_type == "A") {
				$qry_select_companies = "select * from cs_companydetails where reseller_id <> '' ";
			} else {
				$qry_select_companies = "select * from cs_companydetails where transaction_type = '$companytrans_type' and reseller_id <> '' ";
			}
		}
		else if($companyname== "A" &&  $companytype== "ET") 
		{
			if ($companytrans_type == "A") {
				$qry_select_companies = "select * from cs_companydetails where reseller_id is null ";
			} else {
				$qry_select_companies = "select * from cs_companydetails where transaction_type = '$companytrans_type' and reseller_id is null ";
			}
		}
		 else 
		{
			$qry_select_companies = "select * from cs_companydetails where userid=$companyname";
		}
		$sUploadedAllDocument 	=	(isset($HTTP_GET_VARS["chkUploadedDocuments"])?trim($HTTP_GET_VARS["chkUploadedDocuments"]):"");
		$sCompletedApplication 	=	(isset($HTTP_GET_VARS["chkCompletedApplication"])?trim($HTTP_GET_VARS["chkCompletedApplication"]):"");
		$sReadyToWire			=	(isset($HTTP_GET_VARS["chkReadyToWire"])?trim($HTTP_GET_VARS["chkReadyToWire"]):"");
		$sExtra					=	"";
		if ( $sUploadedAllDocument != "" ) {
			$sExtra	.=	" num_documents_uploaded = 4 ";
		}
		if ( $sCompletedApplication != "" ) {
			$sExtra	.= (($sExtra == "")?"  completed_merchant_application = 1 ":" or completed_merchant_application = 1 ");
		}	
		if ( $sReadyToWire != "" ) {
			$sExtra	.= (($sExtra == "")?"  completed_uploading  = 'Y' ":" or completed_uploading  = 'Y' ");
		}
		
		
		if ( $sExtra != "" ) {
			if ( substr_count($qry_select_companies," where ")>0) {
				$qry_select_companies	.=	" and ".$sExtra;
			} else {
				$qry_select_companies	.=	" where ".$sExtra;
			}
		}	
		$qry_select_companies	.=	" order by transaction_type asc, date_added desc ";	
		
		if($qry_select_companies != "")
		{
			if(!($show_sql =mysql_query($qry_select_companies)))
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			}
		}

	}
	else
	{
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

function func_delete(iUserId)
{
	if(confirm("Are you sure you want to delete the Company?"))
	{
		window.location="deleteCompanyProfile.php?company_id="+iUserId;
	}
}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
	<table width="60%" border="0" cellspacing="0" cellpadding="0">
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
	if($companyname== "A") 
	{
?>
<!--	<table cellspacing="0" cellpadding="0" style="margin-top:5" width="95%">
	<tr>
	<td align="left">&nbsp;&nbsp;&nbsp;<font face="verdana" size="2"><b><font color="<?= $str_active_color ?>">Active</font>&nbsp;&nbsp;<font color="<?= $str_non_active_color ?>">Non-Active</font></b></font>
	</td>
	</tr>
	</table>
-->
<?
		$arr_merchant_types = array("Adult", "Card Swipe", "Ecommerce", "Gaming", "Gateway", "Pharmacy", "Telemarketing", "Travel");
		if(mysql_num_rows($show_sql)>0)
		{
			$i_active = 0;
			$i_non_active = 0;
			$str_merchant_name = "";
			$str_prev_merchant_name = "-1";
			$str_bg_color = "";
			//$i_type = -1;
			while($showval = mysql_fetch_row($show_sql)) 
			{
				$i_num_documents = $showval[78];
				$company_id = $showval[0];

				if ($showval[50] == "Yes" && $showval[77] == 1 && $i_num_documents == 4) {
					$i_num_documents += 2;
				}
				$str_merchant_name = func_get_merchant_name($showval[27]);
				if($showval[28]==1) 
				{
					$str_bg_color = $str_active_color;
				} 
				else 
				{
					$str_bg_color = $str_non_active_color;
				}
				if ($str_merchant_name != $str_prev_merchant_name) {
					$i_active = 0;
					$i_non_active = 0;
					switch ($str_merchant_name) {
						case "Adult"		 : $arr_merchant_types[0] = "";
											   break;
						case "Card Swipe"	 : $arr_merchant_types[1] = "";
											   break;
						case "Ecommerce"	 : $arr_merchant_types[2] = "";
											   break;
						case "Gaming"		 : $arr_merchant_types[3] = "";
											   break;
						case "Gateway"		 : $arr_merchant_types[4] = "";
											   break;
						case "Pharmacy"		 : $arr_merchant_types[5] = "";
											   break;
						case "Telemarketing" : $arr_merchant_types[6] = "";
											   break;
						case "Travel"		 : $arr_merchant_types[7] = "";
											   break;
					}
			?>
					<center>		
					<table cellspacing="0" cellpadding="0" style="margin-top:5" width="95%">
					<tr>
					<td align="center"><br><font face="verdana" size="2"><b><?= $str_merchant_name ?></b></font>
					</td>
					</tr>
					</table>
				<?
					if ($companytype == "A") {
				?>
					<!--<table cellspacing="0" cellpadding="0" style="margin-top:5" width="100%">
					<tr>
					<td align="left"><font face="verdana" size="2" style="margin-left:10"><b>Active Companies</b></font>
					</td>
					</tr>
					</table>-->
				<?
					}
				?>
			<?
				}
				if($companytype == "A")
				{
					if($showval[28]==1) 
					{
						$i_active++;
					} 
					else 
					{
						$i_non_active++;
					}
				}
				else
				{
					$i_active++;
					$i_non_active = -1;
				}
				if($showval[7]=="") 
				{
					$state=str_replace("\n",",\t",$showval[12]);
				} 
				else 
				{
					$state=str_replace("\n",",\t",$showval[7]);
				}
				$str_address = "";
				$str_address = $showval[5] == "" ? "" : $showval[5]."<br>";
				//$str_address .= $showval[6] == "" ? "" : $showval[6]."<br>";
				$str_address .= $state == "" ? "" : $state."<br>";
				$str_address .= $showval[8] == "" ? "" : $showval[8]."<br>";
				//$str_address .= $showval[9] == "" ? "" : $showval[9]."<br>";
				if($i_active == 0)
				{
					$i_active = -1;
				}
				if($i_active == 1 || $i_non_active == 1)
				{
					if($i_non_active == 1)
					{
						$i_active = -1;
						//$str_bg_color = "red";

					}
					if ($str_merchant_name != $str_prev_merchant_name) {
						$str_prev_merchant_name = $str_merchant_name;
?>
						<table class='lefttopright' cellpadding='3' cellspacing='0' width='95%'  valign="center" align="center"  style='margin-top: 5; margin-bottom: 5;margin-left: 8;margin-right: 5;'>
						<tr height='30' bgcolor='#78B6C2'>
						<td align='center' width='100' class='cl1'><span class="subhd">Company Name</span></td>
						 <td align='center' width='180' class='cl1'><span class="subhd">URL</span></td>
						 <td align='center' width='150' class='cl1'><span class="subhd">Total Monthly Volume</span></td>
						 <td align='center' width='80' class='cl2'><span class="subhd">Wire Money</span></td> 
						 <td align='center' width='80' class='cl2'><span class="subhd">Documents</span></td> 
						 <td align='center' width='200' class='cl2'><span class="subhd">Signup Date & Time</span></td>
						 <td align='center' width='200' class='cl2'><span class="subhd">Edit</span></td>
						 <td align='center' width='200' class='cl2'><span class="subhd">Delete</span></td>
						 </tr>
<?
					}
				}
			if($showval[77] ==1 ) {
	?>
					<tr height='30'><td align='center' class='cl1'><font face='verdana' size='1' color="<?= $str_bg_color ?>">&nbsp;<a href="viewCompanyProfile.php?company_id=<?= $company_id?>" style="color:<?= $str_bg_color ?>"><strong><?=$showval[3]?></strong></a></font></td>
			<?php } else { ?>
					<tr height='30'><td align='center' class='cl1'><font face='verdana' size='1' color="<?= $str_bg_color ?>">&nbsp;<a href="viewCompanyProfile.php?company_id=<?= $company_id?>" style="color:<?= $str_bg_color ?>"><?=$showval[3]?></a></font></td>
			<?php } ?>
					<td align='center' class='cl1'>&nbsp;<font face='verdana' size='1' color="<?= $str_bg_color ?>"><?= $showval[43] == "" ? "" : "$showval[43]";?><?= $showval[44] == "" ? "" : "<br>$showval[44]";?><?= $showval[45] == "" ? "" : "<br>$showval[45]";?></font></td>
					<td align='center' class='cl1'><font face='verdana' size='1' color="<?= $str_bg_color ?>">&nbsp;<?=$showval[30]?></font></td>
					<td align='center' class='cl1'><font face='verdana' size='1' color="<?= $str_bg_color ?>">&nbsp;<?=$showval[49] == "Y" ? "Yes" : "No"?></font></td> 
					<td align='center' class='cl1' ><font face='verdana' size='1' color="<?= $str_bg_color ?>">&nbsp;
					<?= $i_num_documents == 0 ? "0/6" : "<a href='viewuploads.php?companyname=$showval[0]&companymode=$companytype&companytrans_type=$companytrans_type&companies=$companyname' style='color:$str_bg_color'>$i_num_documents/6"?>
					</font></td> 
					<td align='center' class='cl1' ><font face='verdana' size='1' color="<?= $str_bg_color ?>"><?=$showval[46] == "0000-00-00 00:00:00" ? "Not Available" : func_get_date_time_12hr($showval[46])?></font></td>
					<td align='center' class='cl1' ><font face='verdana' size='1'><a href="editCompanyProfile.php?company_id=<?= $company_id?>">Edit</a></font></td>
					<td align='center' class='cl1' ><font face='verdana' size='1'><a href="javascript:func_delete(<?=$company_id?>);">Delete</a></font></td>
					</tr>
		<?
			}
		?>
					</table>
		<?
					if($i_non_active == 0)
					{
		?>
						<!--<table cellspacing="0" cellpadding="0" style="margin-top:15" width="100%">
						<tr>
						<td align="left"><font face="verdana" size="2" style="margin-left:10"><b>Non Active Companies</b></font>
						</td>
						</tr>
						</table>
						<p align="left">
						<font face="verdana" size="1" style="margin-left:30"><b>No Non-Active Companies</b></font>
						</p>-->
		<?
					}
					if ($companytrans_type == "A") {
						for ($i = 0; $i < count($arr_merchant_types); $i++) {
							if ($arr_merchant_types[$i] != "") {
						?>
								<center>		
								<table cellspacing="0" cellpadding="0" style="margin-top:5" width="100%">
								<tr>
								<td align="center"><br><font face="verdana" size="2"><b><?= $arr_merchant_types[$i] ?></b></font>
								</td>
								</tr>
								</table>
								<p align="center">
								<font face="verdana" size="1" style="margin-left:30"><b>No Companies to display</b></font>
								</p>
						<?
							}
						}
					}
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
	  
<?
	} 
?>
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