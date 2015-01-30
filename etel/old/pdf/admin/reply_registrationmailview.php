<?php 
include("includes/sessioncheck.php");

include("includes/mailbody_replytemplate.php"); 

$companyid = isset($HTTP_GET_VARS['company'])?quote_smart($HTTP_GET_VARS['company']):"";
$type = isset($HTTP_GET_VARS['type'])?quote_smart($HTTP_GET_VARS['type']):"";
if($type=="ecom") {
	//print func_getecommerce_mailbody();
	print str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", nl2br(func_getecommerce_mailbody()));
} else if($type=="reseller") {
	//print func_reseller_loginletter();
	print str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", nl2br(func_reseller_loginletter()));
} else {
	if($companyid == "A") {
		$companyname = "[CompanyName]";
		$username = "[UserName]";
		$password = "[PassWord]";
		//$referenceNumber="[Reference No]";
	} else if($companyid != "") {
		$show_sql =mysql_query("select distinct email,companyname,username,password,ReferenceNumber from cs_companydetails where userid=$companyid",$cnn_cs);
		$to_id=mysql_result($show_sql,0,0);
		$companyname=mysql_result($show_sql,0,1);
		$username =mysql_result($show_sql,0,2);
		$password=mysql_result($show_sql,0,3);
		//$referenceNumber=mysql_result($show_sql,0,4);
	} else {
		$companyname = "[CompanyName]";
		$username = "[UserName]";
		$password = "[PassWord]";
		//$referenceNumber="[Reference No]";
	}
	//print func_getreplymailbody($companyname,$username,$password);
	print str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", nl2br(func_getreplymailbody_htmlformat($companyname,$username,$password,$referenceNumber)));
}
?>