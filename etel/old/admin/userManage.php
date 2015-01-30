<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// viewcompany.php:	The admin page functions for viewing the company.
$allowBank=true;
include("includes/sessioncheck.php");
include("../includes/companySubView.php");

require_once("../includes/function2.php");
$headerInclude = "companies";
include("includes/header.php");

$userid = isset($_REQUEST['userid'])?quote_smart($_REQUEST['userid']):"";
$li_user_view = isset($_REQUEST['li_user_view'])?quote_smart($_REQUEST['li_user_view']):"";
$level = 'full';
if($adminInfo['li_level']!='full') dieLog("No Access");


beginTable();
?>

<select name="userid" size="5" id="userid">
  <option value="">Select a User</option>
  <?php func_fill_combo_conditionally("select userid,username from `cs_login` ORDER BY `username` DESC ",$userid,$cnn_cs); ?>
</select>
<BR>
<input name="etel_disable_cache" value="1" type="hidden">
<input name="ViewUser" type="submit" value="Edit Admin User">
<?php 
$userSelect= ob_get_contents();
ob_end_clean();
doTable($userSelect,"Available Admin Users","userManage.php");

if($userid) {

	
	$sql = "select * from cs_login where userid='$userid'";
	$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query $sql");
	$adminInfo = mysql_fetch_assoc($result);
	$adminConfig = @unserialize( gzuncompress($adminInfo['li_config']));
	if($adminInfo)
	{
		if($_POST['updateUser']) 
		{
			$adminInfo['li_user_view']=$li_user_view;
			$adminConfig['vList']=explode("|",$_POST['userIdList']);
			$sql = "update  `cs_login` set `li_config` = '".addslashes(gzcompress(serialize($adminConfig)))."', li_user_view='".$adminInfo['li_user_view']."' WHERE `userid` = ".$adminInfo['userid'];
			mysql_query($sql) or dieLog(mysql_error());
		}
		$_REQUEST['tobatchfield'] = implode(", ",$adminConfig['vList']);
	
		beginTable();
		?>
		<input type="hidden" name="userid" id="userid" value="<?=$userid?>">
		<input type="hidden" name="updateUser" id="updateUser" value="1">
		
		
		<?php
		echo func_get_enum_radio('cs_login','li_user_view','li_user_view',$adminInfo['li_user_view']);
beginTable();
echo genCompanyViewTable('userManage.php','userManage.php',$level);
endTable("Company Payment");
		?>
		
		<?php
		$userSelect= ob_get_contents();
		ob_end_clean();
		doTable($userSelect,"User May View","userManage.php",false,false,false,'frmSelComp');
	}
	$adminConfig = NULL;
}
include("includes/footer.php");

?>