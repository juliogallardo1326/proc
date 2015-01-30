<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// deleteLetterTemplate.php:	This admin page functions for deleting a particular Letter Template. 
include("includes/sessioncheck.php");


$backhref="useraccount.php";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="") {
	$iTemplateId = (isset($HTTP_GET_VARS['templateId'])?quote_smart($HTTP_GET_VARS['templateId']):"");
	$qry = "delete from cs_mailtemplate where template_id = $iTemplateId";
	if(!mysql_query($qry)){
		print("Can not execute query");
		print("<br>");
		print(mysql_error());
		exit();
	}

	header("location:maileditor.php");
}
?>