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
// massmail2.php:	This admin page functions for mailing the company. 
include 'includes/sessioncheck.php';
$disablePostChecks=true;
include '../includes/dbconnection.php';
$headerInclude = "mail";
include 'includes/header.php';



$show_sql =mysql_query("select distinct email,companyname from cs_companydetails order by email",$cnn_cs);
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
?>
<script language="javascript">
function validation(){
  
  if(document.Frmlogin.fromaddress.value==""){
    alert("Please enter From address")
    document.Frmlogin.fromaddress.focus();
	return false;
  }
  if(document.Frmlogin.subject.value==""){
    alert("Please enter From subject")
    document.Frmlogin.subject.focus();
	return false;
  }
  if(!func_isEmail(document.Frmlogin.fromaddress.value)){
    alert("Please enter email address for From")
    document.Frmlogin.fromaddress.focus();
	return false;
  }
   if(document.Frmlogin.optAttachments.length>=0){
	strFiles = ",";	
	for(i=0;i<document.Frmlogin.optAttachments.length;i++){
		if(document.Frmlogin.optAttachments[i].value != ""){
			strFiles = strFiles + document.Frmlogin.optAttachments[i].value+",";
		}
	}
	
	document.Frmlogin.attachments.value = strFiles;
 }else{
	document.Frmlogin.attachments.value = ",";
 }
 return true;
}
function func_isEmail(emailval)
{
	var tempStr,icount;
	var blnmail,blnperiod; 
	var lastoccofperiod,maxthree;
	var ampicount=0;
	var amppos;
	var servername = 1;
	var dots;
	icount=emailval.length;
	blnperiod = 1;
	maxthree = 1;
	specialchar = 0
	lastoccofperiod = 0;
	if (icount==0)
	{
		return true;
	}
	for(i=0;i<icount;i++)
	{
		tempStr = emailval.charAt(i);
		if ((tempStr >='a')&&(tempStr <='z'))
		{
			specialchar=specialchar+1;
		}
		else
		{
			if ((tempStr >='A')&&(tempStr <='Z'))
			{
				specialchar=specialchar+1;
			}
			else
			{
				if ((tempStr >= 0)&&(tempStr<=9))
				{
					specialchar=specialchar+1;
				}
				else
				{
					if ((tempStr=='_')||(tempStr=='-')||(tempStr=='.')||(tempStr=='@'))
					{
						specialchar=specialchar+1;
					}
					else
					{
						return false;
					}
				}
			}
		}
	}
	dots = emailval.indexOf('..');
	if (dots != -1)
	{
		return false;
	}
	espace = emailval.indexOf(' ');
	if (espace != -1)
	{
		return false;
	}
	lastoccofperiod = emailval.lastIndexOf('.');
	if (lastoccofperiod <= 0)
	{
		blnperiod = 0;
	}
	if (((icount - lastoccofperiod) > 5)||((icount - lastoccofperiod) < 3))
	{
		maxthree = 0;
	}
	 for(i=0;i<=icount;i++)
	{
		tempStr = emailval.charAt(i)
		if (tempStr=='@')
		ampicount=ampicount + 1;
	}
	amppos = emailval.indexOf('@');
	if (emailval.charAt(amppos+1) == '.')
	servername = 0;
	if(icount - emailval.charAt(amppos)< 5) 
	servername = 0;
	if ((ampicount==1)&&(blnperiod==1)&&(maxthree==1)&&(servername==1))
	{
		blnmail=1;
	}
	else
	{
		 blnmail=0;
	}
	 //return blnmail;
	if (blnmail==0)
	{
		//alert('Please enter a valid email address');
		return false;
	}
	else
	{
		return true;
	}
 }
 
 function funcUpload(){
	objForm = document.Frmlogin;
	window.open("uploadmailattach.php",null,"height=125,width=400,status=yes,toolbar=no,menubar=no,location=no,scrollbars=0");
	}
function funcAddValue(strFileName){
	objElement = document.Frmlogin.optAttachments
	iLength = objElement.length;
	objElement.length = iLength+1;
	objElement.options[iLength].value = strFileName;
	objElement.options[iLength].text = strFileName;
}
function funcDelete(){
	objElement = document.Frmlogin.optAttachments;
	if(objElement.selectedIndex >=0){
	objElement.remove(objElement.selectedIndex);
	}
}
 
</script>
<style type="text/css">
<!--
.style2 {font-size: 12px}
-->
</style>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="70%" >
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
	<table width="80%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Send&nbsp;Mass&nbsp;Mail</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">
  <form action="massmail2fb.php" method="post" onsubmit="return validation()" name="Frmlogin" enctype="multipart/form-data">
	<input type="hidden" name="attachments" value="">
  <table height="100%" width="100%" cellspacing="0" cellpadding="0">
<br>  <tr><td  width="100%" valign="center" align="center">     
      <table width="500" border="0" cellpadding="0"  height="100">
                      <tr>
                        <td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">From 
                          :</font></td>
                        <td align="left" height="30" width="350"><input name="fromaddress" type="text" style="font-family:arial;font-size:10px;width:280px" value="<?=$_SESSION['gw_emails_sales']?>" maxlength="230">
                        </input>
                        </td>
                      </tr>
                      <tr>
                        <td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Subject:</font></td>
                        <td align="left" height="30" width="350"><input name="subject" type="text" style="font-family:arial;font-size:10px;width:280px" value="<?=$_POST['subject']?>" maxlength="230"></input>
                        </td>
                      </tr>
                      <tr>
                        <td align="center" valign="center" height="30" colspan="2"><textarea wrap="virtual" name="txtBody" id="txtBody" cols="60" rows="10"><?= stripslashes( $_POST['txtBody'])?></textarea>
		   
<script type="text/javascript" src="/fckedit/fckeditor.js"></script>
<script type="text/javascript">
<!--
// Automatically calculates the editor base path based on the _samples directory.
// This is usefull only for these samples. A real application should use something like this:
// oFCKeditor.BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
var sBasePath = '/fckedit/';
var oFCKeditor = new FCKeditor( 'txtBody','100%','400' ) ;//( instanceName, width, height, toolbarSet, value )
oFCKeditor.BasePath	= sBasePath ;
oFCKeditor.ReplaceTextarea() ;
//-->
</script>
<br>
<span class="style2">Note: You may seperate emails by any number of commas or space characters.
</span></td>
                      </tr>
					   <tr>
					  	<td align="right"><font face="verdana" size="1">Attachments:</font></td>
					  	<td>
							<select name="optAttachments" size="5" style="width:200"></select>
							<br><br>
							<a href="javascript:funcUpload()"><img SRC="<?=$tmpl_dir?>/images/uploadattachment.jpg" border="0" alt="Upload Attachments"></a>
							<a href="javascript:funcDelete()"><img SRC="<?=$tmpl_dir?>/images/delete.jpg" border="0" alt="Delete"></a>
						</td>
					  </tr>
					   <tr>
                        <td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Upload 
                          address list:</font></td>
                        <td align="left" height="30" width="350"><input type="file" name="fle_address" size="30"></input>
                        </td>
                      </tr>
                      <tr>
                        <td align="center" valign="center" height="30" colspan="2"><input type="image" id="sendmail" SRC="<?=$tmpl_dir?>/images/send.jpg"></input>
                        </td>
                      </tr>
                    </table>
  </td></tr></table>
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
include 'includes/footer.php';
?>