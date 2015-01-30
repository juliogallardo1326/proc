<?php
$pageConfig['Title'] = 'Merchant Contract';
if (isset($_GET['printable'])) $printable_version = true;
require_once("includes/sessioncheck.php");
$headerInclude="startHere";
require_once("includes/header.php");
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
if($curUserInfo['cd_completion']<=2)
{
	include("merchantRequestRates.php");
	exit();
}
if($curUserInfo['cd_completion']<=3)//|| !$curUserInfo['cd_merchant_show_contract'])
{
	include("blank.php");
	exit();
}
if($_POST['submit_x'] || $_POST['submit'])
{
	$cd_contract_ip = getRealIp();
	$cd_contract_date = time();
	$agree_contract = $_POST['agree_contract']==1;
	$curUserInfo['merchant_contract_agree'] = $agree_contract;
	if($agree_contract && $curUserInfo['cd_completion']<=4) $completion = ' cd_completion=6, ';

	$str_qry = "update cs_companydetails set $completion merchant_contract_agree = '$agree_contract',cd_contract_ip='$cd_contract_ip',cd_contract_date='$cd_contract_date' where userId = '".$curUserInfo['userId']."'";
	if($agree_contract==1) 
	{
		sql_query_write($str_qry,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>$str_qry");
		if($completion) en_status_change_notify($curUserInfo['en_ID']);
		
		print "<script>document.location.href='Listdetails.php?type=testMode&msg=Merchant+Contract+Completed+Successfully. Please Print, Sign, and Upload your Merchant Contract.';</script>";
		
		die();
	}
}
$confirm = ($curUserInfo['merchant_contract_agree']=="1"?"checked":"");

$Rates = new rates_fees();
$contract = $Rates->get_Merchant_Contract($curUserInfo['en_ID']);

//if($curUserInfo['cd_custom_contract']) $content = $curUserInfo['cd_custom_contract'];
$content = $contract['et_htmlformat'];
?>
<form name="merchant_contract" method="post" action="">
<table border="0" cellpadding="0" width="100%" cellspacing="0" >
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
<table border="0" cellpadding="0" cellspacing="0" width="85%" class="disbd">

            <tr>
              <td width="100%" valign="top" align="center">
		   
<table  border="0" cellspacing="0" cellpadding="0" width="640" height="61">
	  <tr><td width="2189"><p class="disctxhd"><?php if ($printable_version) { ?><img onClick="window.print();" border src="images/print.gif" width="23" height="22"><?php } ?> &nbsp;Merchant Contract.</p>
	      <?=$content?>
	      </td></tr>
	
            <?php if (!$printable_version) { ?>
		  <tr><td height="31" valign="middle"><span class="intx1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="agree_contract" value="1" <?=$confirm?>>
		  &nbsp;I Agree With The Merchant Contract.</span></td></tr>
			<?php } ?>
	</table>			
	  </td>
		</tr>
 		  
            <?php if (!$printable_version) { ?>
		  <tr><td align="center" valign="middle" height="40" colspan="2"><a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a>&nbsp;&nbsp;<input name="submit" type="image" src='images/submit.jpg' width="49" height="20" border="0">		    &nbsp;<a href="merchantContract.php?printable" target="_blank"><img border="0" src="images/print.jpg" width="49" height="20"></a></td>
		  </tr>
         	<?php }?>

		 </table>
		
<!--	</td>		
			
			
		  </tr>
		  <tr><td><span class="intx1"><input type="checkbox" name="agree_contract" value="Yes" <?=$confirm?>>&nbsp;I Agree With The Merchant Contract.</span></td></tr>
 		  <tr><td align="center" valign="bottom" height="30" colspan="2"><a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a>&nbsp;&nbsp;<input type="image" border="0" src='images/continue.gif'></td></tr>
		  </table> -->
      </td>
    </tr>
  </table>    </td>
     </tr>
</table>
</form>
<?php
	if (!$printable_version) include("includes/footer.php");


?>