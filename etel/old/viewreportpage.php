<?php
require_once('viewTransaction.php');
die();
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

include 'includes/function2.php';
require_once( 'includes/function.php');
$headerInclude="reports";
include("includes/topheader.php");
$sessionlogin =$companyInfo['userId'];
$transactionId=intval($_REQUEST['id']);
$ref=quote_smart($_REQUEST['ref']);

$id = $transactionId;
$field = 'transactionId';
if($ref)
{
	$id = $ref;
	$field = 'reference_number';
}
$transactionInfo=getTransactionInfo($id,quote_smart(intval($_GET['test'])),$field," and t.userId = '$sessionlogin'");
if($transactionInfo == -1) dieLog("Invalid Transaction Found! ID=$id FIELD=$field userId=$sessionlogin Testmode=".$_GET['test']." _SERVER=".serialize($_SERVER),"Transaction Not Found!");

//Update Shipping
if($_POST['Update'])
{
	$td_tracking_id = quote_smart($_POST['td_tracking_id']);
	$td_tracking_link = quote_smart($_POST['td_tracking_link']);
	$td_tracking_company = quote_smart($_POST['td_tracking_company']);
	$td_tracking_info = quote_smart($_POST['td_tracking_info']);
	$td_tracking_order_id = quote_smart($_POST['td_tracking_order_id']);
	$td_tracking_id_disable = quote_smart($_POST['td_tracking_id_disable']);
	
	
	if($td_tracking_id_disable) $td_tracking_id = "No Tracking Number Available";
	$tracking_ship_date_timestamp = strtotime(quote_smart($_POST['td_tracking_ship_date']));
	$tracking_ship_est_timestamp = strtotime(quote_smart($_POST['td_tracking_ship_est']));
	$td_tracking_ship_date = date("Y-m-d g:i:s",$tracking_ship_date_timestamp);
	if($_POST['td_tracking_ship_est']) $td_tracking_ship_est = date("Y-m-d g:i:s",$tracking_ship_est_timestamp);
	
	$sql = "update `cs_transactiondetails` set `td_tracking_order_id` = '$td_tracking_order_id', `td_tracking_ship_est` = '$td_tracking_ship_est', `td_tracking_ship_date` = '$td_tracking_ship_date', `td_tracking_id` = '$td_tracking_id', `td_tracking_link` = '$td_tracking_link', `td_tracking_company` = '$td_tracking_company', `td_tracking_info` = '$td_tracking_info' where transactionId = '$transactionId'";
	if(!$transactionInfo['td_tracking_id'] && $tracking_ship_date_timestamp>0 && $td_tracking_company) 
	{
		$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot update transaction.");
	
		$transactionInfo['td_tracking_id'] = $td_tracking_id;
		$transactionInfo['td_tracking_link'] = $td_tracking_link;
		$transactionInfo['td_tracking_order_id'] = $td_tracking_order_id;
		$transactionInfo['td_tracking_company'] = $td_tracking_company;
		$transactionInfo['td_tracking_ship_date'] = $td_tracking_ship_date;
		$transactionInfo['td_tracking_info'] = $td_tracking_info;
		$transactionInfo['td_tracking_ship_est'] = $td_tracking_ship_est;

		// Email
		if($td_tracking_id)
		{
			$useEmailTemplate = "customer_tracking_confirmation";
			
			$data['site_URL'] = $transactionInfo['cs_URL'];
			$data['reference_number'] = $transactionInfo['reference_number'];
			$data['full_name'] = $transactionInfo['surname'].", ".$transactionInfo['name'];
			$data['email'] = $transactionInfo['email'];
			$data['tracking_ID'] = $transactionInfo['td_tracking_id'];
			$data['tracking_link'] = $transactionInfo['td_tracking_link'];
			$data['tracking_order_id'] = $transactionInfo['td_tracking_order_id'];
			$data['tracking_info'] = ($transactionInfo['td_tracking_info']?$transactionInfo['td_tracking_info']:"None");
			$data['tracking_company'] = $transactionInfo['td_tracking_company'];
			$data['tracking_ship_date'] = ($transactionInfo['td_tracking_ship_date']? date("F j, Y, g:i a",strtotime($transactionInfo['td_tracking_ship_date'])):"No Date Available");
			$data['tracking_ship_est'] = ($transactionInfo['td_tracking_ship_est']? date("F j, Y, g:i a",strtotime($transactionInfo['td_tracking_ship_est'])):"No Estimate Available");
			$data["gateway_select"] = $companyInfo['gateway_id'];
			send_email_template($useEmailTemplate,$data,""); // Send Customer Email.
			if($transactionInfo['cd_recieve_order_confirmations'])
			{	
				$data['email'] = $transactionInfo['cd_recieve_order_confirmations'];
				send_email_template($useEmailTemplate,$data,"( Merchant Copy) ");
			}
		}
	}

}
if(!$transactionInfo['td_tracking_link']) $transactionInfo['td_tracking_link'] = "http://";

?>
<style>
.tdbdr{border-bottom:1px solid black;}
.tdbdr1{border-bottom:1px solid black;border-right:1px solid black;}
.tdbdr2{border-right:1px solid black;}
.style1 {font-size: 10px}
</style>
<table border="0" cellpadding="0" width="800" cellspacing="0" align="center"> 
  <tr> 
    <td width="100%" valign="top" align="center"> <form name="frmTransaction" id="frmTransaction" action="viewreportpage.php" method="post" onSubmit="return validateForm(this);"> 
        <br> 
        <table border="0" cellspacing="0" cellpadding="0"> 
          <tr> 
            <td  align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" ></td> 
            <td  align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Credit&nbsp; Card&nbsp;Transaction</span></td> 
            <td  align="left" valign="top" width="49" nowrap><img border="0" src="images/menutopcurve.gif" width="49" ></td> 
            <td  align="left" valign="top" width="55%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td> 
            <td  align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" ></td> 
          </tr> 
          <tr align="center"> 
            <td width="987" colspan="5" class="lgnbd"> <table width="100%" cellpadding="0" cellspacing="0"> 
                <tr> 
                  <td width="50%" valign="top"><p><strong> Client Information:</strong><br> 
                      Reference ID:
                      <?=$transactionInfo['reference_number']?> 
                      <br> 
                      URL: <a href="<?=$transactionInfo['cs_URL']?>" > 
                      <?=$transactionInfo['cs_URL']?> 
                      </a><br> 
                    </p></td> 
                  <td width="50%" valign="top"><p><strong> Signup Information:</strong><br> 
                      Time:
                      <?=date("F j, Y, g:i a",strtotime($transactionInfo['transactionDate']))?> 
                      <br> 
                      Email: <a href="<?=$transactionInfo['email']?>"> 
                      <?=$transactionInfo['email']?> 
                      </a><br> 
                      IP Address:
                      <?=$transactionInfo['ipaddress']?> 
                    </p></td> 
                </tr> 
                <tr> 
                  <td valign="top"><p><strong> Purchase Information:</strong><br> 
                      <strong> 
                      <?=$transactionInfo['userActiveMsg?']?"User Activity: ".$transactionInfo['userActiveMsg?']."<BR>":""?> 
                      </strong> 
                      <?=$transactionInfo['charge_type_info']?"Charge Type: ".$transactionInfo['charge_type_info']."<BR>":""?> 
                      <?=$transactionInfo['subAccountName']?"SubAccount: ".$transactionInfo['subAccountName']."<br>":""?> 
                      <?=$transactionInfo['schedule']?"Schedule: ".$transactionInfo['schedule']."<br>":""?> 
                    <table width="400" cellpadding="0" cellspacing="0"> 
                      <tr> 
                        <td ><p> Amount Charged: </p></td> 
                        <td ><p> &nbsp;$ 
                            <?=formatMoney($transactionInfo['amount'])?> 
                          </p></td> 
                      </tr> 
                      <tr> 
                        <td ><p> Next Payment Date: </p></td> 
                        <td ><p> &nbsp; 
                            <?=$transactionInfo['nextDateInfo']?> 
                          </p></td> 
                      </tr> 
                      <tr> 
                        <td ><p> Member Since: </p></td> 
                        <td ><p> &nbsp; 
                            <?=date("F j, Y, g:i a",strtotime($transactionInfo['transactionDate']))?> 
                          </p></td> 
                      </tr> 
                      <tr> 
                        <td><p> Expires: </p></td> 
                        <td><p> &nbsp; 
                            <?=$transactionInfo['expires']?> 
                          </p></td> 
                      </tr> 
                      <?php if ($expired) { ?> 
                      <tr> 
                        <td><p> Expired: </p></td> 
                        <td><p> &nbsp; 
                            <?=$transactionInfo['expired']?> 
                          </p></td> 
                      </tr> 
                      <?php } ?> 
                      <?php if($transactionInfo['cancel_refer_num']){ ?> 
                      <tr> 
                        <td><p> <?=($transactionInfo['cancelstatus']=='Y'?'Refund':'Cancel')?> Date: </p></td> 
                        <td><p> &nbsp; 
                            <?=date("F j, Y, g:i a",strtotime($transactionInfo['cancellationDate']))?> 
                          </p></td> 
                      </tr> 
                      <tr> 
                        <td><p> <?=($transactionInfo['cancelstatus']=='Y'?'Refund':'Cancel')?> Reason: </p></td> 
                        <td><p> &nbsp; 
                            <?=$transactionInfo['reason']?> 
                          </p></td> 
                      </tr> 
                      <tr> 
                        <td><p> <?=($transactionInfo['cancelstatus']=='Y'?'Refund':'Cancel')?> Reference Number: </p></td> 
                        <td><p> &nbsp; 
                            <?=$transactionInfo['cancel_refer_num']?> 
                          </p></td> 
                      </tr> 
                      <?php } ?> 
                      <tr> 
                        <td><p> Affiliate: </p></td> 
                        <td><p>&nbsp; 
                            <?=($transactionInfo['td_is_affiliate']==1?"Yes":"No")?> 
                          </p></td> 
                      </tr> 
                      <tr> 
                        <td><p> Status: </p></td> 
                        <td><p>&nbsp; 
                            <?=(!$transactionInfo['userActiveMsg']?"No Status":$transactionInfo['userActiveMsg'])?> 
                          </p></td> 
                      </tr> 
                      <tr> 
                        <td><p> Username: </p></td> 
                        <td><p>&nbsp; 
                            <?=(!$transactionInfo['td_username']?"N/A":$transactionInfo['td_username'])?> 
                          </p></td> 
                      </tr> 
                      <tr> 
                        <td><p> Password: </p></td> 
                        <td><p>&nbsp; 
                            <?=(!$transactionInfo['td_password']?"N/A":$transactionInfo['td_password'])?> 
                          </p></td> 
                      </tr> 
                    </table></td> 
                  <td valign="top"><p><strong> Customer Information:</strong><br> 
                      <?=$transactionInfo['surname']?> 
                      ,
                      <?=$transactionInfo['name']?> 
                      <br> 
                      <?=$transactionInfo['address']?> 
                      <br> 
                      <?=$transactionInfo['city']?> 
                      ,
                      <?=$transactionInfo['state']?> 
                      <?=$transactionInfo['zipcode']?> 
                      <br> 
                      <?=$transactionInfo['country']?> 
                    </p>
                    <?php 
						  if($companyInfo['cd_enable_tracking']=='on' && $transactionInfo['td_enable_tracking']=='on') { 
						  $editable = ($transactionInfo['td_tracking_id']?"disabled":"");
						  $track_status = "<b>Tracking Number Deadline: ".date("F j, Y",$transactionInfo['Tracking_Deadline'])."</b>";
						  if(!$editable)
						  {
							  if($transactionInfo['Tracking_Days_Left']<=0)
								$track_status .= "<BR><font color='#FF0000'>This order has not recieved a tracking number and is past due.</font><BR>This order may soon be refunded if we do not recieve a tracking number as soon as possible.";
							  else
								$track_status .= "<BR>This order must recieve a shipping tracking ID within ".$companyInfo['cd_tracking_init_response']." days of purchase.<BR>You have ".$transactionInfo['Tracking_Days_Left']." days left before this transaction is refunded.";
						  }
						  else
						  {
						  	$track_status .= "<BR>The tracking id has been recieved for this order.";
						  }
					 ?>
                    <p><strong>Shipping Information:</strong></p> 
                    <table cellpadding="0" cellspacing="0"> 
                      <tr> 
                        <td colspan="2"><?=$track_status?>
                        <br></td> 
                      </tr> 
                      <tr> 
                        <td ><p> No tracking Number (ex. USPS): </p></td> 
                        <td ><p> &nbsp; 
                            <input name="td_tracking_id_disable" type="checkbox" id="td_tracking_id_disable" value="1" onChange="document.getElementById('td_tracking_id').disabled = this.checked;document.getElementById('td_tracking_link').disabled = this.checked;" <?=$editable?> src='req'> 
                          </p></td> 
                      </tr> 
                      <tr> 
                        <td ><p> Tracking Number: </p></td> 
                        <td ><p> &nbsp; 
                            <input name="td_tracking_id" type="text" id="td_tracking_id" value="<?=$transactionInfo['td_tracking_id']?>" size="35" <?=$editable?> src="req"> 
                          </p></td> 
                      </tr> 
                      <tr> 
                        <td ><p> Tracking Link: </p></td> 
                        <td ><p>&nbsp; 
                            <input name="td_tracking_link" type="text" id="td_tracking_link" value="<?=$transactionInfo['td_tracking_link']?>" size="35" <?=$editable?> src="req">
</p></td> 
                      </tr> 
                      <tr> 
                        <td ><p> Order Number: </p></td> 
                        <td ><p> &nbsp; 
                            <input name="td_tracking_order_id" type="text" id="td_tracking_order_id" value="<?=$transactionInfo['td_tracking_order_id']?>" size="35" <?=$editable?>> 
                          </p></td> 
                      </tr> 
                      <tr> 
                        <td ><p> Shipping Company: </p></td> 
                        <td ><p> &nbsp; 
                            <input name="td_tracking_company" type="text" id="td_tracking_company" value="<?=$transactionInfo['td_tracking_company']?>" size="35" <?=$editable?> src='req'><br>
                            <span class="style1"><br> 
                            To add a New Shipping Company, use the top field. </span></p></td> 
                      </tr> 
                      <tr> 
                        <td ><p> Date Shipped:</p></td> 
                        <td ><p> &nbsp; 
                            <input name="td_tracking_ship_date" type="text" id="td_tracking_ship_date" value="<?=$transactionInfo['td_tracking_ship_date']?>" size="25" <?=$editable?> src="req"> <span class="style1"><br>
                        ('YYYY-MM-DD HH:MM:SS') 
                        in GMT-07:00</span>
                          </p></td> 
                      </tr> 
                      <tr> 
                        <td ><p> Estimated Arrival Time:</p></td> 
                        <td ><p> &nbsp; 
                            <input name="td_tracking_ship_est" type="text" id="td_tracking_ship_est" value="<?=$transactionInfo['td_tracking_ship_est']?>" size="25" <?=$editable?> > <span class="style1"><br>
                        ('YYYY-MM-DD HH:MM:SS')
                        in GMT-07:00</span>
                          </p></td> 
                      </tr> 
                      <tr> 
                        <td ><p> Additional Information:</p></td> 
                        <td ><p> &nbsp; 
                            <textarea name="td_tracking_info" cols="25" id="td_tracking_info" <?=$editable?>><?=$transactionInfo['td_tracking_info']?></textarea> 
                          </p></td> 
                      </tr> 
                      <tr> 
                        <td colspan="2" ><p> &nbsp; 
                            <?php if(!$editable) { ?> 
                            <input name="Update" type="submit" id="Update" value="Update"> 
                            <? } ?> 
                          </p></td> 
                      </tr> 
                    </table> 
                    <p>&nbsp;</p> 
                    <p><br> 
                      <br> 
                      <br> 
                      <br> 
                      <br> 
                    </p> 
                    <?php } ?> 
                    <p>&nbsp;</p></td> 
                </tr> 
              </table> 
              <table align="center" height="50" > 
                <tr> 
                  <td><a href="#" onclick="window.history.back()"><img   src="images/back.jpg" border="0"></a>&nbsp;</td> 
                </tr> 
              </table> 
              <input name="id" type="hidden" id="id" value="<?=$transactionId?>"></td> 
          </tr> 
          <tr> 
            <td width="1%"><img src="images/menubtmleft.gif"></td> 
            <td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td> 
            <td width="1%" ><img src="images/menubtmright.gif"></td> 
          </tr> 
        </table> 
        <table border="0" cellpadding="0" width="100%" cellspacing="0"  align="center"> 
          <tr> 
            <td width="90%" valign="top" align="center" >&nbsp; </td> 
          </tr> 
        </table> 
      </form></td> 
  </tr> 
</table> 
<script language="javascript">
setupForm(document.getElementById('frmTransaction'));
</script>
<?php
include 'includes/footer.php';

?> 
