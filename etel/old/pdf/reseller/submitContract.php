<?php 
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//include ("includes/sessioncheck.php");

$headerInclude="startHere";
include("includes/header.php");
include("includes/message.php"); 
require_once("../includes/updateAccess.php");

	$schedule_participant_sign = (isset($HTTP_POST_VARS['schedule_participant_sign'])?Trim($HTTP_POST_VARS['schedule_participant_sign']):"");

	if($schedule_participant_sign)
	{
		$update = array();
		$update['Reseller']['Signed_Contract'] = 1;
		if($companyInfo['en_info']['Reseller']['Completion']<4)
			$update['Reseller']['Completion'] = 4;
		
		etel_update_serialized_field('cs_entities','en_info'," en_ID = '".$companyInfo['en_ID']."'",$update);
			
		$msgtodisplay="Congratulations. You are now ready to begin promoting ".$_SESSION['gw_title']." and making money!";
		$link = 'MerchantUrl.php';
	}
	else
	{
		$msgtodisplay="Resellers must sign the reseller agreement before they can begin reselling and managing merchants.";
		$link = 'resellerContract.php';
	}
	
?>

      <?php beginTable() ?>
		  <table width="600" border="0" cellpadding="0"  >
			<tr>
			<td align="center" valign="center" height="30" width="60%"  bgcolor="#F8FAFC"><font face="verdana" size="1">
			<?= $msgtodisplay ?>
			</td>
			</tr>
		  <tr>
	  	<td align="center" valign="center" height="30" colspan="2"><a href="<?=$link?>"><img border="0" src="../images/continue.gif"></a> 
		&nbsp;&nbsp;
		<br>
		</td>
	  	</tr>
		</table>
	<?php endTable("Merchant Contract Complete","startHere.php") ?>

<?
include '../includes/footer.php';
?>