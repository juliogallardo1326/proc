<?php

require_once("includes/sessioncheck.php");
$headerInclude="startHere";
require_once("includes/header.php");
	
$act = (isset($HTTP_POST_VARS["act"])?quote_smart($HTTP_POST_VARS["act"]):"");

$questions_charge = (isset($HTTP_POST_VARS["questions_charge"])?quote_smart($HTTP_POST_VARS["questions_charge"]):"");
$return_val ="";
$msg="";
$message ="";
$username=$contact_name;
$email=$contact_email;
$companyname =$contact_company_name;
$how_about_us="";
$send_ecommercemail=1;
if($act=="mail")
{

	if($curUserInfo['cd_completion']>2)
	{
		echo "
		<script>
			location.href='confirmation.php?msg=Your Rates have already been requested.'
		</script>
		";
		exit();
	}
	if($curUserInfo['cd_completion']!=2)
	{
		echo "
		<script>
			document.location.href='Listdetails.php?msg=Please Complete the Merchant Application before requesting rates.'
		</script>
		";
		exit();
	}

			$data = $curUserInfo;
			$data['email'] = $curUserInfo['contact_email'];
			if(!$data['email']) $data['email'] = $curUserInfo['email'];
			$data['companyname'] = $curUserInfo['companyname'];
			$data['full_name'] = $curUserInfo['companyname'];
			$data['phone'] = $curUserInfo['phonenumber'];
			$data['fax'] = $curUserInfo['fax_number'];
			$data['comments'] = $questions_charge;
			$data['contact_type'] = $curUserInfo['transaction_type'];
			$data['edit_link'] = $_SESSION['gw_domain']."/admin/editCompanyProfile1.php?company_id=".$curUserInfo['userId'];
			$data["gateway_select"] = $curUserInfo['gateway_id'];
			send_email_template('merchant_request_rates',$data);
			mysql_query("update `cs_companydetails` set cd_completion=3 where userId = '".$curUserInfo['userId']."'") or dieLog(mysql_error());
			toLog('requestrates','merchant', '', $curUserInfo['userId']);
			echo "
				<script>
					location.href='Listdetails.php?msg=Your Rates have been requested successfully.'
				</script>
				";
			exit();

}

	
?>
<?php beginTable() ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="1" cellpadding="3">
		<input type="hidden" name="act" value="mail">
		<tr >
		  <td align="left" colspan="2"><strong><font  class="tx2">Use this form to request rates from 
		    <?=$_SESSION['gw_title']?>
	      :&nbsp;</font></strong></td>
		</tr>
		<tr >
		  <td colspan=2><?php if($return_val==1){?>
			<font class="tx2">
			<?=$message;?>
			</font>
			<?php }?>
		  </td>
		</tr>
		<tr >
		  <td><font class="tx2">Please add any additional notes here (Not required):</font></td>
		  <td><textarea name="questions_charge" rows="5" cols="35" value="<?echo $questions_charge;?>"><?echo $questions_charge;?></textarea>
		  </td>
		</tr>
		<tr align="left" valign="top" >
		  <td colspan=2><div align="center">
			  <input type="image" src="<?=$tmpl_dir?>/images/submit.jpg" width="76" height="24">
			</div></td>
		</tr>
		</form>
		
	  </table></td>
  </tr>
</table>
<?php endTable("Request Your ".$_SESSION['gw_title']." Rates Now.","merchantRequestRates.php") ?>
<?php
	include("includes/footer.php");
?>
