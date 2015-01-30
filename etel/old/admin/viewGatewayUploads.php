<?php 
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
// viewuploads.php:	The  page used to view uploaded files. 
include 'includes/sessioncheck.php';

$headerInclude = "companies";
include 'includes/header.php';

require_once( '../includes/function.php');
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";

$company_id = isset($HTTP_GET_VARS['companyname'])?$HTTP_GET_VARS['companyname']:"";
if ($company_id == "") {
	$company_id = isset($HTTP_POST_VARS['companyname'])?$HTTP_POST_VARS['companyname']:"";
}
$companies = isset($HTTP_GET_VARS['companies'])?$HTTP_GET_VARS['companies']:"";
if ($companies == "") {
	$companies = isset($HTTP_POST_VARS['companies'])?$HTTP_POST_VARS['companies']:"";
}
$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"";
if ($companytype == "") {
	$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"";
}
$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"";
if ($companytrans_type == "") {
	$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?$HTTP_POST_VARS['companytrans_type']:"";
}
$gatewayCompanyId = isset($HTTP_GET_VARS['gatewayCompanies'])?$HTTP_GET_VARS['gatewayCompanies']:"";
if ($gatewayCompanyId == "") {
	$gatewayCompanyId = isset($HTTP_POST_VARS['gatewayCompanies'])?$HTTP_POST_VARS['gatewayCompanies']:"";
}

$str_update = isset($HTTP_POST_VARS['update'])?$HTTP_POST_VARS['update']:"";
if ($str_update == "yes") {
	$str_document_ids = isset($HTTP_POST_VARS['document_ids'])?$HTTP_POST_VARS['document_ids']:"";
	$arr_document_ids = split(",", $str_document_ids);
	for ($i_loop=0;$i_loop<count($arr_document_ids);$i_loop++) {
		$i_document_id = $arr_document_ids[$i_loop];
		$str_status = isset($HTTP_POST_VARS["reject".$i_document_id])?$HTTP_POST_VARS["reject".$i_document_id]:"P";
		//print("status= $str_status");
		$str_reject_reason = isset($HTTP_POST_VARS["reject_reason".$i_document_id])?quote_smart($HTTP_POST_VARS["reject_reason".$i_document_id]):"";
		$str_query = "update cs_uploaded_documents set status = '$str_status', reject_reason = '$str_reject_reason' where file_id = $i_document_id";
		//print($str_query);
		if(!mysql_query($str_query,$cnn_cs)) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		$i_num_documents = 0;
		$str_query = "select count(distinct(file_type)) from cs_uploaded_documents where user_id = $company_id and status = 'A'";
		if(!($show_sql =mysql_query($str_query,$cnn_cs)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		if(mysql_num_rows($show_sql)>0)
		{
			$i_num_documents = mysql_result($show_sql, 0, 0);
		}

		$str_query = "update cs_companydetails set num_documents_uploaded = $i_num_documents where userId = $company_id";
		if(!mysql_query($str_query,$cnn_cs))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}

		//print($i_document_id." - ". $str_status ." - ". $str_reject_reason ."<br>");
	}
}
$myLicenceFileArray = array();
$myArticlesFileArray = array();
$myHistoryFileArray = array();
$myProfessionalReferenceFileArray = array();
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
		} else if ($showval1[0] == "Professional_Reference") {
			$myProfessionalReferenceFileArray[] = $showval1[1] ."#!_" .$showval1[2] ."#!_" .$showval1[3] ."#!_" .$showval1[4];
		}
		$str_document_ids .= $showval1[2] .",";
	}
	$str_document_ids = substr($str_document_ids, 0, strlen($str_document_ids) - 1);
}	
?>
<form name="viewUploadsForm" action="viewGatewayUploads.php" method="post">
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="59%">
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="75%" >
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Uploaded 
            Documents</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>

      <tr>
          <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5"><br>
		<table width="90%" cellspacing="0" cellpadding="0" >
		<tr><td align="left">
			  <table width="100%" cellpadding="0" cellspacing="0"  height="100" style="border:1px solid #d1d1d1">
                      <tr> 
                        <td align="right" valign="middle" height="30" width="25%" style="border-bottom:1px solid #d1d1d1" ><font face="verdana" size="1"><b>Document Type&nbsp;&nbsp;&nbsp;&nbsp;</b></font></td>
                        <td align="left" height="30" width="75%" style="border-bottom:1px solid #d1d1d1">
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr><td align='left'><font face="verdana" size="1"><b>Document Name</b></font></td>
							<td align='right' valign='middle' height='30' width='130'><font face='verdana' size='1'><b>Approval Status</b></font></td>
							<td align='center' valign='middle' height='30' width='150'><font face='verdana' size='1'><b>Comments</b></font></td></tr>
						</table>
						</td>
                      </tr>

					  <tr> 
                        <td align="right" valign="middle" height="30" width="25%" style="border-bottom:1px solid #d1d1d1" ><font face="verdana" size="1">Drivers 
                          License/Passport :&nbsp;</font></td>
                        <td align="left" height="30" width="75%" style="border-bottom:1px solid #d1d1d1">
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
<?php					for($i_loop=0;$i_loop<count($myLicenceFileArray);$i_loop++) {
							$str_document_details = split("#!_", $myLicenceFileArray[$i_loop]);
							print "<tr><td align='left'><a href='../UserDocuments/License/$str_document_details[0]' target='_blank'><font face='verdana' size='1'>$str_document_details[0]</font></a>&nbsp;</td>";
							print "<td align='right' valign='middle' height='30' width='130'><font face='verdana' size='1'>Approve<input type='radio' name='reject$str_document_details[1]' value='A'"; print $str_document_details[2] == "A" ? "checked" : "";
							print ">&nbsp;Reject<input type='radio' name='reject$str_document_details[1]' value='R'";
							print $str_document_details[2] == "R" ? "checked" : "";
							print "></font></td>";
							print "<td align='right' valign='middle' height='30' width='140'><font face='verdana' size='1'><textarea name='reject_reason$str_document_details[1]' rows='2' cols='15'>".  $str_document_details[3] ."</textarea></font></td></tr>";
							
						}
?>						
						</table>
						</td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" height="30" width="25%" style="border-bottom:1px solid #d1d1d1"><font face="verdana" size="1">Articles 
                          of Incorporation :&nbsp;</font></td>
                        <td align="left" height="30" width="75%" style="border-bottom:1px solid #d1d1d1">
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
<?php					for($j_loop=0;$j_loop<count($myArticlesFileArray);$j_loop++) {
							$str_document_details = split("#!_", $myArticlesFileArray[$j_loop]);
							print "<tr><td align='left'><a href='../UserDocuments/Articles/$str_document_details[0]' target='_blank'><font face='verdana' size='1'>$str_document_details[0]</font></a>&nbsp;</td>";
							print "<td align='right' valign='middle' height='30' width='130'><font face='verdana' size='1'>Approve<input type='radio' name='reject$str_document_details[1]' value='A'";
							print $str_document_details[2] == "A" ? "checked" : "";
							print ">&nbsp;Reject<input type='radio' name='reject$str_document_details[1]' value='R'";
							print $str_document_details[2] == "R" ? "checked" : "";
							print "></font></td>";
							print "<td align='right' valign='middle' height='30' width='140'><font face='verdana' size='1'><textarea name='reject_reason$str_document_details[1]' rows='2' cols='15'>". $str_document_details[3] ."</textarea></font></td></tr>";
						}
?>						
						</table>
						</td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" height="30" width="25%" style="border-bottom:1px solid #d1d1d1"><font face="verdana" size="1">Previous 
                          processing history&nbsp;&nbsp;&nbsp;<br>(if applicable) :&nbsp;</font></td>
                        <td align="left" height="30" width="75%" style="border-bottom:1px solid #d1d1d1">
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
<?php					for($k_loop=0;$k_loop<count($myHistoryFileArray);$k_loop++) {
							$str_document_details = split("#!_", $myHistoryFileArray[$k_loop]);
							print "<tr><td align='left'><a href='../UserDocuments/History/$str_document_details[0]' target='_blank'><font face='verdana' size='1'>$str_document_details[0]</font></a>&nbsp;</td>";
							print "<td align='right' valign='middle' height='30' width='130'><font face='verdana' size='1'>Approve<input type='radio' name='reject$str_document_details[1]' value='A'";
							print $str_document_details[2] == "A" ? "checked" : "";
							print ">&nbsp;Reject<input type='radio' name='reject$str_document_details[1]' value='R'";
							print $str_document_details[2] == "R" ? "checked" : "";
							print "></font></td>";
							print "<td align='right' valign='middle' height='30' width='140'><font face='verdana' size='1'><textarea name='reject_reason$str_document_details[1]' rows='2' cols='15'>". $str_document_details[3] ."</textarea></font></td></tr>";
						}
?>						
					</table>
					</td>
                      </tr>
                     <tr> 
                        <td align="right" valign="middle" height="30" width="25%"><font face="verdana" size="1">Personal Reference :&nbsp;</font></td>
                        <td align="left" height="30" width="750%">
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
<?php					for($l_loop=0;$l_loop<count($myProfessionalReferenceFileArray);$l_loop++) {
							$str_document_details = split("#!_", $myProfessionalReferenceFileArray[$l_loop]);
							print "<tr><td align='left'><a href='../UserDocuments/Contract/$str_document_details[0]' target='_blank'><font face='verdana' size='1'>$str_document_details[0]</font></a>&nbsp;</td>";
							print "<td align='right' valign='middle' height='30' width='130'><font face='verdana' size='1'>Approve<input type='radio' name='reject$str_document_details[1]' value='A'";
							print $str_document_details[2] == "A" ? "checked" : "";
							print ">&nbsp;Reject<input type='radio' name='reject$str_document_details[1]' value='R'";
							print $str_document_details[2] == "R" ? "checked" : "";
							print "></font></td>";
							print "<td align='right' valign='middle' height='30' width='140'><font face='verdana' size='1'><textarea name='reject_reason$str_document_details[1]' rows='2' cols='15'>". $str_document_details[3] ."</textarea></font></td></tr>";
						}
?>						
						</table>
						</td>
                      </tr> 
                      <tr> 
                    </table>
		  </td></tr>
		  <tr><td colspan="2" align="center" height="40"><a href="viewGatewayCompanyNext.php?gatewayCompanies=<?=$gatewayCompanyId?>&companymode=<?= $companytype?>&companytrans_type=<?= $companytrans_type?>&companyname=<?= $companies?>"><img SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></img></a>&nbsp;&nbsp;<input type="image" SRC="<?=$tmpl_dir?>/images/submit.jpg" border="0">
		  </td></tr>
		  </table>
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
<input type="hidden" name="update" value="yes">
<input type="hidden" name="document_ids" value="<?= $str_document_ids?>">
<input type="hidden" name="gatewayCompanies" value="<?= $gatewayCompanyId?>">
<input type="hidden" name="companyname" value="<?= $company_id?>">
<input type="hidden" name="companytrans_type" value="<?= $companytrans_type?>">
<input type="hidden" name="companymode" value="<?= $companytype?>">
<input type="hidden" name="companies" value="<?= $companies?>">
</form>
<br>
<?
include 'includes/footer.php';
?>