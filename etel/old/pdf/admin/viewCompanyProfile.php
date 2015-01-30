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
	$company_id = isset($HTTP_GET_VARS['company_id'])?$HTTP_GET_VARS['company_id']:"";
	if ($company_id == "") {
		$company_id = isset($HTTP_GET_VARS['companyname'])?$HTTP_GET_VARS['companyname']:"";
	}
	$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"";
	$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"";
	
	$qry_select_companies = "select * from cs_companydetails where userid=$company_id";
	if(!($show_sql =mysql_query($qry_select_companies)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
?>
<script language="javascript">
function emailsubmit() {
	//document.Frmcompany.action="viewBottom.php";
	document.Frmcompany.method="POST";
	document.Frmcompany.submit();
}	
</script>
<table border="0" align="center" cellpadding="0" width="98%" cellspacing="0" height="80%">
  <tr>
       <td width="100%" valign="top" align="center"  >
    &nbsp;
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
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

		 ?>
		<table width="98%" cellpadding="0" cellspacing="0" align="center"><tr>
		<td align="center" width="50%" valign="top" >
		<table cellpadding='5' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="center"  height="600">
<tr> 
                        <td height="25" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Company 
                          Informations </strong>&nbsp;</font></td>
                        <td height="25" class='cl1' align="left">&nbsp;</td>
						</tr>					  
						<tr height='30'>
                        <td width="191" height="25" align='left' class='cl1'><font face='verdana' size='1'><b>Company 
                          Name</b></font></td>
                        <td height="25" align='left'  class='cl1'><font face='verdana' size='1'>&nbsp;
                          <?=$showval[3]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25"  align='left'  class='cl1'><font face='verdana' size='1'><b>User 
                          Name</b></font></td>
                        <td height="25" align='left' class='cl1'><font face='verdana' size='1'>&nbsp;
                          <?=$showval[1]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25"  align='left'  class='cl1'><font face='verdana' size='1'><b>Password</b></font></td>
                        <td height="25" align='left'  class='cl1'><font face='verdana' size='1'>&nbsp;
                          <?=$showval[2]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align='left'  class='cl1'><font face='verdana' size='1'><b>Address</b></font></td>
                        <td height="25" align='left' class='cl1'><font face='verdana' size='1'>&nbsp;
                          <?=str_replace("\n",",\t",$showval[5]);?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align='left' class='cl1'><font face='verdana' size='1'><b>City</b></font></td>
                        <td height="25" align='left'  class='cl1'><font face='verdana' size='1'>&nbsp;
                          <?=str_replace("\n",",\t",$showval[6]);?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align='left' class='cl1'><font face='verdana' size='1'><b>State</b></font></td>
                        <td height="25" align='left'  class='cl1'><font face='verdana' size='1'>&nbsp;
                          <?=$state;?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align='left'  class='cl1'><font face='verdana' size='1'><b>Country</b></font></td>
                        <td height="25" align='left'  class='cl1'><font face='verdana' size='1'>&nbsp;
                          <?=str_replace("\n",",\t",$showval[8]);?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align='left'  class='cl1'><font face='verdana' size='1'><b>Zipcode</b></font></td>
                        <td height="25" align='left'  class='cl1'><font face='verdana' size='1'>&nbsp;
                          <?=str_replace("\n",",\t",$showval[9]);?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25"  align='left'  class='cl1' ><font face='verdana' size='1'><b>Fax 
                          Number</b></font></td>
                        <td height="25" align='left'  class='cl1' ><font face='verdana' size='1'>&nbsp;
                          <?=$showval[51]?>
                          </font></td>
                      </tr>
						<tr> 
                        <td height="25"  align='left'  class='cl1' ><font face='verdana' size='1'><b>Phone 
                          Number</b></font></td>
                        <td height="25" align='left'  class='cl1' ><font face='verdana' size='1'>&nbsp;
                          <?=$showval[4]?>
                          </font></td>
                      </tr>
					  <tr> 
                        <td height="25" align="left"   class='cl1' ><font face="verdana" size="1"><strong>Type 
                          Of Company</strong> &nbsp;</font></td>
                        <td height="25" align="left"  class='cl1' >&nbsp;<font face='verdana' size='1'>
                          <?=$showval[52]?>
                          </font></td>
                      </tr>					
					  <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Customer 
                          services phone number</strong>&nbsp;&nbsp;</font></td>
                        <td height="25" align="left"  class='cl1' >&nbsp;<font face='verdana' size='1'>
                          <?=$showval[54]?>
                          </font></td>
                      </tr>					  
					  <tr> 
                        <td height="25" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Rates 
                          & Fees</strong>&nbsp;</font></td>
	                        
                        <td height="25" align="left" class='cl1'>&nbsp;</td>
						</tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Charge 
                          Back - $</font></strong></td>
                        <td height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>&nbsp;$ 
                          <?=$showval[18]?>
                          </strong> </font></td>
                      </tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Credit $ </font></strong></td>
                        <td height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>&nbsp;$ 
                          <?=$showval[19]?>
                          </strong> </font></td>
                      </tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Transaction 
                          Fee - $</font></strong></td>
                        <td height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>&nbsp;$ 
                          <?=$showval[21]?>
                          </strong> </font></td>
                      </tr>
					  <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Discount 
                          Rate - %</font></strong></td>
                        <td height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<strong> 
                          <?=$showval[20]?>
                          %</strong></font></td>
                      </tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Reserve - %</font></strong></td>
                        <td height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>&nbsp; 
                          <?=$showval[22]?>
                          %</strong></font></td>
                      </tr>
					  <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Voice Authorization Fee - $</font></strong></td>
                        <td height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<strong>$ <?=$showval[23]?></strong></font></td>
                      </tr>
					  <tr> 
                        <td height="25" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Process 
                          Informations </strong>&nbsp;</font></td>
                        <td height="25" class='cl1' align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Merchant Active</font></strong></td>
                        <td height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[28] ==1 ? "Yes" : "No"?>
                          </font></td>
                      </tr>
						<tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Billing Descriptor Name</font></strong></td>
                        <td height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                          <?=$showval[48]?></font></td>
                      </tr>
					  <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Setup Fees Paid</font></strong></td>
                        <td height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                          <?=$showval[82]?></font></td>
                      </tr>
					  <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Merchant 
                          Type </font></strong></td>
                        <td height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <strong> 
                          <?php if($showval[27] == "ecom") print"Ecommerce"; else if($showval[27] == "pmtg") print "Gateway"; else if($showval[27] == "tele") print"Telemarketing"; else if($showval[27] == "trvl") print"Travel"; else if($showval[27] == "phrm") print"Pharmacy"; else if($showval[27] == "game") print"Gaming"; else if($showval[27] == "adlt") print"Adult";  else print"&nbsp;";?>
                          </strong> </font></td>
                      </tr>
<?php			if($showval[27] == "tele") { ?>					
					  <tr> 
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Customer 
                          Service Cancel(auto)</font></strong></td>
                        <td align="left" height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[24] == "Y" ? "Yes - $showval[25] Days" : "No"?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Shipping 
                          Cancel(auto)</font></strong></td>
                        <td align="left" height="25" width="191" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[31] == "Y" ? "Yes - $showval[32] Days" : "No"?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Auto 
                          Approve Pass Orders&nbsp;</font></strong></td>
                        <td align="left" height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[26] == "Y" ? "Yes" : "No"?>
                          </font></td>
                      </tr>
<?php
					}
?>                    
					  <tr> 
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Expected 
                          Monthly Volume ($)&nbsp;</font></strong></td>
                        <td align="left" height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[30]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Average 
                          Ticket&nbsp;</font></strong></td>
                        <td align="left" height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[38]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Charge 
                          Back %&nbsp;</font></strong></td>
                        <td align="left" height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[39]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Previous 
                          Processing&nbsp;</font></strong></td>
                        <td align="left" height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[40]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Recurring 
                          Billing&nbsp;</font></strong></td>
                        <td align="left" height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[41]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Currently 
                          Processing&nbsp;</font></strong></td>
                        <td align="left" height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[42]?>
                          </font></td>
                      </tr>
	<?php 

		$myLicenceFileArray = array();
		$myArticlesFileArray = array();
		$myHistoryFileArray = array();
		$myProfessionalReferenceFileArray = array();
		$str_qry = "select file_type, file_name from cs_uploaded_documents where user_id = $company_id and status = 'A'";
		if(!($show_sql1 =mysql_query($str_qry,$cnn_cs)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		if(mysql_num_rows($show_sql1)>0)
		{
			while($showval1 = mysql_fetch_row($show_sql1)) 
			{
				if ($showval1[0] == "License") {
					$myLicenceFileArray[] = $showval1[1];
				} else if ($showval1[0] == "Articles") {
					$myArticlesFileArray[] = $showval1[1];
				} else if ($showval1[0] == "History") {
					$myHistoryFileArray[] = $showval1[1];
				} else if ($showval1[0] == "Professional_Reference") {
					$myProfessionalReferenceFileArray[] = $showval1[1];
				}
			}
		}	
?>					
						<tr> 
                        <td height="25" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Uploaded 
                          Documents </strong>&nbsp;</font></td>
	                        
                        <td height="25" align="left" class='cl1'>&nbsp;</td>
						</tr>						
						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Drivers 
                          License/Passport &nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'><font face="verdana" size="1"> 
                          <strong> 
                          <?php for($i_loop=0;$i_loop<count($myLicenceFileArray);$i_loop++) {
							print "&nbsp;<a href='../gateway/".$_SESSION['gw_folder']."UserDocuments/License/$myLicenceFileArray[$i_loop]' target='_blank'>$myLicenceFileArray[$i_loop]</a><br>";
						}
?>
                          </strong> &nbsp;</font></td>
						</tr>
						
						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Articles 
                          of Incorporation&nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'><font face="verdana" size="1"> 
                          <strong> 
                          <?php for($j_loop=0;$j_loop<count($myArticlesFileArray);$j_loop++) {
							print "&nbsp;<a href='../gateway/".$_SESSION['gw_folder']."UserDocuments/Articles/$myArticlesFileArray[$j_loop]' target='_blank'>$myArticlesFileArray[$j_loop]</a><br>";
						}
?>
                          </strong> &nbsp;</font></td>
						</tr>
						
						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Previous 
                          processing history (if applicable)&nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'><font face="verdana" size="1"> 
                          <strong> 
                          <?php for($k_loop=0;$k_loop<count($myHistoryFileArray);$k_loop++) {
							print "&nbsp;<a href='../gateway/".$_SESSION['gw_folder']."UserDocuments/History/$myHistoryFileArray[$k_loop]' target='_blank'>$myHistoryFileArray[$k_loop]</a><br>";
						}
?>
                          </strong> &nbsp;</font></td>
						</tr>
						
<!--						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Signed 
                          merchant Contract&nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'><font face="verdana" size="1"> 
                          <?php for($l_loop=0;$l_loop<count($myContractFileArray);$l_loop++) {
							print "&nbsp;<a href='../gateway/".$_SESSION['gw_folder']."UserDocuments/Contract/$myContractFileArray[$l_loop]' target='_blank'>$myContractFileArray[$l_loop]</a><br>";
						}
?>
                          &nbsp;</font></td>
						</tr>
-->
						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Professional Reference 
                          &nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'><font face="verdana" size="1"> 
                          <strong> 
                          <?php for($l_loop=0;$l_loop<count($myProfessionalReferenceFileArray);$l_loop++) {
							print "&nbsp;<a href='../gateway/".$_SESSION['gw_folder']."UserDocuments/Professional_Reference/$myProfessionalReferenceFileArray[$l_loop]' target='_blank'>$myProfessionalReferenceFileArray[$l_loop]</a><br>";
						}
?>
                          </strong> &nbsp;</font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Wire Money?
                          &nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'><font face="verdana" size="1"> 
                          <?= $showval[49] == "Y" ? "Yes" : "No"?>
                          &nbsp;</font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Merchant Contract Accepted
                          &nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'><font face="verdana" size="1"> 
                          <?= $showval[50] == "Yes" ? "Yes" : "No"?>
                          &nbsp;</font></td>
						</tr>						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Completed Merchant Application?
                          &nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'><font face="verdana" size="1"> 
                          <?= $showval[77] == "1" ? "Yes" : "No"?>
                          &nbsp;</font></td>
						</tr>
                      <input type="hidden" name="username" value="<?=$showval[1]?>"></input>
                      <input type="hidden" name="email" value="<?=$showval[10]?>"></input>
                      <input type="hidden" name="password" value="<?=$showval[2]?>" ></input>
                    </table>
		</td>
		<td align="center" width="50%" valign="top"  height="600">
		<table  width="100%"  height="600" class='lefttopright' cellpadding='5' cellspacing='0' valign="center" style='margin-top: 15; margin-bottom: 5'>
						<tr>
                        <td width="80%" height="25" align="left" valign="center" class='cl1'><font face="verdana" size="1" color="#000000"><strong>Suspended 
                          User</strong>&nbsp;</font></td>
                        <td width="20%" height="25" align="left" class='cl1'>&nbsp;<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          <strong><?=$showval[11] == "Yes" ? "Yes" : "No"?></strong>
                          </font></td>
						</tr>
						<tr>
                        <td width="80%" height="25" align="left" valign="center" class='cl1'><font face="verdana" size="1" color="#000000"><strong>Unsubscribe from mailing list</strong>&nbsp;</font></td>
                        <td width="20%" height="25" align="left" class='cl1'>&nbsp;<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          <strong><?=$showval[76] == 0 ? "Yes" : "No"?>
                          </strong> </font></td>
						</tr>						
						<tr>
                        <td height="25" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Web 
                          Site Informations </strong>&nbsp;</font></td>
                        <td height="25" class='cl1' align="left">&nbsp;</td>
						</tr>                      
						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Email&nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                          <a href="massmail1.php?mailto_id=<?=$company_id?>&selectAll=1"><strong><?=$showval[10]?></strong></a>
                          </font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">URL1&nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                          <?=$showval[43]?>
                          </font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">URL2&nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                          <?=$showval[44]?>
                          </font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">URL3&nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                          <?=$showval[45]?>
                          </font></td>
						</tr>                      <tr> 
                        <td height="25" align='left'  class='cl1'><font face='verdana' size='1'><b>Signup 
                          Date & Time</b></font></td>
                        <td height="25" align='left' class='cl1'><font face='verdana' size='1'>&nbsp;
                          <?=$showval[46] == "0000-00-00 00:00:00" ? "Not Available" : func_get_date_time_12hr($showval[46])?>
                          </font></td>
                      </tr>
<?php			if($showval[27] == "tele") {
?>					
					  <tr> 
                        <td height="25" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Letter 
                          template setup</strong>&nbsp;</font></td>
                        <td height="25" class='cl1' align="left">&nbsp;</td>
						</tr>
					  
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Merchant Name</font></strong></td>
                        <td height="25" class='cl1'> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                          <?=$showval[13]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Toll Free Number</font></strong></td>
                        <td height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                          <?=$showval[14]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Retrieval Number </font></strong></td>
						  
                        <td height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                          <?=$showval[15]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Security 
                          Number </font></strong></td>
                        <td height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                          <?=$showval[16]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Processor</font></strong></td>
                        <td height="25" class='cl1'><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                          <?=$showval[17]?>
                          </font></td>
                      </tr>
                        <tr> 
                        <td height="25" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Verification 
                          Script </strong>&nbsp;</font></td>
                        <td height="25" align="left" class='cl1'>&nbsp;</td>
						</tr>						
						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Package 
                          Name &nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'>&nbsp;<font face="verdana" size="1">
                          <?=$showval[33]?>
                          &nbsp;</font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Package 
                          Product Service &nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'>&nbsp;<font face="verdana" size="1">
                          <?=$showval[34]?>
                          &nbsp;</font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Package 
                          Price &nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'>&nbsp;<font face="verdana" size="1">
                          <?=$showval[35]?>
                          &nbsp;</font></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Refund 
                          Policy &nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'>&nbsp;<font face="verdana" size="1">
                          <?=$showval[36]?>
                          &nbsp;</font></td>
						</tr>
						
						<tr>
                        <td align="left" valign="center" height="25" class='cl1'><strong><font face="verdana" size="1">Description 
                          &nbsp;</font></strong></td>
						<td align="left" height="25" class='cl1'>&nbsp;<font face="verdana" size="1">
                          <?=$showval[37]?>
                          &nbsp;</font></td>
						</tr>
<?				}
?>					  <tr> 
                        <td height="25" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>User 
                          Informations </strong>&nbsp;</font></td>
                        <td height="25" class='cl1' align="left">&nbsp;</td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1">	
                          <strong>Your First Name</strong></font></td>
                        <td height="25" align="left"   class='cl1' ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[69]?> <?=$showval[63]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1">	
                          <strong>Your Last Name</strong></font></td>
                        <td height="25" align="left"  class='cl1' ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[64]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Date 
                          of birth</strong></font></td>
                        <td height="25" align="left"  class='cl1' >&nbsp;<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          <?=$showval[70]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Sex</strong></font></td>
                        <td height="25" align="left"  class='cl1' ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[71]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Address</strong></font></td>
                        <td height="25" align="left"  class='cl1' ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[72]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Zipcode</strong></font></td>
                        <td height="25" align="left"  class='cl1' ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[73]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1">	
                          <strong>What is your job title or position?</strong></font></td>
                        <td height="25" align="left"  class='cl1' ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[65]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"   class='cl1' ><font face="verdana" size="1"><strong>Contact 
                          email address</strong></font></td>
                        <td height="25" align="left"  class='cl1' ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[66]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Telephone 
                          number</strong></font></td>
                        <td height="25" align="left"  class='cl1' ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[67]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Residence 
                          Number</strong></font></td>
                        <td height="25" align="left"  class='cl1' ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[74]?>
                          </font> </td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Fax Number</strong></font></td>
                        <td height="25" align="left"  class='cl1' >&nbsp;<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          <?=$showval[75]?>
                          </font> </td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Where 
                          did you hear about Etelegate.com</strong></font></td>
                        <td height="25" align="left"  class='cl1' >&nbsp;<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
<?php					if($showval[47] == "rsel") {
 							print "Reseller";						
						} else if($showval[47] == "other"){
							print "Others";
						} else {
							print $showval[47];
						}
  ?>                          </font></td>
                      </tr>
					  <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Reseller/Other Details</strong></font></td>
                        <td height="25" align="left"  class='cl1' >&nbsp;<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
 <?php					print $showval[81];
  ?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Bank 
                          Processing Informations </strong>&nbsp;</font></td>
                        <td height="25" class='cl1' align="left">&nbsp;</td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><strong><font face="verdana" size="1">Bank Name</font></strong></td>
                        <td height="25" align="left"  class='cl1' >&nbsp;<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          <?=$showval[55]?>
                          </font></td>
                      </tr>
					<tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Beneficiary Name</strong></font></td>
                        <td height="25" align="left"  class='cl1' ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[79]?>
                          </font></td>
                      </tr>
						<tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Name On Bank Account</strong></font></td>
                        <td height="25" align="left"  class='cl1' ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[80]?>
                          </font></td>
                      </tr>					  <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          address</strong></font></td>
                        <td height="25" align="left"  class='cl1' ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$showval[57]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          country</strong></font></td>
                        <td height="25" align="left"  class='cl1' >&nbsp;<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          <?=$showval[58]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          telephone number</strong></font></td>
                        <td height="25" align="left"  class='cl1' >&nbsp;<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          <?=$showval[59]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          sort code / Branch Code</strong></font></td>
                        <td height="25" align="left"  class='cl1' >&nbsp;<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          <?=$showval[60]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          Account number</strong></font></td>
                        <td height="25" align="left"  class='cl1' >&nbsp;<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          <?=$showval[61]?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td height="25" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          Swift Code</strong></font></td>
                        <td height="25" align="left"  class='cl1' >&nbsp;<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          <?=$showval[62]?>
                          </font></td>
                      </tr>
<?php 		if($showval[47] == "rsel") { ?>				
				<tr> 
				  <td align="center" valign="middle" height="30" class="cl1" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Reseller Rates & Fees 
					Informations</strong></font></td>
				  <td align="left" valign="center" height="30" class="cl1">&nbsp;</td>
				</tr>
				<tr> 
				        <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total 
                          merchant discount rate</strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">&nbsp;<?= $showval[85] ?></font></td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total reseller discount rate</strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">&nbsp;<?= $showval[86] ?></font></td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total discount rate</strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">&nbsp;<?=$showval[87]?></font></td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total merchant transaction fee</strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">&nbsp;<?=$showval[88]?></font></td>
				</tr>
				<tr> 
				 <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total reseller transaction fee</strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">&nbsp;<?= $showval[89] ?></font></td>
				</tr>
				<tr>
				  <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total transaction fee</strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">&nbsp;<?= $showval[90] ?></font></td>
				</tr>
<?php 		} 	?>                    </table>
		</td></tr></table>
		<center>
		<table align="center">
		<tr><td align="center" valign="center" height="30" colspan="2" ><a href="#" onclick="window.history.back()"><img  SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>&nbsp;&nbsp;<a href="editCompanyProfile.php?company_id=<?=$company_id?>&companyname=<?= $company_id?>&companymode=<?= $companytype?>&companytrans_type=<?= $companytrans_type?>""><img  SRC="<?=$tmpl_dir?>/images/editcompanydetails.gif" border="0"></a></td></tr>	
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