<?php
	include("../includes/sessioncheckserviceuser.php");
	
	require_once("../includes/function.php");
	$iCount = (isset($HTTP_POST_VARS["hdCount"])?Trim($HTTP_POST_VARS["hdCount"]):"");
	$iTransactionId  = (isset($HTTP_POST_VARS["hdTransactionId"])?Trim($HTTP_POST_VARS["hdTransactionId"]):"");
	$qryUpdate = "UPDATE cs_callnotes set solved =0 where transaction_id = ".$iTransactionId;
	if(!(mysql_query($qryUpdate,$cnn_cs)))
	{
		print("Can not execute update query");
		exit();
	}	
	for($iLoop=0;$iLoop<=$iCount;$iLoop++)
	{
		$iTrans = (isset($HTTP_POST_VARS["chk".$iLoop])?Trim($HTTP_POST_VARS["chk".$iLoop]):"");
		if($iTrans != "")
		{
			$qryUpdate = "update cs_callnotes set solved = 1 where note_id = ".$iTrans;
			if(!mysql_query($qryUpdate,$cnn_cs))
			{
				print("Can not execute query solved");
				print("<br>");
				print($qryUpdate);
				exit();
			}	
		}	
	}
	header("location:previouscalls.php?id=".$iTransactionId);?>