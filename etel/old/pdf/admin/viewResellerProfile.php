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
// modifyReseller.php:	This admin page functions for adding  the company user. 
include("includes/sessioncheck.php");

$headerInclude="reseller";
include("includes/header.php");
include("includes/message.php");
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$return_reseller_id="";
if($sessionAdmin!="")
{	
	$returnid = isset($HTTP_GET_VARS["returnid"])?$HTTP_GET_VARS["returnid"]:"";
	$i_reseller_id = isset($HTTP_GET_VARS["reseller_id"])?$HTTP_GET_VARS["reseller_id"]:"";
	
	$qry_selectdetails = "select * from cs_resellerdetails where reseller_id = $i_reseller_id";	
	if (!($rst_selectdetails = mysql_query($qry_selectdetails)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
if($show_select_value = mysql_fetch_array($rst_selectdetails)){ 
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="60%" >
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="80%">
      <tr>
        <td width="100%" height="22">&nbsp;
         
        </td>
      </tr>
      <tr>
        <td width="100%" valign="top" align="left">
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		        <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Reseller Details</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5"><br>		
		<form name="FrmProfile" action="viewReseller.php" method="get">
		<input type="hidden" name="return_reseller_id" value="<?=$returnid?>">
	 <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100"><br>  
		 <tr>
		  <td height="70"  valign="top" align="left"  width="50%">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr> 
			  <td align="center" valign="middle" height="30" class="rightbottomtop" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Company 
				Informations</strong></font></td>
			  <td align="left" valign="center" height="30" class="rightbottomtop">&nbsp;</td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Company 
				Name&nbsp;</font></td>
			                  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[6]?></font></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Contact 
				Name&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[7]?></font></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;User 
				Name&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[3]?>
				</font></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Password&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[4]?></font></td>
			</tr>
<!--
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Retype 
				Password&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp; 
				<input name="repassword" type="text" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[4]?>" ></td>
			</tr>
            <tr> 
				  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Confirm 
					email address&nbsp;</font></td>
				  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp; 
					<input name="confirmemail" type="text" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[8]?>"></td>
				</tr>
-->							
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Phone 
				Number&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[9]?></font></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;New 
				merchant applications &nbsp;monthly&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[11]?></font></td>
			</tr>
			<tr> 
			  <td align="center" valign="middle" height="30" class="cl1" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Website 
				Informations</strong></font></td>
			  <td align="left" valign="center" height="30" class="cl1">&nbsp;</td>
			</tr>
			<tr> 
				  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Email 
					Address&nbsp;</font></td>
				  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[8]?></font></td>
				</tr>
				<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;URL 1&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[10]?></font></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;URL 2&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[42]?></font></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;URL 3&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[43]?></font></td>
			</tr>
				<tr> 
			  <td align="center" valign="middle" height="30" class="cl1" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Bank 
				Informations</strong></font></td>
			  <td align="left" valign="center" height="30" class="cl1">&nbsp;</td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">&nbsp;With 
				which bank do you hold a &nbsp;company account?</font></td>
			  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[26]?></font></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">	
				&nbsp;If 'Other', please specify:&nbsp;&nbsp;</font></td>
			  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[27]?></font></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">	
				&nbsp; Beneficiary Name:&nbsp;&nbsp;</font></td>
			  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[28]?></font></td>
			</tr>
			<tr>
			  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">	
				&nbsp; Name On Bank Account:&nbsp;&nbsp;</font></td>
			  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[29]?></font></td>
			</tr>
			<tr>
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">	
			&nbsp; Bank Address:&nbsp;&nbsp;</font></td>
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[30]?></font></td>
		  </tr>
		  <tr>
	  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">	
		&nbsp; Bank Country:&nbsp;&nbsp;</font></td>
	  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[31]?></font></td>
		  </tr>
		  <tr>
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">	
			&nbsp; Bank Telephone Number:&nbsp;&nbsp;</font></td>
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[32]?></font></td>
		  </tr>
		<tr>
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">	
			&nbsp; Sort Code/Branch Number:&nbsp;&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[33]?></font></td>
		  </tr>
		  <tr>
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">	
			&nbsp; Bank Account Number: &nbsp;&nbsp;</font></td>
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[34]?></font></td>
		  </tr>
		  <tr>
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">	
			&nbsp; Bank Swift Code:&nbsp;&nbsp;</font></td>
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[35]?></font></td>
		  </tr> 
		  </table>
		  </td>
		  <td valign="top" align="left"  width="50%">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr> 
		  <td align="center" valign="middle" height="30" class="rightbottomtop" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Subscription 
			Informations</strong></font></td>
		  <td align="left" valign="center" height="30" class="rightbottomtop">&nbsp;</td>
		</tr>
		<tr> 
		  <td align="left" valign="center" height="30" width="50%" class="cl1" ><font face="verdana" size="1">&nbsp;Unsubscribe 
			Mails&nbsp;</font></td>
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[25]==1?"No":"Yes"?></font></td>
		</tr> 
		  <tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1" ><font face="verdana" size="1">&nbsp;Suspend Reseller&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[44]==1?"Yes":"No"?></font></td>
			</tr>
		  <tr> 
		  <td align="center" valign="middle" height="30" class="cl1" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Customer 
			Informations</strong></font></td>
		  <td align="left" valign="center" height="30" class="cl1">&nbsp;</td>
		</tr>
			<tr><td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Title&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[12]?></font></td>
		  </tr>
		  <tr>
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;First 
			Name&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[13]?></font></td>
		  </tr>
		  <tr>
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Last 
			Name&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[14]?></font></td>
		  </tr>
		  <tr>
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Sex&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[15]?></font></td>
		  </tr>
		  <tr>
		  <td align="left" valign="center" height="90" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Address&nbsp;</font></td>
		  <td align="left" valign="center" height="90" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[2]?></font></td>
		  </tr>
		  <tr>
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Zipcode 
			&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[16]?></font></td>
		  </tr>
		  <tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Job 
				Title &nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[17]?></font></td>
			</tr>
			<tr> 
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Contact 
			Email &nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[8]?></font></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Residence 
				Phone &nbsp;</font></td>
				<td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[18]?></font></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Fax Number&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$show_select_value[19]?></font> 
			  </td>
			</tr>		
			</table>
		  </td>
		  </tr>
		  <tr><td height="40" valign="bottom" align="center" colspan="2"><input type="image" id="modifyuser" SRC="<?=$tmpl_dir?>/images/back.jpg"></input>
		   </td>
		</tr>
		</table>
		</form>
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
	 </table><br>
	 </td>
	</tr>
</table>

<?php
	}
include("includes/footer.php");
}
?>