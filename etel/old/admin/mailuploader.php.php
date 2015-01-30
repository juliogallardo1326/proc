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
// MailUploader.php:	The admin page functions for adding the New company. 
include("includes/sessioncheck.php");

$headerInclude="administration";	
include("includes/header.php");

include("includes/message.php");

$str_error = "";

	if(isset($_FILES['fileupload'])) {
		extract($_FILES['fileupload'], EXTR_PREFIX_ALL, 'uf');
				
		if(trim($uf_size) == 0) {
			$str_error .= "<li>Uploaded HTM file is not a valid one</li>";
		} else {
			if($str_error == "") {
				$str_file_name = $uf_name;
				$i_len = strlen($str_file_name);
				$i_pos_dot = strpos($str_file_name,"."); 
				$str_type = substr($str_file_name,$i_pos_dot+1,3);
				if(strtoupper($str_type)!="HTM") {
					$str_error .= "<li>Please upload the correct HTM  file</li>";
					$msgtodisplay="Please upload the correct HTM  file. ";
					$outhtml="y";				
					message($msgtodisplay,$outhtml,$headerInclude);									
					exit();
				} elseif($uf_name != "mailtemplate.htm") {
						$msgtodisplay="The file name should be as 'mailtemplate.htm'. ";
						$outhtml="y";				
						message($msgtodisplay,$outhtml,$headerInclude);									
						exit();	 
				} else {	
					$svr = $_SERVER["PATH_TRANSLATED"];
					$path_parts = pathinfo($svr); 
					$str_current_path = $path_parts["dirname"];
					
					$str_file_name = $uf_name;
					$str_current_path = $str_current_path."\\".$str_file_name;
					if(copy($uf_tmp_name,$str_current_path)) {
						$msgtodisplay="File successfully uploaded. ";
						$outhtml="y";				
						message($msgtodisplay,$outhtml,$headerInclude);									
						exit();	 
					} else {
						$msgtodisplay="File exceed the limited size!";
						$outhtml="y";				
						message($msgtodisplay,$outhtml,$headerInclude);									
						exit();	   
			   		}
				}
			}
		}
	}
		
?>
<script language="JavaScript">
function validation() {
trimSpace(document.forms[0].fileupload);
	if(document.forms[0].fileupload.value == "" ) {
		alert("Please enter the valid file name");
		document.forms[0].fileupload.focus();
		return false;
	} else {
		document.forms[0].submit();
		return true;
	}
}

function trimSpace(frmElement)
{
     var stringToTrim = eval(frmElement).value;
     var len = stringToTrim.length;
     var front;
     var back;
     for(front = 0; front < len && (stringToTrim.charAt(front) == ' ' || stringToTrim.charAt(front) == '\n' || stringToTrim.charAt(front) == '\r' || stringToTrim.charAt(front) == '\t'); front++);
     for(back = len; back > 0 && back > front && (stringToTrim.charAt(back - 1) == ' ' || stringToTrim.charAt(back - 1) == '\n' || stringToTrim.charAt(back - 1) == '\r' || stringToTrim.charAt(back - 1) == '\t'); back--);
     frmElement.value = stringToTrim.substring(front, back);
}

function downloadfile() {
	document.mailupload.method="post";
	document.mailupload.action="download.php";
	document.mailupload.submit();
}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
		<table border="0" cellpadding="0" cellspacing="0" width="50%">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Letter&nbsp;Template</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5">

		<form name="mailupload" action="MailUploader.php" method="POST" enctype="multipart/form-data" >
	  <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">  
		<tr><td valign="middle" align="right" width="40%"><font face="verdana" size="1">Upload File : </font>&nbsp;</td><td  valign="middle" align="left"><input type="file" name="fileupload" style="font-family:arial;font-size:10px;width:200px" value=""></td></tr>	
	  <tr><td  valign="middle" align="center" colspan="2"><input type="image" name="Download" SRC="<?=$tmpl_dir?>/images/download.jpg" onclick="javascript: return downloadfile();">&nbsp;&nbsp;<input type="image" name="Submit" SRC="<?=$tmpl_dir?>/images/upload.jpg" onClick="javascript: return validation();"></td></tr>
		</table>
	</form></td>
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
<?php
	include("includes/footer.php");
?>	

