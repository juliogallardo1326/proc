<?php
include("includes/sessioncheck.php");
$headerInclude="startHere";
include("includes/header.php");

$str_ResellerId = isset($HTTP_SESSION_VARS["sessionReseller"])?trim($HTTP_SESSION_VARS["sessionReseller"]):"";
if($resellerInfo['rd_completion']==3) func_update_single_field('cs_resellerdetails','rd_completion',4,NULL,'reseller_id',$resellerInfo['reseller_id'],$cnn_cs);

?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="60%">
  <tr>
    <td width="100%" valign="top" align="center"><br> </td>
  </tr>
</table>
<?php
	include("includes/footer.php");
?>

