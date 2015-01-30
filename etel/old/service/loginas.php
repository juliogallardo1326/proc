<?php
	$rootdir="../";
	$headerInclude = "service";
	include($rootdir."includes/sessioncheckserviceuser.php");
	include($rootdir."includes/dbconnection.php");

				
$redir = $_REQUEST['redir'];
if($_SESSION['cs_found_merchant']){
		$sql = "select * from cs_companydetails where userId = '".intval($_SESSION['cs_found_merchant'])."'";
		$result = mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
		$companyInfo = mysql_fetch_assoc($result);
		if($companyInfo)
		{		
			$etel_debug_mode=0;
			require_once("../includes/dbconnection.php");
		
			$_SESSION["loginredirect"]="None";
			
			$_SESSION["gw_customerservice_info"] = etelEnc($_SESSION["gw_user_username"]."|".$_SESSION["gw_user_password"]."|CustomerService|".$_SESSION['gw_id']."|livetree.php");
		
			general_login($companyInfo['username'],$companyInfo['password'],"merchant",$companyInfo['gateway_id'],false,$redir);
			die();
		}
		
}
dieLog("Invalid Login ~ $sql","Invalid Login");
?>