<?php
		$rootdir="../";
		$headerInclude = "service";
		include($rootdir."includes/sessioncheckserviceuser.php");
		include($rootdir."includes/dbconnection.php");
		require_once($rootdir."includes/function.php");
		include($rootdir."includes/header.php");
$str_error_message = "";
$str_success_message = "";
if(isset($HTTP_POST_VARS["txt_current"]))
{
	$str_current_password = (isset($HTTP_POST_VARS["txt_current"])?Trim($HTTP_POST_VARS["txt_current"]):"");
	$str_new_password = (isset($HTTP_POST_VARS["txt_new"])?Trim($HTTP_POST_VARS["txt_new"]):"");
	if(isset($_SESSION["sessionServiceUser"])){
		$str_username = $HTTP_SESSION_VARS["sessionServiceUser"];
		$qry_password = "select password from cs_customerserviceusers where username='$str_username'";
	}else{
		$qry_password = "select password from cs_login where username='service'";
	}
	
	if(!($rst_password = mysql_query($qry_password,$cnn_cs))) {
		print(mysql_errno().": ".mysql_error()."<BR>");
		print("Cannot execute query");
		exit();
	}
	
	$str_database_password = "";
	if(mysql_num_rows($rst_password)>0)
	{
		$str_database_password = mysql_result($rst_password,0,0);
	}
	if($str_database_password == $str_current_password)
	{
		if(isset($_SESSION["sessionServiceUser"])){
			$qry_update = "update cs_customerserviceusers set password = '$str_new_password' where username='$str_username'";
		}else{
			$qry_update = "update cs_login set password = '$str_new_password' where username='service'";
		}
		if(!mysql_query($qry_update))
		{
			print(mysql_errno().": ".mysql_error()."<BR>");
			print("Cannot execute query");
			exit();
		}
			$msgtodisplay="Password changed successfully";
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();
	}
	else
	{
		$msgtodisplay="Current password does not match";
		$outhtml="y";				
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();
	}	
}
else{
	$msgtodisplay="Please enter current password";
	$outhtml="y";				
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();
}

?>
<?php
	include("../admin/includes/footer.php");
?>	