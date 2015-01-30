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
// batchuploads.php:	This admin page functions for uploading the company transactions. 
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
$headerInclude = "administration";
include 'includes/header.php';

require_once( '../includes/function.php');

$total_pmtg_comp =0;
$total_ecom_comp =0;
$total_trvl_comp =0;
$total_phrm_comp =0;
$total_game_comp =0;
$total_adlt_comp =0;
$total_tele_comp =0;
$total_pmtg_comp =0;
$active_pmtg_comp =0;
$active_ecom_comp =0;
$active_trvl_comp =0;
$active_phrm_comp =0;
$active_game_comp =0;
$active_adlt_comp =0;
$active_tele_comp =0;
$active_pmtg_comp =0;
$nonactive_pmtg_comp =0;
$nonactive_ecom_comp =0;
$nonactive_trvl_comp =0;
$nonactive_phrm_comp =0;
$nonactive_game_comp =0;
$nonactive_adlt_comp =0;
$nonactive_tele_comp =0;
$nonactive_pmtg_comp =0;

$select_allcompany_sql ="SELECT transaction_type,count(userid) FROM cs_companydetails GROUP BY transaction_type";
$select_activecompany_sql ="SELECT transaction_type,count(userid) FROM cs_companydetails WHERE activeuser =1 GROUP BY transaction_type";
$select_nonactivecompany_sql ="SELECT transaction_type,count(userid) FROM cs_companydetails WHERE activeuser =0 GROUP BY transaction_type";

if(!($show_allcompany_sql =mysql_query($select_allcompany_sql,$cnn_cs))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
//print $select_allcompany_sql.count($show_allcompany_sql);
while($show_select_details = mysql_fetch_array($show_allcompany_sql)) {
//	print " Checking-". $show_select_details[0]." No-".$show_select_details[1];

	if($show_select_details[0]=="ecom") {
		$total_ecom_comp = $show_select_details[1];
	}else if($show_select_details[0]=="trvl") {
		$total_trvl_comp = $show_select_details[1];
	}else if($show_select_details[0]=="phrm") {
		$total_phrm_comp = $show_select_details[1];
	}else if($show_select_details[0]=="game") {
		$total_game_comp = $show_select_details[1];
	}else if($show_select_details[0]=="adlt") {
		$total_adlt_comp = $show_select_details[1];
	}else if($show_select_details[0]=="tele") {
		$total_tele_comp = $show_select_details[1];
	}else if($show_select_details[0]=="pmtg") {
		$total_pmtg_comp = $show_select_details[1];
	}
	
}

if(!($show_activecompany_sql =mysql_query($select_activecompany_sql,$cnn_cs))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
while($show_select_details = mysql_fetch_array($show_activecompany_sql)) {
	if($show_select_details[0]=="ecom") {
		$active_ecom_comp = $show_select_details[1];
	}else if($show_select_details[0]=="trvl") {
		$active_trvl_comp = $show_select_details[1];
	}else if($show_select_details[0]=="phrm") {
		$active_phrm_comp = $show_select_details[1];
	}else if($show_select_details[0]=="game") {
		$active_game_comp = $show_select_details[1];
	}else if($show_select_details[0]=="adlt") {
		$active_adlt_comp = $show_select_details[1];
	}else if($show_select_details[0]=="tele") {
		$active_tele_comp = $show_select_details[1];
	}else if($show_select_details[0]=="pmtg") {
		$active_pmtg_comp = $show_select_details[1];
	}
}

if(!($show_noncompany_sql =mysql_query($select_nonactivecompany_sql,$cnn_cs))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
while($show_select_details = mysql_fetch_array($show_noncompany_sql)) {

	if($show_select_details[0]=="ecom") {
		$nonactive_ecom_comp = $show_select_details[1];
	}else if($show_select_details[0]=="trvl") {
		$nonactive_trvl_comp = $show_select_details[1];
	}else if($show_select_details[0]=="phrm") {
		$nonactive_phrm_comp = $show_select_details[1];
	}else if($show_select_details[0]=="game") {
		$nonactive_game_comp = $show_select_details[1];
	}else if($show_select_details[0]=="adlt") {
		$nonactive_adlt_comp = $show_select_details[1];
	}else if($show_select_details[0]=="tele") {
		$nonactive_tele_comp = $show_select_details[1];
	}else if($show_select_details[0]=="pmtg") {
		$nonactive_pmtg_comp = $show_select_details[1];
	}
}
//print(number_format(4.11E+15));

?>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%" >
  <tr>
       <td width="90%" valign="top" align="center"  height="333">
    &nbsp;
<table border="0" cellspacing="0" cellpadding="0" width="75%">
<tr>
<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
          <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Company 
            Type Details</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td class="lgnbd" colspan="5" align="center">
 <table  width="100%" cellspacing="0" cellpadding="0">
  <tr><td  width="100%" valign="center" align="center">&nbsp;     
      <table width="77%" border="1" cellspacing="0" cellpadding="0">
<tr bgcolor="#CCCCCC">
    <td width="100" height="20"><span class="subhd">Company Type</span></td>
                      <td width="123"><span class="subhd">Active companies</span></td>
                      <td width="144"><span class="subhd">Non active companies</span></td>
                      <td width="107"><span class="subhd">Total companies</span></td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC"><span class="subhd">Ecommerce</span></td>
    <td><?=$active_ecom_comp?></td>
    <td><?=$nonactive_ecom_comp?></td>
    <td><?=$total_ecom_comp?></td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC"><span class="subhd">Telemarketing</span></td>
    <td><?=$active_tele_comp?></td>
    <td><?=$nonactive_tele_comp?></td>
    <td><?=$total_tele_comp?></td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC"><span class="subhd">Gateway</span></td>
    <td><?=$active_pmtg_comp?></td>
    <td><?=$nonactive_pmtg_comp?></td>
    <td><?=$active_pmtg_comp?></td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC"><span class="subhd">Travel</span></td>
    <td><?=$active_trvl_comp?></td>
    <td><?=$nonactive_trvl_comp?></td>
    <td><?=$total_trvl_comp?></td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC"><span class="subhd">Pharmacy</span></td>
    <td><?=$active_phrm_comp?></td>
    <td><?=$nonactive_phrm_comp?></td>
    <td><?=$total_phrm_comp?></td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC"><span class="subhd">Gaming</span></td>
    <td><?=$active_game_comp?></td>
    <td><?=$nonactive_game_comp?></td>
    <td><?=$total_game_comp?></td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC"><span class="subhd">Adult</span></td>
    <td><?=$active_adlt_comp?></td>
    <td><?=$nonactive_adlt_comp?></td>
    <td><?=$active_adlt_comp?></td>
  </tr>
</table><br>

  </td></tr></table>
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
<?php include("includes/footer.php");
?>