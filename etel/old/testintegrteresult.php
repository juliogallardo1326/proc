<?php
$message = (isset($HTTP_POST_VARS['mt_transaction_result'])?Trim($HTTP_POST_VARS['mt_transaction_result']):"");
$transid= (isset($HTTP_POST_VARS['mt_transaction_id'])?Trim($HTTP_POST_VARS['mt_transaction_id']):"");
$voiceid= (isset($HTTP_POST_VARS['mt_voiceauth_id'])?Trim($HTTP_POST_VARS['mt_voiceauth_id']):"");
if($message=="UIN")
{
$msgdisplay="<font face='verdana' size='2' color='black'>You are not a valid user.</font>";			
	$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr></table>";
		print $msgtodisplay;
	exit();
	}
	elseif($message=="INT")
	{
	$msgdisplay="<font face='verdana' size='2' color='black'>Error in executing Query.</font>";			
	$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr></table>";
		print $msgtodisplay;
	exit();
	}
	else
	{
	$msgdisplay="<font face='verdana' size='2' color='black'>Successfully completed the transaction .Your transaction id is $transid .</font>";			
	$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr></table>";
		print $msgtodisplay;
	exit();
	}	
?>
