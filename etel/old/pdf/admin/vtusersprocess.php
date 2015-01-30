<?php
include("includes/sessioncheck.php");


$iCompanyId	=	(isset($HTTP_POST_VARS["hdCompanyId"])?quote_smart($HTTP_POST_VARS["hdCompanyId"]):"")	;	
$iUserId	=	(isset($HTTP_POST_VARS["hdId"])?quote_smart($HTTP_POST_VARS["hdId"]):"");	
$sUserName	=	(isset($HTTP_POST_VARS["txtUserName"])?quote_smart($HTTP_POST_VARS["txtUserName"]):"");
$sPassWord	=	(isset($HTTP_POST_VARS["txtPassword"])?quote_smart($HTTP_POST_VARS["txtPassword"]):"");
$sAction	=	(isset($HTTP_GET_VARS["action"])?quote_smart($HTTP_GET_VARS["action"]):"");


if ( $iCompanyId != "" && $iUserId == "" && $sAction == "") {
	$qryInsert = "insert into cs_companyusers (userid,username,password) values ($iCompanyId,'$sUserName','$sPassWord') ";
	if (!(funcIsUserNameExist($cnn_cs,$iUserId,$sUserName)))
	{
		mysql_query($qryInsert,$cnn_cs);
	}

}
if ( $iCompanyId != "" && $iUserId != "" && $sAction == "") {
	$qryInsert = "update cs_companyusers set userid = $iCompanyId,username = '$sUserName',password = '$sPassWord' where id = $iUserId";
	if (!(funcIsUserNameExist($cnn_cs,$iUserId,$sUserName)))
	{
		mysql_query($qryInsert,$cnn_cs);
	}

}
if ( $sAction != "" ) {
	$iCount = $HTTP_POST_VARS["hdCount"];
	for($iLoop = 1;$iLoop<=$iCount;$iLoop++) {
		$iId = (isset($HTTP_POST_VARS["chk$iLoop"])?quote_smart($HTTP_POST_VARS["chk$iLoop"]):"");
		if ($iId != "" ) {
			$qryDelete = "delete from cs_companyusers where id = $iId";
			mysql_query($qryDelete,$cnn_cs);		
		}	
	}
}
header("location:vtusers.php?id=".$iCompanyId);
function funcIsUserNameExist($cnn_connection,$iUserId,$sUserName) {
	$qrySelect = "select * from cs_companyusers where  username ='$sUserName' ";
	if ( $iUserId != "" ) {
		$qrySelect .= " and  id <> $iUserId ";
	}
	$rstSelect = mysql_query($qrySelect,$cnn_connection);
	if(mysql_num_rows($rstSelect)>0) {
		return true;
	}else{
		return false;	
	}
}



?>

