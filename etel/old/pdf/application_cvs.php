<?php 
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//include 'includes/sessioncheck.php';
include 'includes/sessioncheck.php';
$headerInclude="startHere";
require_once("includes/header.php");
$headerInclude= "blank";	
include 'includes/topheader.php';
function func_company_ext_entry($userid,$mastercurrency,$visacurrency,$cnn_cs){
	$qry_exist="select * from cs_companydetails_ext where userid='$userid'";
	if(!$rst_exist=mysql_query($qry_exist,$cnn_cs))
	{
		echo "Cannot execute Query";
	}
	else{
		$num=mysql_num_rows($rst_exist);
		if($num==0)
		{
			$qry_companyext="insert into cs_companydetails_ext (userId,processingcurrency_master,processingcurrency_visa) values('$userid','$mastercurrency','$visacurrency')";	
		}
		else
		{
			$qry_companyext="update cs_companydetails_ext set processingcurrency_master='$mastercurrency',processingcurrency_visa='$visacurrency' where userid='$userid'";
		}
		if(!$rst_update=mysql_query($qry_companyext,$cnn_cs))
		{
			echo "Cannot execute query";
		}
	}
}
$invalidlogin = "";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

	$username = (isset($HTTP_POST_VARS['username'])?quote_smart($HTTP_POST_VARS['username']):"");
	$volume= (isset($HTTP_POST_VARS['volume'])?quote_smart($HTTP_POST_VARS['volume']):"");
	$avgticket= (isset($HTTP_POST_VARS['avgticket'])?quote_smart($HTTP_POST_VARS['avgticket']):"");
	$chargeper= (isset($HTTP_POST_VARS['chargeper'])?quote_smart($HTTP_POST_VARS['chargeper']):"");
	$rad_order_type= (isset($HTTP_POST_VARS['rad_order_type'])?quote_smart($HTTP_POST_VARS['rad_order_type']):"");
	$prepro= (isset($HTTP_POST_VARS['prepro'])?quote_smart($HTTP_POST_VARS['prepro']):"");
	$rebill= (isset($HTTP_POST_VARS['rebill'])?quote_smart($HTTP_POST_VARS['rebill']):"");
	$currpro= (isset($HTTP_POST_VARS['currpro'])?quote_smart($HTTP_POST_VARS['currpro']):"");
	$billingdesc = (isset($HTTP_POST_VARS['billingdesc'])?quote_smart($HTTP_POST_VARS['billingdesc']):"");
	 $mastercurrency= (isset($HTTP_POST_VARS['currencymaster'])?quote_smart($HTTP_POST_VARS['currencymaster']):"");
	 $visacurrency = (isset($HTTP_POST_VARS['currencyvisa'])?quote_smart($HTTP_POST_VARS['currencyvisa']):"");
	 func_company_ext_entry($sessionlogin,$mastercurrency,$visacurrency,$cnn_cs);


	if($volume=="") 
		$volume=0;
	if($avgticket=="")
		$avgticket=0;
	if($chargeper=="")
		$chargeper=0;
		
		$str_update_query  = "update cs_companydetails set avgticket = '$avgticket', chargebackper = '$chargeper', ";
		$str_update_query .= "transaction_type = '$rad_order_type', preprocess = '$prepro', recurbilling = '$rebill', currprocessing = '$currpro', billingdescriptor='$billingdesc' ";
		$str_update_query .= "where userid=$sessionlogin";

		if (!mysql_query($str_update_query,$cnn_cs)) {
			echo mysql_errno().": ".mysql_error()."<BR>";
			echo "Cannot execute update query.";
			exit();
		}
	
	$qry_select_user = "select *  from cs_companydetails where userid=$sessionlogin";
	
	if(!($show_sql =mysql_query($qry_select_user)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if($showval = mysql_fetch_array($show_sql)){ 
?>
<script language="javascript">
function validation(){
   if(document.Frmcompany.txtPackagename.value==""){
    alert("Please enter the package name.")
    document.Frmcompany.txtPackagename.focus();
	return false;
  }
   if(document.Frmcompany.txtPackageProduct.value==""){
    alert("Please enter the package product service.")
    document.Frmcompany.txtPackageProduct.focus();
	return false;
  }
  if(document.Frmcompany.txtPackagePrice.value==""){
    alert("Please enter the package price.")
    document.Frmcompany.txtPackagePrice.focus();
	return false;
  }
  if(isNaN(document.Frmcompany.txtPackagePrice.value)){
    alert("Please enter a numeric value for package price.")
    document.Frmcompany.txtPackagePrice.focus();
	return false;
  }
  if(document.Frmcompany.txtRefundPolicy.value==""){ 
    alert("Please enter the refund policy.")
    document.Frmcompany.txtRefundPolicy.focus();
	return false;
  }
  return true;
}
function HelpWindow() {
   advtWnd=window.open("aboutscript.htm","Help","'status=1,scrollbars=1,width=500,height=375,left=0,top=0'");
   advtWnd.focus();
}
</script>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
	<table border="0" cellpadding="0" cellspacing="0" width="70%" class="disbd">
            <tr>
              <td width="100%" valign="top" align="center" bgcolor="#999999" height="20">
              <img border="0" src="images/spacer.gif" width="1" height="1">
              </td>
            </tr>
            <tr>
              <td width="100%" valign="top" align="center" bgcolor="#2F5F68" height="5"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
            </tr>
             <tr>
          <td width="100%" valign="middle" align="left" height="24" class="disctxhd">&nbsp; 
            Merchant Application</td>
            </tr>
            <tr>
              <td width="100%" valign="top" align="center">
			<table width="100%"  height="40"  valign="bottom">			
			  <tr>
                <td width="100%" valign="middle" align="left" height="40" bgcolor="#DDDDDD"><img border="0" src="<?=$tmpl_dir?>images/application.gif"><img border="0" src="<?=$tmpl_dir?>images/aboutyou.gif"><img border="0" src="<?=$tmpl_dir?>images/yourcompany.gif"><img border="0" src="<?=$tmpl_dir?>images/yourprocess.gif"><img src="images/yourscript1.gif" border="0"><img border="0" src="<?=$tmpl_dir?>images/yourbank.gif"><img border="0" src="<?=$tmpl_dir?>images/finishingline.gif"></td>
            </tr> 
			</table>           
			<form action="application_bpi.php" method="post" onsubmit="return validation()" name="Frmcompany">
			<input type="hidden" name="username" value="<?=$showval[1]?>"></input>
            <table border="0" cellpadding="0"  height="100" width="100%">
                      <tr>
                        <td align="center" valign="center" height="30" colspan="2" bgcolor="#CCCCCC" class="whitehd">Create Verification Script</td>
                      </tr>
					<tr>
                    <td align="left" valign="middle" height="30" width="175"  bgcolor="#F8FAFC"><font face="verdana" size="1">Package 
                                  Name &nbsp;</font></td>
						<td align="left" height="30" width="225"  bgcolor="#F8FAFC"><input name="txtPackagename" type="text" src='req' style="font-family:arial;font-size:10px;width:240px" value="<?=htmlentities($showval[33])?>"></td>
						</tr>
						
						<tr>
                        <td align="left" valign="middle" height="30" width="175"  bgcolor="#F8FAFC"><font face="verdana" size="1">Package 
                                  Product Service &nbsp;</font></td>
						<td align="left" height="30" width="225"  bgcolor="#F8FAFC"><input name="txtPackageProduct" type="text" src='req' style="font-family:arial;font-size:10px;width:240px" value="<?=htmlentities($showval[34])?>"></td>
						</tr>
						
						<tr>
                        <td align="left" valign="middle" height="30" width="175"  bgcolor="#F8FAFC"><font face="verdana" size="1">Package 
                                  Price &nbsp;</font></td>
						<td align="left" height="30" width="225"  bgcolor="#F8FAFC"><input name="txtPackagePrice" type="text" src='req' style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[35]?>"></td>
						</tr>
						
						<tr>
                         <td align="left" valign="middle" height="30" width="175"  bgcolor="#F8FAFC"><font face="verdana" size="1">Refund 
                                  Policy &nbsp;</font></td>
						<td align="left" height="30" width="225"  bgcolor="#F8FAFC"><input name="txtRefundPolicy" type="text" src='req' style="font-family:arial;font-size:10px;width:240px" value="<?=htmlentities($showval[36])?>"></td>
						</tr>
						
						<tr>
                        <td align="left" valign="middle" height="30" width="175"  bgcolor="#F8FAFC"><font face="verdana" size="1">Description 
                                  &nbsp;</font></td>
						<td align="left" height="30" width="225"  bgcolor="#F8FAFC"><textarea name="txtDescription" type="text" src='req' style="font-family:arial;font-size:10px;width:240px" rows="6"><?=htmlentities($showval[37])?></textarea></td>
						</tr>
						<input type="hidden" name="company" value="company">
                      <tr>
                  <td align="center" valign="center" height="30" colspan="2"><a href="javascript:HelpWindow();"><img border="0" src="images/help_s.gif"></a>&nbsp;&nbsp;<a href="mailto:<?=$_SESSION['gw_emails_sales']?>"><img border="0" src="images/mailme_s.gif"></a>&nbsp;&nbsp;<a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a> 
                    &nbsp;<input name="image" type="image" id="modifycompany" src="images/continue.gif">
<br>
                        </td>
                      </tr>
                    </table>
           		</form>
              </td>
            </tr>
          </table></td>
     </tr>
</table><br>
<?php
}
	include("includes/footer.php");
	


?>