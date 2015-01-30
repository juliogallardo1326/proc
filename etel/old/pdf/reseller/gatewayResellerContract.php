<?php
	include("includes/sessioncheck.php");
	$headerInclude="blank";
	include("includes/header.php");
	
$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";
$gatewayid=(isset($HTTP_GET_VARS["gatewayid"])?$HTTP_GET_VARS["gatewayid"]:"");
$qry_select="Select * from cs_gatewaydocument where gateway_id=$gatewayid and contenttype='resellercontract'";
//echo $qry_select;
$str_contract="";
if(!($rst_select=mysql_query($qry_select,$cnn_cs)))
{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
$i_count = mysql_num_rows($rst_select);
if($i_count!=0)
$str_contract = mysql_result($rst_select,0,1);
if($str_contract=="")
{
$str_contract="Contact your administrator for reseller contract details";
}
if($resellerLogin!=""){
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="60%">
    <tr>
   		 <td width="100%" valign="top" align="center">&nbsp;
		 <form name="ResellerContract" action="submitContract.php" method="post">
       <table border="0" cellpadding="0" cellspacing="0" width="85%" class="disbd">
            <tr>
              <td width="100%" valign="top" align="center" bgcolor="#448A99" height="20">
              <img border="0" src="images/spacer.gif" width="1" height="1">
              </td>
            </tr>
            <tr>
              <td width="100%" valign="top" align="center" bgcolor="#2F5F68" height="5"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
            </tr>
            <tr>
              <td width="100%" valign="top" align="center">
            
<table  border="0" cellspacing="0" cellpadding="0" width="795" height="75">
              <tr> 
                <td height="30" width="793" colspan="3" align="center"><span class="disctxhd"><?php echo $str_contract?>
                 </span></td>
              </tr>
			  </table>
			  </td>
			  </tr>
			  <tr><td align="center" valign="middle" width="793" height="40" colspan="3"><a href="javascript:window.history.back();"><img border="0" src="../images/back.jpg"></a>&nbsp;&nbsp;<input type="image" src="../images/continue.gif"></td>
			  </tr>
			  </table>
			  </form>
			  </td>
			  </tr>
			  </table>
			  <?php }?>