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
// telescript.php:	The  page used to create tha tele script. 
$headerInclude="startHere";
include("includes/header.php");
$type = (isset($HTTP_GET_VARS['type'])?quote_smart($HTTP_GET_VARS['type']):"profile");
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$company_id = $sessionlogin ; 

$myLicenceFileArray = array();
$myArticlesFileArray = array();
$myHistoryFileArray = array();
$myProfessionalReferenceFileArray = array();
$str_document_ids = "";
$str_qry = "select file_type, file_name, file_id, status, reject_reason from cs_uploaded_documents where ud_en_ID = ".$companyInfo['en_ID']."";
if(!($show_sql =mysql_query($str_qry,$cnn_cs)))
{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
if(mysql_num_rows($show_sql)>0) {
	while($showval1 = mysql_fetch_row($show_sql)) {
		if ($showval1[0] == "License") {
			$myLicenceFileArray[] = $showval1[1] ."#!_" .$showval1[2] ."#!_" .$showval1[3] ."#!_" .$showval1[4];
		} else if ($showval1[0] == "Articles") {
			$myArticlesFileArray[] = $showval1[1] ."#!_" .$showval1[2] ."#!_" .$showval1[3] ."#!_" .$showval1[4];
		} else if ($showval1[0] == "History") {
			$myHistoryFileArray[] = $showval1[1] ."#!_" .$showval1[2] ."#!_" .$showval1[3] ."#!_" .$showval1[4];
		} else if ($showval1[0] == "Contract") {
			$myContractFileArray[] = $showval1[1] ."#!_" .$showval1[2] ."#!_" .$showval1[3] ."#!_" .$showval1[4];
		} else if ($showval1[0] == "Professional_Reference") {
			$myProfessionalReferenceFileArray[] = $showval1[1] ."#!_" .$showval1[2] ."#!_" .$showval1[3] ."#!_" .$showval1[4];
		}
		$str_document_ids .= $showval1[2] .",";
	}
	$str_document_ids = substr($str_document_ids, 0, strlen($str_document_ids) - 1);
}	
	beginTable();

?>
<table width="100%" cellpadding="0" cellspacing="0"  height="100" style="border:1px solid #d1d1d1">
                      <tr> 
                        <td align="right" valign="middle" height="30" width="180" style="border-bottom:1px solid #d1d1d1" ><font face="verdana" size="1"><b>Document Type&nbsp;&nbsp;&nbsp;&nbsp;</b></font></td>
                        <td align="left" height="30" style="border-bottom:1px solid #d1d1d1">
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr><td width="150"><font face="verdana" size="1"><b>Document Name</b></font></td>
							<td align='left' valign='middle' height='30'><font face='verdana' size='1'><b>Approval Status</b></font></td>
							<td align='left' valign='middle' height='30'><font face='verdana' size='1'><b>Comments</b></font></td>
							</tr>
						</table>
						</td>
                      </tr>
<?php foreach($myLicenceFileArray as $myLicenceFile){ ?>			  
					  <tr> 
                        <td width="180" height="30" align="right" valign="middle" style="border-bottom:1px solid #d1d1d1" ><font face="verdana" size="1">Drivers 
                          License/Passport :&nbsp;</font></td>
                        <td align="left" height="30" style="border-bottom:1px solid #d1d1d1">
						<table width="400%" cellpadding="0" cellspacing="0" border="0">
<?php			
							$str_document_link="";
							$str_document_details = split("#!_", $myLicenceFile);
							if($str_document_details[0]) $str_document_link = 'gateway/'.$_SESSION['gw_folder'].'UserDocuments/License/'.$str_document_details[0];
							$str_filetxt = substr($str_document_details[0],0,20);
							if(!$str_document_details[0]) $str_filetxt= "Not Uploaded";
							if ($str_document_details[2] == "A") {
								$str_document_details[2] = "Approved";
							} else if ($str_document_details[2] == "R") {
								$str_document_details[2] = "Rejected";
							} else if ($str_document_details[2] == "P") {
								$str_document_details[2] = "Pending";
							}

?>						
<tr><td width="150"><a href='<?=$str_document_link?>' target='_blank'><font face='verdana' size='1'><?=$str_filetxt?></font></a>&nbsp;</td>
						<td align='left' valign='middle' height='30'><font face='verdana' size='1'><?=$str_document_details[2]?></font></td>
						<td align='left' valign='middle' height='30'><font face='verdana' size='1'><?=$str_document_details[3]?></font></td>
</tr>
						</table>
						</td>
                      </tr>
<?php } foreach($myArticlesFileArray as $myArticlesFile){ ?>			
                      <tr> 
                        <td width="180" height="30" align="right" valign="middle" style="border-bottom:1px solid #d1d1d1"><font face="verdana" size="1">Articles 
                          of Incorporation :&nbsp;</font></td>
                        <td align="left" height="30" style="border-bottom:1px solid #d1d1d1">
						<table width="400%" cellpadding="0" cellspacing="0" border="0">
<?php			
							$str_document_link="";
							$str_document_details = split("#!_", $myArticlesFile);
							if($str_document_details[0]) $str_document_link = 'gateway/'.$_SESSION['gw_folder'].'UserDocuments/Articles/'.$str_document_details[0];
							$str_filetxt = substr($str_document_details[0],0,20);
							if(!$str_document_details[0]) $str_filetxt= "Not Uploaded";
							if ($str_document_details[2] == "A") {
								$str_document_details[2] = "Approved";
							} else if ($str_document_details[2] == "R") {
								$str_document_details[2] = "Rejected";
							} else if ($str_document_details[2] == "P") {
								$str_document_details[2] = "Pending";
							}

?>						
<tr><td width="150"><a href='<?=$str_document_link?>' target='_blank'><font face='verdana' size='1'><?=$str_filetxt?></font></a>&nbsp;</td>
						<td align='left' valign='middle' height='30'><font face='verdana' size='1'><?=$str_document_details[2]?></font></td>
						<td align='left' valign='middle' height='30'><font face='verdana' size='1'><?=$str_document_details[3]?></font></td>
</tr>
						</table>
						</td>
                      </tr>
<?php } foreach($myHistoryFileArray as $myHistoryFile){ ?>			

                      <tr> 
                        <td width="180" height="30" align="right" valign="middle" style="border-bottom:1px solid #d1d1d1"><font face="verdana" size="1">Previous 
                          processing history&nbsp;&nbsp;&nbsp;<br>
                        (if applicable) :&nbsp;</font></td>
                        <td align="left" height="30" style="border-bottom:1px solid #d1d1d1">
						<table width="400%" cellpadding="0" cellspacing="0" border="0">
<?php						
							$str_document_link="";
							$str_document_details = split("#!_", $myHistoryFile);
							if($str_document_details[0]) $str_document_link = 'gateway/'.$_SESSION['gw_folder'].'UserDocuments/History/'.$str_document_details[0];
							$str_filetxt = substr($str_document_details[0],0,20);
							if(!$str_document_details[0]) $str_filetxt= "Not Uploaded";
							if ($str_document_details[2] == "A") {
								$str_document_details[2] = "Approved";
							} else if ($str_document_details[2] == "R") {
								$str_document_details[2] = "Rejected";
							} else if ($str_document_details[2] == "P") {
								$str_document_details[2] = "Pending";
							}

?>						
<tr><td width="150"><a href='<?=$str_document_link?>' target='_blank'><font face='verdana' size='1'><?=$str_filetxt?></font></a>&nbsp;</td>
						<td align='left' valign='middle' height='30'><font face='verdana' size='1'><?=$str_document_details[2]?></font></td>
						<td align='left' valign='middle' height='30'><font face='verdana' size='1'><?=$str_document_details[3]?></font></td>
</tr>
						</table>
					</td>
                      </tr>
<?php } foreach($myContractFileArray as $myContractFile){ ?>			

                      <tr> 
                        <td width="180" height="30" align="right" valign="middle" style="border-bottom:1px solid #d1d1d1"><font face="verdana" size="1">Merchant Contract 
                          &nbsp;&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" style="border-bottom:1px solid #d1d1d1">
						<table width="400%" cellpadding="0" cellspacing="0" border="0">
<?php						
							$str_document_link="";
							$str_document_details = split("#!_", $myContractFile);
							if($str_document_details[0]) $str_document_link = 'gateway/'.$_SESSION['gw_folder'].'UserDocuments/Contract/'.$str_document_details[0];
							$str_filetxt = substr($str_document_details[0],0,20);
							if(!$str_document_details[0]) $str_filetxt= "Not Uploaded";
							if ($str_document_details[2] == "A") {
								$str_document_details[2] = "Approved";
							} else if ($str_document_details[2] == "R") {
								$str_document_details[2] = "Rejected";
							} else if ($str_document_details[2] == "P") {
								$str_document_details[2] = "Pending";
							}

?>						
<tr><td width="150"><a href='<?=$str_document_link?>' target='_blank'><font face='verdana' size='1'><?=$str_filetxt?></font></a>&nbsp;</td>
						<td align='left' valign='middle' height='30'><font face='verdana' size='1'><?=$str_document_details[2]?></font></td>
						<td align='left' valign='middle' height='30'><font face='verdana' size='1'><?=$str_document_details[3]?></font></td>
</tr>
						</table>
					</td>
                      </tr><? } ?>
<!--                      <tr> 
                        <td align="right" valign="middle" height="30" width="50%"><font face="verdana" size="1">Signed 
                          merchant Contract :&nbsp;</font></td>
                        <td align="left" height="30" width="50%">
<?php		//			for($l_loop=0;$l_loop<count($myContractFileArray);$l_loop++) {
						//	print "&nbsp;<a href='UserDocuments/Contract/$myContractFileArray[$l_loop]' target='_blank'>$myContractFileArray[$l_loop]</a><br>";
				//		}
?>						
						</td>
                      </tr>  -->
             
                      <tr> 
                  </table>
<?php endTable("View Uploaded Documents","uploadDocuments.php",0,1) ?>
<?
include 'includes/footer.php';
?>