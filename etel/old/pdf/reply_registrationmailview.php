<?php 

include 'includes/sessioncheckuser.php';
include("admin/includes/mailbody_replytemplate.php"); 

$companyid = isset($HTTP_GET_VARS['company'])?Trim($HTTP_GET_VARS['company']):"";
$type = isset($HTTP_GET_VARS['type'])?Trim($HTTP_GET_VARS['type']):"";
if($type=="ecom") {
	print func_getecommerce_mailbody();
}else {
	if($companyid == "A") {
		$companyname = "[CompanyName]";
		$username = "[UserName]";
		$password = "[PassWord]";
	} else if($companyid != "") {
		$show_sql =mysql_query("select distinct email,companyname,username,password from cs_companydetails where userid=$companyid",$cnn_cs);
		$to_id=mysql_result($show_sql,0,0);
		$companyname=mysql_result($show_sql,0,1);
		$username =mysql_result($show_sql,0,2);
		$password=mysql_result($show_sql,0,3);
	} else {
		$companyname = "[CompanyName]";
		$username = "[UserName]";
		$password = "[PassWord]";
	}
	print func_getreplymailbody($companyname,$username,$password);
}
?>