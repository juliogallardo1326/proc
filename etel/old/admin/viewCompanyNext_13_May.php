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

$headerInclude = "companies";
include("includes/header.php");


include("includes/message.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{
	$str_active_color = "green";
	$str_non_active_color = "red";
	$emailsingle = (isset($HTTP_POST_VARS['emailsingle'])?quote_smart($HTTP_POST_VARS['emailsingle']):"");
	$username = (isset($HTTP_POST_VARS['username'])?quote_smart($HTTP_POST_VARS['username']):"");
	$password = (isset($HTTP_POST_VARS['password'])?quote_smart($HTTP_POST_VARS['password']):"");
	$email = (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");
	$companyname = isset($HTTP_GET_VARS['companyname'])?$HTTP_GET_VARS['companyname']:"";
	$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"";
	$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"";

if($emailsingle !="")
	{
		$headers = "";
		$headers .= "From: Companysetup <unknown@unknown.com>\n"; // <admin@companysetup.com>\n";
		$headers .= "X-Sender: Admin Companysetup\n"; 
		$headers .= "X-Mailer: PHP\n"; // mailer
		$headers .= "X-Priority: 1\n"; // Urgent message!
		$headers .= "Return-Path: <unknown@unknown.com>\n"; // <admin@companysetup.com>\n";  // Return path for errors
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n"; // Mime type
		$emailmessage ="<br>Find the details for accessing the site.<table style='border:1px solid #d1d1d1;width:150px'><tr><td>Username : $username </td></tr><tr><td>Password : $password</td></tr></table>";
		mail($email,"Company user details",$emailmessage,$headers);
		$msgtodisplay="Email Send to the Company.";
		$outhtml="y";
		message($msgtodisplay,$outhtml,$headerInclude);					
		exit();
	}
	$qry_select_companies_tele = "";
	$qry_select_companies_ecom = "";
	$qry_select_companies_trvl = "";
	$qry_select_companies_phrm = "";
	$qry_select_companies_game = "";
	$qry_select_companies_adlt = "";
	$qry_select_companies_card = "";
	$qry_select_merchants = "";
	$qry_select_companies = "";
	if($companyname !="")
	{
		if($companyname== "A" && $companytype== "A") 
		{
			if ($companytrans_type == "A") {
				$qry_select_companies = "select * from cs_companydetails order by transaction_type asc, activeuser desc, date_added desc";
			} else {
				$qry_select_companies = "select * from cs_companydetails where transaction_type = '$companytrans_type' order by transaction_type asc, activeuser desc, date_added desc";
			}
		}
		else if($companyname== "A"  && $companytype== "AC") 
		{
			if ($companytrans_type == "A") {
				$qry_select_companies = "select * from cs_companydetails where activeuser=1 order by transaction_type asc, date_added desc";
			} else {
				$qry_select_companies = "select * from cs_companydetails where transaction_type = '$companytrans_type' and activeuser=1 order by transaction_type asc, date_added desc";
			}
		}
		else if($companyname== "A" &&  $companytype== "NC") 
		{
			if ($companytrans_type == "A") {
				$qry_select_companies = "select * from cs_companydetails where activeuser=0 order by transaction_type asc, date_added desc";
			} else {
				$qry_select_companies = "select * from cs_companydetails where transaction_type = '$companytrans_type' and activeuser=0 order by transaction_type asc, date_added desc";
			}
		}
		 else 
		{
			$qry_select_companies = "select * from cs_companydetails where userid=$companyname";
		}
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
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
	<table width="60%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">View&nbsp; 
            Details </span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">
	<form action="viewCompanyNext.php"  name="Frmcompany" method="post">
<?	
	if($companyname== "A") 
	{
?>
	<table cellspacing="0" cellpadding="0" style="margin-top:5" width="100%">
	<tr>
	<td align="left">&nbsp;&nbsp;&nbsp;<font face="verdana" size="2"><b><font color="<?= $str_active_color ?>">Active</font>&nbsp;&nbsp;<font color="<?= $str_non_active_color ?>">Non-Active</font></b></font>
	</td>
	</tr>
	</table>

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
				$has_documents = false;
				$company_id = $showval[0];
				$svr = $_SERVER["PATH_TRANSLATED"];
				$path_parts = pathinfo($svr); 
				$str_current_path = $path_parts["dirname"];
			
				$mydirLicense = dir($str_current_path.'..//UserDocuments//License//');
				$mydirArticles = dir($str_current_path.'..//UserDocuments//Articles//');
				$mydirHistory = dir($str_current_path.'..//UserDocuments//History//');
//				$mydirContract = dir($str_current_path.'..//UserDocuments//Contract//');

				$myLicenceFileList = func_read_file_uploaded_name($mydirLicense,$company_id);
				$myArticlesFileList = func_read_file_uploaded_name($mydirArticles,$company_id);
				$myHistoryFileList = func_read_file_uploaded_name($mydirHistory,$company_id);
//				$myContractFileList = func_read_file_uploaded_name($mydirContract,$company_id);

				$myLicenceFileArray = split(",",$myLicenceFileList);
				$myArticlesFileArray = split(",",$myArticlesFileList);
				$myHistoryFileArray = split(",",$myHistoryFileList);
//				$myContractFileArray = split(",",$myContractFileList);

				if ($myLicenceFileList != "" || $myArticlesFileList != "" || $myHistoryFileList != "" ) {
					$has_documents = true;
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
					<table cellspacing="0" cellpadding="0" style="margin-top:5" width="100%">
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
	?>
					<!--<p align="left">
					<font face="verdana" size="1" style="margin-left:30"><b>No Active Companies</b></font>
					</p>-->
	<?
					$i_active = -1;
				}
				if($i_active == 1 || $i_non_active == 1)
				{
					if($i_non_active == 1)
					{
						$i_active = -1;
						//$str_bg_color = "red";
?>
						<!--<table cellspacing="0" cellpadding="0" style="margin-top:15" width="100%">
						<tr>
						<td align="left"><font face="verdana" size="2" style="margin-left:10"><b>Non Active Companies</b></font>
						</td>
						</tr>
						</table>-->
<?
					}
					if ($str_merchant_name != $str_prev_merchant_name) {
						$str_prev_merchant_name = $str_merchant_name;
?>
						<table class='lefttopright' cellpadding='3' cellspacing='0' width='98%'  valign="center" align="center"  style='margin-top: 5; margin-bottom: 5;margin-left: 8;margin-right: 5;'>
						<tr height='30' bgcolor='#CCCCCC'>
						<td align='center' width='100' class='cl1'><span class="subhd">Company Name</span></td>
						<td align='center'  width='100' class='cl1' ><span class="subhd">User Name</span></td>
						<td align='center'  width='100' class='cl1' ><span class="subhd">Password</span></td>
						<td  align='center' width='100' class='cl1'><span class="subhd">Address</span></td>
						 <td  align='center' width='180' class='cl1'><span class="subhd">URL</span></td>
						 <td  align='center' width='150' class='cl1'><span class="subhd">Email</span></td>
						 <td align='center'  width='80' class='cl2'><span class="subhd">Completed Uploading Documents</span></td>
						 <td align='center'  width='80' class='cl2'><span class="subhd">Documents</span></td>
						 <td align='center'  width='80' class='cl2'><span class="subhd">Source</span></td>
						 <td align='center'  width='150' class='cl2'><span class="subhd">Signup Date & Time</span></td>
						 </tr>
<?
					}
				}
	?>
					<tr height='30'><td align='center' class='cl1'><font face='verdana' size='1' color="<?= $str_bg_color ?>">&nbsp;<a href="viewCompanyProfile.php?company_id=<?= $company_id?>" style="color:<?= $str_bg_color ?>"><?=$showval[3]?></a></font></td>
					<td align='center'  class='cl1' ><font face='verdana' size='1' color="<?= $str_bg_color ?>">&nbsp;<?=$showval[1]?></font></td>
					<td align='center'  class='cl1' ><font face='verdana' size='1' color="<?= $str_bg_color ?>">&nbsp;<?=$showval[2]?></font></td>
					<td align='center' class='cl1'><font face='verdana' size='1' color="<?= $str_bg_color ?>">&nbsp;<?= $str_address?></font></td>
					<td align='center' class='cl1'>&nbsp;<font face='verdana' size='1' color="<?= $str_bg_color ?>"><?= $showval[43] == "" ? "" : "$showval[43]";?><?= $showval[44] == "" ? "" : "<br>$showval[44]";?><?= $showval[45] == "" ? "" : "<br>$showval[45]";?></font></td>
					<td align='center' class='cl1'><font face='verdana' size='1' color="<?= $str_bg_color ?>"><b>&nbsp;<?=$showval[10]?></font></td>
					<td align='center' class='cl1'><font face='verdana' size='1' color="<?= $str_bg_color ?>">&nbsp;<?=$showval[49] == "Y" ? "Yes" : "No"?></font></td>
					<td align='center' class='cl1' ><font face='verdana' size='1' color="<?= $str_bg_color ?>">&nbsp;
					<?= $has_documents ? "<a href='viewuploads.php?companyname=$showval[0]' style='color:$str_bg_color'>View" : "Nil"?>
					</font></td>
					<td align='center' class='cl1'><font face='verdana' size='1' color="<?= $str_bg_color ?>"><b>&nbsp;<?=$showval[47]?></font></td>
					<td align='center' class='cl1' ><font face='verdana' size='1' color="<?= $str_bg_color ?>"><?=$showval[46] == "0000-00-00 00:00:00" ? "Not Available" : func_get_date_time_12hr($showval[46])?></font></td>
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
		<td align="center" valign="center" height="30" colspan="2"><a href="#" onclick="window.history.back()"><img  id="emailr" SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a></td></tr>	
		</table></center>
	  
<?
	} 
	else
	{
	
		while($showval = mysql_fetch_row($show_sql)) 
		{
			if($showval[7]=="") 
			{
				$state=str_replace("\n",",\t",$showval[12]);
			} 
			else 
			{
				$state=str_replace("\n",",\t",$showval[7]);
			}

		 ?>
		<table width="100%"><tr><td align="center">
		<table class='lefttopright' cellpadding='5' cellspacing='0' valign=center style='margin-top: 15; margin-bottom: 5'>
<tr> 
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Company 
                          Informations </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
						</tr>					  <tr height='30'> 
                        <td width="189" align='left' class='cl1'><font face='verdana' size='1'><b>Company 
                          Name</b></font></td>
                        <td align='left'  class='cl1'><font face='verdana' size='1'>&nbsp; 
                          <?=$showval[3]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td  align='left'  class='cl1'><font face='verdana' size='1'><b>User 
                          Name</b></font></td>
                        <td align='left' class='cl1'><font face='verdana' size='1'>&nbsp; 
                          <?=$showval[1]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td  align='left'  class='cl1'><font face='verdana' size='1'><b>Password</b></font></td>
                        <td align='left'  class='cl1'><font face='verdana' size='1'>&nbsp; 
                          <?=$showval[2]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Address</b></font></td>
                        <td align='left' class='cl1'><font face='verdana' size='1'>&nbsp; 
                          <?=str_replace("\n",",\t",$showval[5]);?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align='left' class='cl1'><font face='verdana' size='1'><b>City</b></font></td>
                        <td align='left'  class='cl1'><font face='verdana' size='1'>&nbsp; 
                          <?=str_replace("\n",",\t",$showval[6]);?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align='left' class='cl1'><font face='verdana' size='1'><b>State</b></font></td>
                        <td align='left'  class='cl1'><font face='verdana' size='1'>&nbsp; 
                          <?=$state;?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Country</b></font></td>
                        <td align='left'  class='cl1'><font face='verdana' size='1'>&nbsp; 
                          <?=str_replace("\n",",\t",$showval[8]);?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Zipcode</b></font></td>
                        <td align='left'  class='cl1'><font face='verdana' size='1'>&nbsp; 
                          <?=str_replace("\n",",\t",$showval[9]);?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td  align='left'  class='cl1' ><font face='verdana' size='1'><b>Phone 
                          Number</b></font></td>
                        <td align='left'  class='cl1' ><font face='verdana' size='1'>&nbsp; 
                          <?=$showval[4]?>
                          </font></td>
                      </tr>
					<tr> 
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Web 
                          Site Informations </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
						</tr>                      
						<tr> 
                        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Email</b></font></td>
                        <td align='left' class='cl1'><font face='verdana' size='1'>&nbsp; 
                          <?=$showval[10]?>
                          </font></td>
                      </tr>
<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">URL1&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<?=$showval[43]?></font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">URL2&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<?=$showval[44]?></font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">URL3&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<?=$showval[45]?></font></td>
						</tr>                      <tr> 
                        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Signup Date & Time</b></font></td>
                        <td align='left' class='cl1'><font face='verdana' size='1'>&nbsp; 
                          <?=$showval[46] == "0000-00-00 00:00:00" ? "Not Available" : func_get_date_time_12hr($showval[46])?>
                          </font></td>
                      </tr>
					  <tr> 
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>E-mail 
                          Template Setup</strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
						</tr>
					  
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Merchant 
                          Name</font></strong></td>
                        <td class='cl1'> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[13]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Toll 
                          Free Number</font></strong></td>
                        <td class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          &nbsp; 
                          <?=$showval[14]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Retrieval 
                          Number </font></strong></td>
                        <td class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          &nbsp; 
                          <?=$showval[15]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Security 
                          Number </font></strong></td>
                        <td class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          &nbsp; 
                          <?=$showval[16]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Processor</font></strong></td>
                        <td class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          &nbsp; <?=$showval[17]?></font></td>
                      </tr>
						<tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Billing Descriptor</font></strong></td>
                        <td class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          &nbsp; <?=$showval[48]?></font></td>
                      </tr>					  <tr> 
                        <td height="30" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Ledger 
                          Constants</strong>&nbsp;</font></td>
	                        <td height="30" align="left" class='cl1'>&nbsp;</td>
						</tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Charge 
                          Back - $</font></strong></td>
                        <td class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          &nbsp; 
                          $<?=$showval[18]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Credit 
                          $ </font></strong></td>
                        <td class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          &nbsp; 
                          $<?=$showval[19]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Transaction 
                          Fee - $</font></strong></td>
                        <td class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          &nbsp; 
                          $<?=$showval[21]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Voice 
                          Authorization Fee - $</font></strong></td>
                        <td class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          &nbsp; 
                          $<?=$showval[23]?>
                          </font></td>
                      </tr>
					  <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Discount 
                          Rate - %</font></strong></td>
                        <td class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          &nbsp; 
                          <?=$showval[20]?>%
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Reserve 
                          - %</font></strong></td>
                        <td class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          &nbsp; 
                          <?=$showval[22]?>%
                          </font></td>
                      </tr>
						<tr> 
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Process 
                          Informations </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
						</tr>
						<tr>
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Agree 
                          Merchant Contract</font></strong></td>
<td class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<?=$showval[50]?></font></td>
                      </tr>
						<tr><td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Transaction 
                          Active</font></strong></td><td class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<?=$showval[28] ==1 ? "Yes" : "No"?></font></td>
                      </tr>
						<tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Merchant 
                          Type </font></strong></td>
<td class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<?php if($showval[27] == "ecom") print"Ecommerce"; else if($showval[27] == "pmtg") print "Gateway"; else if($showval[27] == "tele") print"Telemarketing"; else if($showval[27] == "trvl") print"Travel"; else if($showval[27] == "phrm") print"Pharmacy"; else if($showval[27] == "game") print"Gaming"; else if($showval[27] == "adlt") print"Adult";  else print"&nbsp;";?></font></td>
                      </tr>
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Customer Service Cancel(auto)</font></strong></td>
						<td align="left" height="30" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<?=$showval[24] == "Y" ? "Yes - $showval[25] Days" : "No"?></font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Shipping Cancel(auto)</font></strong></td>
						<td align="left" height="30" width="225" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<?=$showval[31] == "Y" ? "Yes - $showval[32] Days" : "No"?></font></td>
						</tr>						
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Auto Approve Pass Orders&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<?=$showval[26] == "Y" ? "Yes" : "No"?></font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Expected Monthly Volume ($)&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<?=$showval[30]?></font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Average Ticket&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<?=$showval[38]?></font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Charge Back %&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<?=$showval[39]?></font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Previous Processing&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<?=$showval[40]?></font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Recurring Billing&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<?=$showval[41]?></font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Currently Processing&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<?=$showval[42]?></font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">How hear about us?&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<a href="<?=$showval[47]?>" target="_blank"><?=$showval[47]?></a></font></td>
						</tr>
	<?php 
		$svr = $_SERVER["PATH_TRANSLATED"];
		$path_parts = pathinfo($svr); 
		$str_current_path = $path_parts["dirname"];
		$company_id= $companyname;
		$mydirLicense = dir($str_current_path.'..//UserDocuments//License//');
		$mydirArticles = dir($str_current_path.'..//UserDocuments//Articles//');
		$mydirHistory = dir($str_current_path.'..//UserDocuments//History//');
//		$mydirContract = dir($str_current_path.'..//UserDocuments//Contract//');
/*
		$mydirLicense = dir($str_current_path.'..\\UserDocuments\\License\\');
		$mydirArticles = dir($str_current_path.'..\\UserDocuments\\Articles\\');
		$mydirHistory = dir($str_current_path.'..\\UserDocuments\\History\\');
		$mydirContract = dir($str_current_path.'..\\UserDocuments\\Contract\\');
*/
		$myLicenceFileList = func_read_file_uploaded_name($mydirLicense,$company_id);
		$myArticlesFileList = func_read_file_uploaded_name($mydirArticles,$company_id);
		$myHistoryFileList = func_read_file_uploaded_name($mydirHistory,$company_id);
//		$myContractFileList = func_read_file_uploaded_name($mydirContract,$company_id);
		
		$myLicenceFileArray = split(",",$myLicenceFileList);
		$myArticlesFileArray = split(",",$myArticlesFileList);
		$myHistoryFileArray = split(",",$myHistoryFileList);
//		$myContractFileArray = split(",",$myContractFileList);

	?>					
						<tr> 
                        <td height="30" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Uploaded 
                          Documents </strong>&nbsp;</font></td>
	                        <td height="30" align="left" class='cl1'>&nbsp;</td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Completed Uploading Documents?&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'>&nbsp;<font face="verdana" size="1"><?= $showval[49] == "Y" ? "Yes" : "No" ?></font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Drivers 
                          License/Passport &nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font face="verdana" size="1">
<?php					for($i_loop=0;$i_loop<count($myLicenceFileArray);$i_loop++) {
							print "&nbsp;<a href='../UserDocuments/License/$myLicenceFileArray[$i_loop]' target='_blank'>$myLicenceFileArray[$i_loop]</a><br>";
						}
?>						&nbsp;</font></td>
						</tr>
						
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Articles 
                          of Incorporation&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font face="verdana" size="1">
<?php					for($j_loop=0;$j_loop<count($myArticlesFileArray);$j_loop++) {
							print "&nbsp;<a href='../UserDocuments/Articles/$myArticlesFileArray[$j_loop]' target='_blank'>$myArticlesFileArray[$j_loop]</a><br>";
						}
?>
                          &nbsp;</font></td>
						</tr>
						
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Previous 
                          processing history (if applicable)&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font face="verdana" size="1">
<?php					for($k_loop=0;$k_loop<count($myHistoryFileArray);$k_loop++) {
							print "&nbsp;<a href='../UserDocuments/History/$myHistoryFileArray[$k_loop]' target='_blank'>$myHistoryFileArray[$k_loop]</a><br>";
						}
?>
                          &nbsp;</font></td>
						</tr>
						
<!--						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Signed 
                          merchant Contract&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font face="verdana" size="1">
<?php	//				for($l_loop=0;$l_loop<count($myContractFileArray);$l_loop++) {
		//					print "&nbsp;<a href='../UserDocuments/Contract/$myContractFileArray[$l_loop]' target='_blank'>$myContractFileArray[$l_loop]</a><br>";
		//				}
?>
                          &nbsp;</font></td>
						</tr> -->
<?php					if($showval[27] == "tele") {
?>						
                        <tr> 
                        <td height="30" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Verification 
                          Script </strong>&nbsp;</font></td>
	                        <td height="30" align="left" class='cl1'>&nbsp;</td>
						</tr>						
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Package Name &nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font face="verdana" size="1"><?=$showval[33]?>&nbsp;</font></td>
						</tr>
						
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Package Product Service &nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font face="verdana" size="1"><?=$showval[34]?>&nbsp;</font></td>
						</tr>
						
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Package Price &nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font face="verdana" size="1"><?=$showval[35]?>&nbsp;</font></td>
						</tr>
						
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Refund Policy &nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font face="verdana" size="1"><?=$showval[36]?>&nbsp;</font></td>
						</tr>
						
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">Description &nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'><font face="verdana" size="1"><?=$showval[37]?>&nbsp;</font></td>
						</tr>
<?						}
?>
                      <input type="hidden" name="username" value="<?=$showval[1]?>"></input>
                      <input type="hidden" name="email" value="<?=$showval[10]?>"></input>
                      <input type="hidden" name="password" value="<?=$showval[2]?>" ></input>
                    </table>
		</td></tr></table>
		<center>
		<table align="center">
		<tr><td align="center" valign="center" height="30" colspan="2" ><input type="hidden" name="emailsingle" value="emailsingle"></input>&nbsp;&nbsp;<input type="image" name="emailr" id="emailr" SRC="<?=$tmpl_dir?>/images/emailreminder.jpg"></input> &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="window.history.back()"><img  SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a></td></tr>	
		</table>
		</center>

<?php 
		}
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