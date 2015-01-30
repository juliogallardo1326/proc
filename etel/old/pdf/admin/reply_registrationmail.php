<?php
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//
 
include("includes/sessioncheck.php");

$headerInclude = "adminemail";
include("includes/header.php");

//$headerInclude="companies";

include("includes/message.php");

$Transtype = isset($HTTP_POST_VARS['trans_type'])?quote_smart($HTTP_POST_VARS['trans_type']):"";
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"A";
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"A";
$companyname = isset($HTTP_POST_VARS['companyname'])?$HTTP_POST_VARS['companyname']:"";
$qrt_select_companies ="select distinct userId,companyname from cs_companydetails order by companyname";
if ($Transtype == "Submit")  {
	if($companytype =="AC") {
		$qrt_select_subqry = " activeuser=1";
	} else if($companytype =="NC") {
		$qrt_select_subqry = " activeuser=0";	
	} else {
		$qrt_select_subqry = "";	
	}
	if($companytrans_type =="A") {
		$qrt_select_merchant_qry = "";
	} else {
		if($qrt_select_subqry =="") {
			$qrt_select_merchant_qry = " transaction_type='$companytrans_type'";
		} else {
			$qrt_select_merchant_qry = " and transaction_type='$companytrans_type'";
		}
	}

	$str_total_query = "";
	if ($qrt_select_subqry != "" || $qrt_select_merchant_qry != "") {
		$str_total_query = "where $qrt_select_subqry $qrt_select_merchant_qry";
	}
$qrt_select_companies="select distinct userId,companyname from cs_companydetails $str_total_query order by companyname";
//print($qrt_select_companies);
}	
	
if(!($show_select_sql =mysql_query($qrt_select_companies,$cnn_cs)))
{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}

?>
<script language="javascript1.1">
function viewtemplate(type)
{	
	var isValid = false;
	var companyid;
	var obj_element = document.frm_reply.elements[3];
	for (i = 0; i < obj_element.length; i++) {
		if(obj_element[i].selected) {
			isValid = true;
		}
	} 
	if(type =="login") {
		if (isValid) {
		   companyid = document.frm_reply.elements[3].value;
		   advtWnd=window.open("reply_registrationmailview.php?company="+companyid,"advtWndName","'status=1,scrollbars=1,width=800,height=600,left=0,top=0'");
		   advtWnd.focus();
		} else {
		   companyid = "";
		   advtWnd=window.open("reply_registrationmailview.php?company="+companyid,"advtWndName","'status=1,scrollbars=1,width=800,height=600,left=0,top=0'");
		   advtWnd.focus();
		}
	} else {
		if (isValid) {
		   companyid = document.frm_reply.elements[3].value;
		   advtWnd=window.open("reply_registrationmailview.php?type=ecom","advtWndName","'status=1,scrollbars=1,width=800,height=600,left=0,top=0'");
		   advtWnd.focus();
		} else {
		   companyid = "";
		   advtWnd=window.open("reply_registrationmailview.php?type=ecom","advtWndName","'status=1,scrollbars=1,width=800,height=600,left=0,top=0'");
		   advtWnd.focus();
		}
	}


}
function Displaycompanytype() {
	document.frm_reply.trans_type.value="Submit";
	document.frm_reply.action = "reply_registrationmail.php";
	document.frm_reply.submit();
}
function validation() {
	var isValid = false;
	var obj_element = document.frm_reply.elements[3];
	for (i = 0; i < obj_element.length; i++) {
		if(obj_element[i].selected) {
			isValid = true;
		}
	}
	if (isValid) {
		return true;
	} else {
		alert("Please select a company");
		return false;
	}
}

</script>
<?php
$str_checked = "";
$mail_id = 0;
$qry_select = "Select mail_id,mail_sent from cs_registrationmail";
$rst_select = mysql_query($qry_select,$cnn_cs);
if (mysql_num_rows($rst_select)>0)
{
	$mail_id = mysql_result($rst_select,0,0);
	$mail_sent = mysql_result($rst_select,0,1);
	if ($mail_sent == 1)
	{
		$str_checked = "checked";
	}
}

?>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="61%" align="center">
<form name="frm_reply" action="reply_registrationmailfb.php" method="post" onsubmit="return validation();">
<input type="hidden" name="trans_type" value="">
<tr>
   <td width="95%" valign="top" align="center" ><br>
		<table width="50%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		     <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Mail Confirmation</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5">
			<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="10">
			     <tr>
				 	<td height="10"  valign="middle" align="center" width="50%"></td> 
			    </tr>  
			    
			     <tr><td colspan="2" align="center" valign="middle" width="500">
			<table  cellpadding="0" cellspacing="0" width="100%"  align="center">
				<tr>
				 <td height="30" valign="middle" align="right" width="40%"><font face="verdana" size="1">Company Type :</font></td>
				 <td align="left"  width="60%">&nbsp;<select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 150px" onChange="Displaycompanytype();">
				<?php print func_select_mailcompanytype($companytype); ?>
					</select></td>
				</tr>
				 <tr>
				<td height="30" valign="middle" align="right" width="40%"><font face="verdana" size="1">Merchant Type :</font></td>
				<td align="left"  width="60%">&nbsp;<select name="companytrans_type" style="font-family:verdana;font-size:10px;WIDTH: 150px" onChange="Displaycompanytype();">
				<?php print func_select_companytrans_type($companytrans_type); ?>
					</select></td>
				</tr>
				
				<tr><td colspan="2" height="30" align="center">
				<div id="allC" style="display:yes">
				<table width="100%"  cellpadding="0" cellspacing="0"><tr>
				<td valign="middle" align="right" width="40%"><font face="verdana" size="1">Company Name :</font></td>
				 <td align="left" width="60%">&nbsp;<select id="all" name="companyname[]" style="font-family:verdana;font-size:10px;WIDTH: 150px" multiple>
				<?php func_multiselect_transaction($qrt_select_companies);
				?>
				</select>
				</td></tr></table>
				</div>
				
				</td></tr>
			</table>
			</td></tr>  
				<tr>
                  <td height="30"  valign="middle" align="center" colspan="2">
				  <table width="100%">
				  <tr><td height="30"  valign="middle" align="center">
                    <font face="verdana" size="1"><a href="javascript:viewtemplate('login');">View Login Letter</a></font>
				  </td>
				      <!--  <td height="30"  valign="middle" align="left">&nbsp;&nbsp;&nbsp; 
                          <font face="verdana" size="1"><input name="chk_sent" type="checkbox" value="1" <?=$str_checked?>> Send login Letter </font> &nbsp; 
                          			
				  </td>-->
				  </tr>
									  
				</table>	
				  </td>
				</tr>
			  <tr>
		          <td height="30"  valign="middle" align="center">&nbsp;&nbsp;&nbsp;
				  <input type="image" name="Submit" id="Submit" SRC="<?=$tmpl_dir?>/images/send.jpg">
				  </td>
		      </tr>		 		  
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
<input type="hidden" name="hid_id" value="<?=$mail_id?>">
</form>
</table>
<?php 
include("includes/footer.php");
?>
