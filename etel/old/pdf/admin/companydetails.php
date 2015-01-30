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
$headerInclude = "companies";
include 'includes/header.php';

require_once( '../includes/function.php');

$total_ecom_comp =0;
$total_tele_comp =0;
$total_adult_comp=0;
$total_travel_comp=0;
$total_pharm_comp=0;
$total_gaming_comp=0;
$total_gateway_comp=0;
$total_card_comp=0;
$total_adult_comp=0;
$active_ecom_comp =0;
$active_tele_comp =0;
$active_adult_comp=0;
$active_travel_comp=0;
$active_pharm_comp=0;
$active_gaming_comp=0;
$active_gateway_comp=0;
$active_card_comp=0;
$active_adult_comp=0;
$nonactive_ecom_comp =0;
$nonactive_tele_comp =0;
$nonactive_adult_comp=0;
$nonactive_travel_comp=0;
$nonactive_pharm_comp=0;
$nonactive_gaming_comp=0;
$nonactive_gateway_comp=0;
$nonactive_card_comp=0;
$nonactive_adult_comp=0;
$ready_ecom_comp =0;
$ready_tele_comp =0;
$ready_adult_comp=0;
$ready_travel_comp=0;
$ready_pharm_comp=0;
$ready_gaming_comp=0;
$ready_gateway_comp=0;
$ready_card_comp=0;
$ready_adult_comp=0;
$upload_ecom_comp =0;
$upload_tele_comp =0;
$upload_travel_comp=0;
$upload_pharm_comp=0;
$upload_gaming_comp=0;
$upload_gateway_comp=0;
$upload_card_comp=0;
$upload_adult_comp=0;
$appl_ecom_comp =0;
$appl_tele_comp =0;
$appl_adult_comp=0;
$appl_travel_comp=0;
$appl_pharm_comp=0;
$appl_gaming_comp=0;
$appl_gateway_comp=0;
$appl_card_comp=0;
$appl_adult_comp=0;
$total_merchant = 0;
$active_merchant =0;
$nonactive_merchant =0;
$ready_merchant =0;
$upload_merchant =0;
$appl_merchant =0;


$select_allcompany_sql ="SELECT transaction_type,count(userid) FROM cs_companydetails where 1 $bank_sql_limit  GROUP BY transaction_type";
$select_activecompany_sql ="SELECT transaction_type,count(userid) FROM cs_companydetails WHERE 1 $bank_sql_limit  and activeuser =1 GROUP BY transaction_type";
$select_nonactivecompany_sql ="SELECT transaction_type,count(userid) FROM cs_companydetails WHERE 1 $bank_sql_limit  and activeuser =0 GROUP BY transaction_type";
$select_readytowire_sql = "SELECT transaction_type,count(userid) FROM cs_companydetails where 1 $bank_sql_limit  and completed_uploading='Y' GROUP BY transaction_type";
$select_uploaded_sql = "SELECT transaction_type,count(userid) FROM cs_companydetails where 1 $bank_sql_limit  and num_documents_uploaded=4 GROUP BY transaction_type";
$select_merchantappl_sql = "SELECT transaction_type,count(userid) FROM cs_companydetails where 1 $bank_sql_limit  and completed_merchant_application=1 GROUP BY transaction_type";

if(!($show_merchantappl_sql =mysql_query($select_merchantappl_sql,$cnn_cs))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
while($show_select_details = mysql_fetch_array($show_merchantappl_sql)) {
	if($show_select_details[0]=="tele") {
		$appl_tele_comp = $show_select_details[1];
	} else if($show_select_details[0]=="adlt") {
		$appl_adult_comp = $show_select_details[1];
	} else if($show_select_details[0]=="crds") {
		$appl_card_comp = $show_select_details[1];
	} else if($show_select_details[0]=="ecom") {
		$appl_ecom_comp = $show_select_details[1];
	} else if($show_select_details[0]=="pmtg") {
		$appl_gateway_comp = $show_select_details[1];
	} else if($show_select_details[0]=="game") {
		$appl_gaming_comp = $show_select_details[1];
	} else if($show_select_details[0]=="phrm") {
		$appl_pharm_comp = $show_select_details[1];
	} else if($show_select_details[0]=="trvl") {
		$appl_travel_comp = $show_select_details[1];
	} 
	$appl_merchant += $show_select_details[1];
}

if(!($show_upload_sql =mysql_query($select_uploaded_sql,$cnn_cs))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
while($show_select_details = mysql_fetch_array($show_upload_sql)) {
	if($show_select_details[0]=="tele") {
		$upload_tele_comp = $show_select_details[1];
	} else if($show_select_details[0]=="adlt") {
		$upload_adult_comp = $show_select_details[1];
	} else if($show_select_details[0]=="crds") {
		$upload_card_comp = $show_select_details[1];
	} else if($show_select_details[0]=="ecom") {
		$upload_ecom_comp = $show_select_details[1];
	} else if($show_select_details[0]=="pmtg") {
		$upload_gateway_comp = $show_select_details[1];
	} else if($show_select_details[0]=="game") {
		$upload_gaming_comp = $show_select_details[1];
	} else if($show_select_details[0]=="phrm") {
		$upload_pharm_comp = $show_select_details[1];
	} else if($show_select_details[0]=="trvl") {
		$upload_travel_comp = $show_select_details[1];
	} 
	$upload_merchant += $show_select_details[1];
}

if(!($show_readytowire_sql =mysql_query($select_readytowire_sql,$cnn_cs))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
while($show_select_details = mysql_fetch_array($show_readytowire_sql)) {
	if($show_select_details[0]=="tele") {
		$ready_tele_comp = $show_select_details[1];
	} else if($show_select_details[0]=="adlt") {
		$ready_adult_comp = $show_select_details[1];
	} else if($show_select_details[0]=="crds") {
		$ready_card_comp = $show_select_details[1];
	} else if($show_select_details[0]=="ecom") {
		$ready_ecom_comp = $show_select_details[1];
	} else if($show_select_details[0]=="pmtg") {
		$ready_gateway_comp = $show_select_details[1];
	} else if($show_select_details[0]=="game") {
		$ready_gaming_comp = $show_select_details[1];
	} else if($show_select_details[0]=="phrm") {
		$ready_pharm_comp = $show_select_details[1];
	} else if($show_select_details[0]=="trvl") {
		$ready_travel_comp = $show_select_details[1];
	} 
	$ready_merchant += $show_select_details[1];
}

if(!($show_allcompany_sql =mysql_query($select_allcompany_sql,$cnn_cs))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
while($show_select_details = mysql_fetch_array($show_allcompany_sql)) {
	if($show_select_details[0]=="tele") {
		$total_tele_comp = $show_select_details[1];
	} else if($show_select_details[0]=="adlt") {
		$total_adult_comp = $show_select_details[1];
	} else if($show_select_details[0]=="crds") {
		$total_card_comp = $show_select_details[1];
	} else if($show_select_details[0]=="ecom") {
		$total_ecom_comp = $show_select_details[1];
	} else if($show_select_details[0]=="pmtg") {
		$total_gateway_comp = $show_select_details[1];
	} else if($show_select_details[0]=="game") {
		$total_gaming_comp = $show_select_details[1];
	} else if($show_select_details[0]=="phrm") {
		$total_pharm_comp = $show_select_details[1];
	} else if($show_select_details[0]=="trvl") {
		$total_travel_comp = $show_select_details[1];
	} 
	$total_merchant += $show_select_details[1];
}

if(!($show_activecompany_sql =mysql_query($select_activecompany_sql,$cnn_cs))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
while($show_select_details = mysql_fetch_array($show_activecompany_sql)) {
	if($show_select_details[0]=="tele") {
		$active_tele_comp = $show_select_details[1];
	} else if($show_select_details[0]=="adlt") {
		$active_adult_comp = $show_select_details[1];
	} else if($show_select_details[0]=="crds") {
		$active_card_comp = $show_select_details[1];
	} else if($show_select_details[0]=="ecom") {
		$active_ecom_comp = $show_select_details[1];
	} else if($show_select_details[0]=="pmtg") {
		$active_gateway_comp = $show_select_details[1];
	} else if($show_select_details[0]=="game") {
		$active_gaming_comp = $show_select_details[1];
	} else if($show_select_details[0]=="phrm") {
		$active_pharm_comp = $show_select_details[1];
	} else if($show_select_details[0]=="trvl") {
		$active_travel_comp = $show_select_details[1];
	} 
	$active_merchant += $show_select_details[1];
}

if(!($show_noncompany_sql =mysql_query($select_nonactivecompany_sql,$cnn_cs))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
while($show_select_details = mysql_fetch_array($show_noncompany_sql)) {

if($show_select_details[0]=="tele") {
		$nonactive_tele_comp = $show_select_details[1];
	} else if($show_select_details[0]=="adlt") {
		$nonactive_adult_comp = $show_select_details[1];
	} else if($show_select_details[0]=="crds") {
		$nonactive_card_comp = $show_select_details[1];
	} else if($show_select_details[0]=="ecom") {
		$nonactive_ecom_comp = $show_select_details[1];
	} else if($show_select_details[0]=="pmtg") {
		$nonactive_gateway_comp = $show_select_details[1];
	} else if($show_select_details[0]=="game") {
		$nonactive_gaming_comp = $show_select_details[1];
	} else if($show_select_details[0]=="phrm") {
		$nonactive_pharm_comp = $show_select_details[1];
	} else if($show_select_details[0]=="trvl") {
		$nonactive_travel_comp = $show_select_details[1];
	} 
	$nonactive_merchant += $show_select_details[1];
}
?>

<table border="0" cellpadding="0" width="850" cellspacing="0" height="60%" align="center" >
<tr>
       <td width="90%" valign="top" align="center"  height="333">
    &nbsp;
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Company 
	Type Details <?=$for_bank?></span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
		<td class="lgnbd" colspan="5" align="center">
		 <table  width="100%" cellspacing="0" cellpadding="0">
		  <tr><td  width="100%" valign="center" align="center">&nbsp;     
		  <table width="90%" border="1" cellspacing="0" cellpadding="0">
                    <tr bgcolor="#CCCCCC"> 
                      <td width="100" height="20" align="center" valign="middle"><span class="subhd">Merchant 
                        Type</span></td>
                      <td width="123" align="center" valign="middle"><span class="subhd">Active 
                        Merchants</span></td>
                      <td width="144" align="center" valign="middle"><span class="subhd">Non 
                        Active Merchants</span></td>
                      <td width="107" align="center" valign="middle"><span class="subhd">Ready 
                        To Wire</span></td>
                      <td width="107" align="center" valign="middle"><span class="subhd">Uploaded 
                        Documents</span></td>
                      <td width="107" align="center" valign="middle"><span class="subhd">Merchant Application</span></td>
                      <td width="107" align="center" valign="middle"><span class="subhd">Total 
                        Merchants</span></td>
                    </tr>
                    <tr> 
                      <td height="25" align="center" valign="middle" bgcolor="#CCCCCC"><span class="subhd">Adult</span></td>
                      <td height="25" align="center" valign="middle">&nbsp; 
                        <?=$active_adult_comp?>
                      </td>
                      <td height="25" align="center" valign="middle">&nbsp; 
                        <?=$nonactive_adult_comp?>
                      </td>
                      <td align="center" valign="middle">&nbsp;<?=$ready_adult_comp?></td>
                      <td align="center" valign="middle">&nbsp;<?=$upload_adult_comp?></td>
                      <td align="center" valign="middle">&nbsp;<?=$appl_adult_comp?></td>
                      <td height="25" align="center" valign="middle">&nbsp; <strong> 
                        <?=$total_adult_comp?>
                        </strong> </td>
                    </tr>
                    <tr> 
                      <td height="25" align="center" valign="middle" bgcolor="#CCCCCC"><span class="subhd">Card 
                        Swipe</span></td>
                      <td height="25" align="center" valign="middle">&nbsp; 
                        <?=$active_card_comp?>
                      </td>
                      <td height="25" align="center" valign="middle">&nbsp; 
                        <?=$nonactive_card_comp?>
                      </td>
                      <td align="center" valign="middle">&nbsp;<?=$ready_card_comp?></td>
                      <td align="center" valign="middle">&nbsp;<?=$upload_card_comp?></td>
                      <td align="center" valign="middle">&nbsp;<?=$appl_card_comp?></td>
                      <td height="25" align="center" valign="middle">&nbsp; <strong> 
                        <?=$total_card_comp?>
                        </strong> </td>
                    </tr>
                    <tr> 
                      <td height="25" align="center" valign="middle" bgcolor="#CCCCCC"><span class="subhd">Ecommerce</span></td>
                      <td height="25" align="center" valign="middle">&nbsp; 
                        <?=$active_ecom_comp?>
                      </td>
                      <td height="25" align="center" valign="middle">&nbsp; 
                        <?=$nonactive_ecom_comp?>
                      </td>
                      <td align="center" valign="middle">&nbsp;<?=$ready_ecom_comp?></td>
                      <td align="center" valign="middle">&nbsp;<?=$upload_ecom_comp?></td>
                      <td align="center" valign="middle">&nbsp;<?=$appl_ecom_comp?></td>
                      <td height="25" align="center" valign="middle">&nbsp; <strong> 
                        <?=$total_ecom_comp?>
                        </strong> </td>
                    </tr>
                    <tr> 
                      <td height="25" align="center" valign="middle" bgcolor="#CCCCCC"><span class="subhd">Gateway</span></td>
                      <td height="25" align="center" valign="middle">&nbsp; 
                        <?=$active_gateway_comp?>
                      </td>
                      <td height="25" align="center" valign="middle">&nbsp; 
                        <?=$nonactive_gateway_comp?>
                      </td>
                      <td align="center" valign="middle">&nbsp;<?=$ready_gateway_comp?></td>
                      <td align="center" valign="middle">&nbsp;<?=$upload_gateway_comp?></td>
                      <td align="center" valign="middle">&nbsp;<?=$appl_gateway_comp?></td>
                      <td height="25" align="center" valign="middle">&nbsp; <strong> 
                        <?=$total_gateway_comp?>
                        </strong> </td>
                    </tr>
                    <tr> 
                      <td height="25" align="center" valign="middle" bgcolor="#CCCCCC"><span class="subhd">Gaming</span></td>
                      <td height="25" align="center" valign="middle">&nbsp; 
                        <?=$active_gaming_comp?>
                      </td>
                      <td height="25" align="center" valign="middle">&nbsp; 
                        <?=$nonactive_gaming_comp?>
                      </td>
                      <td align="center" valign="middle">&nbsp;<?=$ready_gaming_comp?></td>
                      <td align="center" valign="middle">&nbsp;<?=$upload_gaming_comp?></td>
                      <td align="center" valign="middle">&nbsp;<?=$appl_gaming_comp?></td>
                      <td height="25" align="center" valign="middle">&nbsp; <strong> 
                        <?=$total_gaming_comp?>
                        </strong> </td>
                    </tr>
                    <tr> 
                      <td height="25" align="center" valign="middle" bgcolor="#CCCCCC"><span class="subhd">Pharmacy</span></td>
                      <td height="25" align="center" valign="middle">&nbsp; 
                        <?=$active_pharm_comp?>
                      </td>
                      <td height="25" align="center" valign="middle">&nbsp; 
                        <?=$nonactive_pharm_comp?>
                      </td>
                      <td align="center" valign="middle">&nbsp;<?=$ready_pharm_comp?></td>
                      <td align="center" valign="middle">&nbsp;<?=$upload_pharm_comp?></td>
                      <td align="center" valign="middle">&nbsp;<?=$appl_pharm_comp?></td>
                      <td height="25" align="center" valign="middle">&nbsp; <strong> 
                        <?=$total_pharm_comp?>
                        </strong> </td>
                    </tr>
                    <tr> 
                      <td height="25" align="center" valign="middle" bgcolor="#CCCCCC"><span class="subhd">Telemarketing</span></td>
                      <td height="25" align="center" valign="middle">&nbsp; 
                        <?=$active_tele_comp?>
                      </td>
                      <td height="25" align="center" valign="middle">&nbsp; 
                        <?=$nonactive_tele_comp?>
                      </td>
                      <td align="center" valign="middle">&nbsp;<?=$ready_tele_comp?></td>
                      <td align="center" valign="middle">&nbsp;
                        <?=$upload_tele_comp?>
                      </td>
                      <td align="center" valign="middle">&nbsp;<?=$appl_tele_comp?></td>
                      <td height="25" align="center" valign="middle">&nbsp; <strong> 
                        <?=$total_tele_comp?>
                        </strong> </td>
                    </tr>
                    <tr> 
                      <td height="25" align="center" valign="middle" bgcolor="#CCCCCC"><span class="subhd">Travel</span></td>
                      <td height="25" align="center" valign="middle">&nbsp; 
                        <?=$active_travel_comp?>
                      </td>
                      <td height="25" align="center" valign="middle">&nbsp; 
                        <?=$nonactive_travel_comp?>
                      </td>
                      <td align="center" valign="middle">&nbsp;<?=$ready_travel_comp?></td>
                      <td align="center" valign="middle">&nbsp;<?=$upload_travel_comp?></td>
                      <td align="center" valign="middle">&nbsp;<?=$appl_travel_comp?></td>
                      <td height="25" align="center" valign="middle">&nbsp; <strong> 
                        <?=$total_travel_comp?>
                        </strong> </td>
                    </tr>
                    <tr> 
                      <td height="25" align="center" valign="middle" bgcolor="#CCCCCC"><span class="subhd">Total</span></td>
                      <td height="25" align="center" valign="middle">&nbsp; <strong> 
                        <?=$active_merchant?>
                        </strong> </td>
                      <td height="25" align="center" valign="middle">&nbsp; <strong> 
                        <?=$nonactive_merchant?>
                        </strong> </td>
                      <td align="center" valign="middle">&nbsp;<strong> 
                        <?=$ready_merchant?>
                        </strong></td>
                      <td align="center" valign="middle">&nbsp;<strong> 
                        <?=$upload_merchant?>
                        </strong></td>
                      <td align="center" valign="middle">&nbsp;<strong><?=$appl_merchant?></strong></td>
                      <td height="25" align="center" valign="middle">&nbsp; <strong> 
                        <?=$total_merchant?>
                        </strong> </td>
                    </tr>
                  </table>
<br>
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