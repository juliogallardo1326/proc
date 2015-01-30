<?php
include("includes/sessioncheck.php");

$strTemplateName = (isset($HTTP_POST_VARS["txtTemplate"])?quote_smart($HTTP_POST_VARS["txtTemplate"]):"");
$qryInsert = "insert into cs_mailtemplate (template_name) values ('".$strTemplateName."')";
if(!mysql_query($qryInsert)){
	print("Can not execute query");
	print("<br>");
	print($qryInsert);
	exit();
}

header("location:maileditor.php");?>