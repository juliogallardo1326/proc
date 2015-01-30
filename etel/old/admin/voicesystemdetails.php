<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,companyAdd.php,excelout.php,massmail.php,modifyCompany.php,modifyCompanyNext.php,report.php,reportTop.php, 
//				config.php,reportBottom.php,reportBottomNext.php,useraccount.php,viewCompany.php,viewCompanyNext.php,ViewreportPage.php
// voicesystemdetails.php:	The admin page functions for displaying the voice sytem reports. 
include("includes/sessioncheck.php");


$headerInclude = "voicesystem";
include("includes/header.php");

include("includes/message.php");
?>
<?php
$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");
$select_user_type="";
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);
$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day . " 00:00:00";
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day . " 23:59:59";
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"";
$companyid = (isset($HTTP_POST_VARS['companyname'])?($HTTP_POST_VARS['companyname']):"");
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"";

$selected_company = "";
if ($companytype =="AC") {
	if ($companytrans_type == "A") {
		$select_user_type =" and B.activeuser=1";
	} else {
		$select_user_type =" and B.activeuser=1 and transaction_type = '$companytrans_type'";
	}
} else if($companytype=="NC") {
	if ($companytrans_type == "A") {
		$select_user_type =" and B.activeuser=0";
	} else {
		$select_user_type =" and B.activeuser=0 and transaction_type = '$companytrans_type'";
	}
} else if($companytype=="RE") {
	if ($companytrans_type == "A") {
		$select_user_type =" and B.reseller_id <> ''";
	} else {
		$select_user_type =" and B.reseller_id <> '' and transaction_type = '$companytrans_type'";
	}
} else if($companytype=="ET") {
	if ($companytrans_type == "A") {
		$select_user_type =" and B.reseller_id is null";
	} else {
		$select_user_type =" and B.reseller_id is null and transaction_type = '$companytrans_type'";
	}
} else {
	if ($companytrans_type == "A") {
		$select_user_type ="";
	} else {
		$select_user_type =" and transaction_type = '$companytrans_type'";
	}
}
if($companyid) {
	if($companyid[0]=="A") {
		$qry_select_details ="select A.upload_id,A.upload_batch_id,A.voice_authorization_id,A.telephone_number, A.pass_status,A.comments,A.upload_date_time,A.updated,B.companyname from cs_voice_system_upload_log A, cs_companydetails B where A.user_id = B.userId and gateway_id = -1 $select_user_type and A.upload_date_time >='$str_from_date' and A.upload_date_time <='$str_to_date' and updated = 'Y' order by A.upload_date_time desc" ;
	} else {
		for($i_loop=0;$i_loop<count($companyid);$i_loop++)
		{	
			if($selected_company == ""){
			$selected_company = $selected_company." (A.user_id = ".$companyid[$i_loop];
			}else{
			$selected_company = $selected_company."  or A.user_id = ".$companyid[$i_loop];
			}
		}
		$selected_company = $selected_company." )"; 
		$qry_select_details ="select A.upload_id,A.upload_batch_id,A.voice_authorization_id,A.telephone_number, A.pass_status,A.comments,A.upload_date_time,A.updated,B.companyname from cs_voice_system_upload_log A, cs_companydetails B where A.user_id = B.userId and $selected_company and A.upload_date_time >='$str_from_date' and A.upload_date_time <='$str_to_date' and updated = 'Y' order by A.upload_date_time desc" ;
	}
}
//	print($qry_select_details);
	if(!($rs_select_details = mysql_query($qry_select_details,$cnn_cs))) {
		print(mysql_errno().": ".mysql_error()."<BR>");
		print("<br>");
		//print($qry_select_details);
		print("Cannot execute query");
		exit();

	}
	$i_count = mysql_num_rows($rs_select_details);

	if ($i_count==0)
	{
		$msgtodisplay="No Voice System Report for this period.";
		$outhtml="y";				
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();	   
	} 
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="61%" >
  <tr>
       <td width="83%" valign="top" align="center"  >
<br>
<form name="update_shipping" action="../updateshipping.php" method="post">
<table width="90%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Voice System Report</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
</tr>
<?php 	
		$str_report = "";
		$iloop = 0;
		$i_pass = 0;
		$i_no_pass = 0;
		while($show_select_details = mysql_fetch_array($rs_select_details)) 
		{	
			$iloop = $iloop +1;
			$str_pass_status_code = $show_select_details[4];
			if ($str_pass_status_code == 'PA') {
				$i_pass++;
				$str_pass_status = "Pass";
			} else if($str_pass_status_code == 'NP'){
				$i_no_pass++;
				$str_pass_status = "No Pass";
			} else if($str_pass_status_code == 'ND'){
				$str_pass_status = "Negative Database";
			}

			 $str_report .= "<input type='hidden' name='upload_id$iloop' value='$show_select_details[0]'>";
			 $str_report .= "<tr>";
			 $str_report .= "<td class='leftbottomright' height='30'><font size='1' face='Verdana'>".func_get_date_time_12hr($show_select_details[6])."</font></td>";
			 $str_report .= "<td class='rightbottomtd' ><font size='1' face='Verdana' >$show_select_details[2]</font></td>";
			 $str_report .= "<td class='rightbottomtd' ><font size='1' face='Verdana' >$show_select_details[8]</font></td>";
			 $str_report .= "<td class='rightbottomtd' ><font size='1' face='Verdana' >$show_select_details[3]</font></td>";
			 $str_report .= "<td class='rightbottomtd' ><font size='1' face='Verdana' >$str_pass_status</font></td>";
			 $str_report .= "<td class='rightbottomtd' ><font size='1' face='Verdana' >$show_select_details[5]&nbsp;</font></td>";		 
			 $str_report .= "</tr>";
		}
?>	 
<tr>
<td class="lgnbd" colspan="5" height="30">
<font face="verdana" size="2"><strong>Total Records Updated: <?= $i_count?>&nbsp;&nbsp;Pass: <?= $i_pass?>&nbsp;&nbsp;No Pass: <?= $i_no_pass?></strong></font> 
</td>
</tr>
<tr>
<td class="lgnbd" colspan="5">
	<table width="100%" cellspacing="1" cellpadding="1" border="0" align="center" >
	 <tr>
	              <td class="bottom"  height="30" bgcolor="#CCCCCC" width="17%"><span class="subhd">Uploaded 
                    Date-Time</span></td>
	              <td width="10%" bgcolor="#CCCCCC" class="bottom"><span class="subhd" width="15%">Voice 
                    Auth. ID</span></td>
	              <td width="17%" bgcolor="#CCCCCC" class="bottom"><span class="subhd" width="25%">Company 
                    Name</span></td>
	              <td width="10%" bgcolor="#CCCCCC" class="bottom"><span class="subhd" width="15%">Telephone 
                    No:</span></td>
	              <td width="8%" bgcolor="#CCCCCC" class="bottom"><span class="subhd" width="10%">Pass 
                    Status</span></td>
	              <td width="38%" bgcolor="#CCCCCC" class="bottom"><span class="subhd" width="20%">Comments</span></td>		 
	 </tr>

<?php
	print($str_report);
?>

<tr><td align="center" colspan="8" height="50" valign="middle"><a href="voicesystemreport.php"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a></td></tr>
	</table>							
</td>
</tr>
<tr>
<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
</tr>
</table><br>
<input type="hidden" name="icount" value="<?=$iloop?>">
</form>
</td></tr>
</table>							
<?php
include("includes/footer.php");
?>