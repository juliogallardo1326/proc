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
// integrate.php:	This page functions for uploading the company transactions. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
$headerInclude= "transactions";	
include 'includes/header.php';

require_once( 'includes/function.php');




if($str_completed_uploading !="") {
	$str_qry = "update cs_companydetails set completed_uploading = '$str_completed_uploading' where userId = '".$companyInfo['userId']."'";
	if (!mysql_query($str_qry,$cnn_cs)) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
}
?>
<script language="javascript">
function func_batchtemplate() {
	document.FrmBatch.action="batchtemplate.php?cctype=pdf";
	document.FrmBatch.submit();
}

function isAdobeReaderInstalled() {
	var acrobat=new Object();

	// Set some base values
	acrobat.installed=false;
	acrobat.version='0.0';

	if (navigator.plugins && navigator.plugins.length)
	{
		for (x=0; x<navigator.plugins.length; x++)
		{
			if (navigator.plugins[x].description.indexOf('Adobe Acrobat') != -1)
			{
				acrobat.version=parseFloat(navigator.plugins[x].description.split('Version ')[1]);

				if (acrobat.version.toString().length == 1) acrobat.version+='.0';

				acrobat.installed=true;
				break;
			}
		}
	}
	else if (window.ActiveXObject)
	{
		for (x=2; x<10; x++)
		{
			try
			{
				oAcro=eval("new ActiveXObject('PDF.PdfCtrl."+x+"');");
				if (oAcro)
				{
					acrobat.installed=true;
					acrobat.version=x+'.0';
				}
			}
			catch(e) {}
		}

		try
		{
			oAcro4=new ActiveXObject('PDF.PdfCtrl.1');
			if (oAcro4)
			{
				acrobat.installed=true;
				acrobat.version='4.0';
			}
		}
		catch(e) {}
	}

	acrobat.ver4=(acrobat.installed && parseInt(acrobat.version) >= 4) ? true:false;
	acrobat.ver5=(acrobat.installed && parseInt(acrobat.version) >= 5) ? true:false;
	acrobat.ver6=(acrobat.installed && parseInt(acrobat.version) >= 6) ? true:false;
	acrobat.ver7=(acrobat.installed && parseInt(acrobat.version) >= 7) ? true:false;
	acrobat.ver8=(acrobat.installed && parseInt(acrobat.version) >= 8) ? true:false;
	acrobat.ver9=(acrobat.installed && parseInt(acrobat.version) >= 9) ? true:false; 
	/*if (acrobat.ver5)
	{
		// if Acrobat 5.0 or newer is installed, do Acrobat 5.0 stuff.
		document.write("Acrobat ver. >=5");
	}
	else*/
	if (acrobat.installed)
	{
		// do older Acrobat stuff
		return true;
	}
	else
	{
		// Acrobat is NOT installed.  Do something else.
		/*if (window.confirm("Adobe Reader is not installed in your System. You need it to open the Integration Guide. Click OK to install it now")) {
			window.open("","xxx.htm");
		}*/
		return false;
	}
}
</script>
<?php
$sql = "SELECT * FROM `cs_company_sites` WHERE `cs_company_id` = '".$companyInfo['userId']."' AND cs_hide = '0'";
if(!($result = mysql_query($sql,$cnn_cs)))
{
	print(mysql_errno().": ".mysql_error()."<BR>");
	print ($qry_update."<br>");
	print("Failed to access company URLs");
	exit();
}
else
{
	if(mysql_num_rows($result) > 0){

beginTable();
?>

<div align="left">
  <p><span class="intx">
    
    To Integrate with the 
    <?=$_SESSION['gw_title']?> 
  Order page,</span></p>
  <p align="center"><span class="intx">Click here to download <a href="gateway/<?=$_SESSION['gw_folder']?>documents/PaymentButtonIntegrationGuide.pdf" onClick="" class="intx1" target="_blank">Quick Payment Button Integration Guide</a><br />
    <br />
  Click here to download the <a href="gateway/<?=$_SESSION['gw_folder']?>documents/PaymentExtendedIntegrationGuide.pdf" onClick="" class="intx1" target="_blank">Extended Payment Integration Guide</a></span></p>
  <p align="left"><span class="intx"><BR />
    To Integrate with the
    <?=$_SESSION['gw_title']?>
  Password Management System,</span></p>
  <p align="center"><span class="intx">Click here to download the <a href="gateway/<?=$_SESSION['gw_folder']?>documents/PasswordManagementIntegrationGuide.pdf" onClick="" class="intx1" target="_blank">Password Management Integration Guide</a></span></p>
  <p align="left"><span class="intx">For all Integration-Related files including plugins and example code,</span></p>
  <p align="center"><span class="intx">Click here to download the <a href="gateway/<?=$_SESSION['gw_folder']?>documents/EtelegateGuides.zip" onclick="" class="intx1" target="_blank">Entire Etelegate Integration Package </a></span></p>
  <p align="center"><span class="intx">    Adobe Reader is requird to open the Integration Guide.<BR />
    <a href="http://www.adobe.com/products/acrobat/readstep2.html" target="_blank" class="intx1">Click here to download it</a><br>
    <br>
    </span>
    
    <?
endTable('Integration Guides',"");
beginTable();
?>
            </p>
</div>
<table width="400" cellpadding='5' cellspacing='0' class='lefttopright' height="57">
    <tr>
      <td height="25" colspan="3" align="center" valign="center" bgcolor="#78B6C2" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Reference Numbers for your websites </strong>&nbsp;</font></td>
    </tr>
    <tr>
      <td align="left" valign="center" height="30" width="50%"  class='cl1'><font face="verdana" size="1">Website&nbsp;</font></td>
      <td align="left" valign="center" height="30" width="50%"  class='cl1'><font face="verdana" size="1">Reference Number &nbsp;</font></td>
      </b>
      </font>
    </td>
    
    </tr>
    
    <?php

	while ($url = mysql_fetch_assoc($result))
	{	
	?>
    <tr>
      <td align="left" valign="center" height="30" width="50%"  class='cl1'><font face="verdana" size="1">
        <?=$url['cs_URL']?>
        </font></td>
      <td align="left" valign="center" height="30" width="50%"  class='cl1'><font face="verdana" size="1"><font color="#CC3300" size="1" face="verdana"><strong>
        <?=($url['cs_reference_ID'])?>
        </strong></font></td>
      </b>
      </font>
      </td>
    </tr>
    <?php
	}
	?>
</table>
<?php 
endTable('Integration Information',"");
	}
	else
	{
?>
<p>&nbsp;</p>
<table width="400" border="1" align="center">
  <tr>
    <td align="center"><p>Please Set up at least one website before using the Integration Guides.</p>
      <p><a href="addwebsiteuser.php">Website Setup </a> </p></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php
	}
}

include("includes/footer.php");
?>
