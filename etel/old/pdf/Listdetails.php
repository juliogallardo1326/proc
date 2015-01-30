<?php
include("includes/sessioncheck.php");

$headerInclude="startHere";
include("includes/header.php");
$companyid = isset($HTTP_SESSION_VARS["sessionlogin"])?quote_smart($HTTP_SESSION_VARS["sessionlogin"]):"";

if($_GET['goLive'])
{
	$sql = "UPDATE cs_companydetails set `cd_completion` = 9 WHERE `cd_completion` = 7 AND `userId` = '$companyid'";
	toLog('requestlive','merchant', '', $curUserInfo['userId']);
	if($curUserInfo['cd_completion']==7) 
	{
		mysql_query($sql) or dieLog(mysql_error()); 
		print "<script>document.location.href='Listdetails.php?msg=You have successfully requested to go Live!';</script>";
		en_status_change_notify($curUserInfo['en_ID']);
		die();
	}
}

if($str_UserId !="") {
	$gateway_id = func_get_value_of_field($cnn_cs,"cs_companydetails","gateway_id","userid",$str_UserId);
} else {
	$gateway_id =-1;
}
$msg = $_GET['msg'];
if($msg) beginTable();
?>
<div align="center">
<strong><?=$msg?></strong>
</div>
  <?php
if($msg) endTable("Notice");
  
	include("includes/footer.php");
?>
