<?php
	include("includes/sessioncheck.php");
	$headerInclude="blank";
	include("includes/header.php");
	
	$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";
$gatewayid=(isset($HTTP_GET_VARS["gatewayid"])?$HTTP_GET_VARS["gatewayid"]:"");
$qry_select="Select * from cs_gatewaydocument where gateway_id=$gatewayid and contenttype='resellerletter'";
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
$str_contract="Contact your administrator for reseller letter details";
}
if($resellerLogin!=""){
?><br><br>
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="60%">
    <tr>
   		 <td width="100%" valign="top" align="center">
       <table border="0" cellpadding="0" cellspacing="0" width="85%" class="disbd">
            <tr>
              <td width="100%" valign="top" align="center" bgcolor="#448A99" height="20">
              <img border="0" src="../images/spacer.gif" width="1" height="1">
              </td>
            </tr>
            <tr>
              <td width="100%" valign="top" align="center" bgcolor="#2F5F68" height="5"><img border="0" src="../images/spacer.gif" width="1" height="1"></td>
            </tr>
            <tr>
              <td width="100%" valign="top" align="center">
            <table  border="0" cellspacing="0" cellpadding="0" width="795" height="61">
              <tr> 
                <td  class="bentx" align="center"><?php echo $str_contract; ?></td>
				</tr>
				</table>
			</td>
			</tr>
			<tr>
			<td align="center" valign="middle" width="793" height="40"><a href="javascript:window.history.back();"><img border="0" src="../images/back.jpg"></a>&nbsp;&nbsp;<a href="resellerApplication.php"><img border="0" src="../images/continue.gif"></a></td>
			</tr></table>
		</td>
	</tr>
</table>
				
<?php
include("includes/footer.php");

 } ?>