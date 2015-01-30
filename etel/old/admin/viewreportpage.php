<?php
include('viewTransaction.php');
die();
$allowBank=true;
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
$headerInclude="transactions";
include 'includes/header.php';
$display_test_transactions =$_GET['test'];

include '../includes/function2.php';
require_once( '../includes/function.php');

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$act = (isset($HTTP_GET_VARS['act'])?quote_smart($HTTP_GET_VARS['act']):"");

$cancel = (isset($HTTP_POST_VARS['cancel'])?quote_smart($HTTP_POST_VARS['cancel']):"");
$cancelreason = (isset($HTTP_POST_VARS['cancelreason'])?quote_smart($HTTP_POST_VARS['cancelreason']):"");
$id = (isset($_REQUEST['id'])?quote_smart($_REQUEST['id']):"");
$reference_number = (isset($_REQUEST['ref'])?quote_smart($_REQUEST['ref']):"");
?>
<style>
.tdbdr{border-bottom:1px solid black;}
.tdbdr1{border-bottom:1px solid black;border-right:1px solid black;}
.tdbdr2{border-right:1px solid black;}
</style>
<table border="0" cellpadding="0" width="800" cellspacing="0" align="center">
  <tr>
    <td width="100%" valign="top" align="center"><form name="view" action="updatereportpage.php" method="post" onsubmit="return cancelvalidation()">
        <input type="hidden" name="id" value="<?=$id?>">
        </input>
        <?
$by = 'transactionId';

if(!$id && !$reference_number) {doTable("Transaction Invalid","Error",NULL,true,true,true);die();}

if(!$id && $reference_number)
	$transactionInfo = getTransactionInfo($reference_number,$display_test_transactions,'reference_number');
else
	$transactionInfo = getTransactionInfo($id,$display_test_transactions);

if(!is_array($transactionInfo)) {doTable("Transaction Not Found","Error",NULL,true,true,true);die();}

$activity = UserActivity($transactionInfo);

if($activity == "ACT") $act = "User Account is Active (Active).";
else if($activity == "UNF") $act = "User Account Not Found (Inactive).";
else if($activity == "PNF") $act = "Password Incorrect (Inactive).";
else if($activity == "SNF") $act = "Site Not Found (Inactive).";
else if($activity == "INA") $act = "User Account is inactive (Inactive).";
else if($activity == "CAN") $act = "User Account is Cancelled (Inactive)";
else if($activity == "CHB") $act = "User Account has been Charged Back (Inactive)";

//print_r($transactionInfo );
 if($transactionInfo['cardtype'] != 'Check')
 {
 ?>
        <table border="0" cellpadding="0" width="100%" cellspacing="0" align="center">
          <tr>
            <td width="100%" valign="top" align="center"  >&nbsp;
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
                  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Credit&nbsp; Card&nbsp;Transaction</span></td>
                  <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
                  <td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
                  <td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
                </tr>
                <tr>
                  <td class="lgnbd" width="987" colspan="5"><table border="0" cellpadding="0" cellspacing="0" width="750" height="544" align="center" >
                      <tr>
                        <td width="100%" height="494" valign="top" align="left"><table width="691" height="165"  align="center" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
                            <tr>
                              <td height="11" valign="top" align="left" width="19">&nbsp;</td>
                              <td valign="top" align="left" width="652"  height="11"><img border="0" src="images/cbg.jpg" width="1" height="2"></td>
                              <td height="11" valign="top" align="left" width="28">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="167" valign="top" align="left" width="19">&nbsp;</td>
                              <td height="167" valign="top" align="left" width="100%" ><table width="100%" cellpadding="2" cellspacing="0" style="border:1px solid black">
                                  <tr align="center" valign="middle" bgcolor="#CCCCCC">
                                    <td colspan="2" class="tdbdr"><span class="subhd"><strong>Customer Information for <?=$transactionInfo['reference_number']?>
                                      </strong></span></td>
                                  </tr>
                                  <tr>
                                    <td width="47%" align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">First Name : </font></td>
                                    <td width="53%" valign="middle" class="tdbdr"><font color="#001188"> &nbsp;
                                      <input type="text" name="firstname1" size="19" maxlength="75" value="<?=$transactionInfo['name']?>" >
                                      </font></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Last Name :</font></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp;
                                      <input type="text" name="lastname1" size="19" maxlength="75" value="<?=$transactionInfo['surname']?>" >
                                      </font></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Address: </font><br></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp;
                                      <input type="text" name="address"size="45" maxlength="100" value="<?=$transactionInfo['address']?>" >
                                      </font></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">City :</font></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp;
                                      <input type="text" name="city"  size="35" maxlength="50" value="<?=$transactionInfo['city']?>" >
                                      </font></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Country :</font></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp;
                                      <!--	<input type="text" name="country2" size="20" value="<?=$transactionInfo['country']?>" > -->
                                      <select name="country"  style="font-family:arial;font-size:11px;width:200px">
                                        <?=func_get_country_select($transactionInfo['country']) ?>
                                      </select>
                                      </font></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">State :</font></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; <font color="#001188">
                                      <input type="text" name="zip"  size="20" value="<?=$transactionInfo['state']?>">
                                      </font>
                                      <!--<input type="text" name="state2" size="20" value="<?=$transactionInfo[15]?>" >-->
                                      </font></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Zip code :</font></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188"><font color="#001188">
                                      <font color="#001188">&nbsp;</font>
                                      <input name="zipcode" type="text" id="zipcode" value="<?=$transactionInfo['zipcode']?>" size="20" >
                                    </font>
                                      </font></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Phone : </font></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp;
                                        <font color="#001188">
                                        <input type="text" name="phonenumber" size="20" value="<?=$transactionInfo['phonenumber_format']?>" >
                                      </font>                                      </font></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">An email confirmation of&nbsp;&nbsp;<br>
                                      this order will be sent to :</font></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;
                                      <input type="text" name="email2" size="40" maxlength="100" value="<?=$transactionInfo['email']?>" >
                                      </font></td>
                                  </tr>
                                  <tr bgcolor="#CCCCCC">
                                    <td colspan="2" align="center" valign="middle" class="tdbdr"><span class="subhd"><strong>Payment Information</strong></span></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Card Number :</font></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp;
                                      <input type="text" name="number" size="17" maxlength="16" value="<?=$transactionInfo['CCnumber_format']?>" >
                                      </font><font size="1" face="Verdana"><a href="#" onClick='javascript:window.open("../images/creditcard.gif","","width=500,height=350")' class="link">CVV2</a></font><font color="#001188">
                                      <input type="text" name="cvv2" size="3" maxlength="3"  value="<?=$transactionInfo['cvv']?>" >
                                      </font> </td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Type : </font></td>
                                    <td valign="middle" class="tdbdr">&nbsp;
                                      <!-- <input type="text" name="ctype" size="20" value="<?=$transactionInfo[21]?>"  > -->
                                      <select size="1" name="cardtype" style="font-size: 8pt; font-family: Verdana">
                                        <option value="Master">Master Card</option>
                                        <option value="Visa">Visa</option>
                                      </select>
                                      <script language="javascript">
										 document.view.cardtype.value='<?=$transactionInfo['cardtype']?>';
									</script>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Expiration Date :</font></td>
                                    <td valign="middle" class="tdbdr">&nbsp;
                                      <input type="text" name="expdate" size="20" value="<?=$transactionInfo['validupto']?>" >
                                    </td>
                                  </tr>
                                  <tr>
                                    <td  align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Amount of Money :</font><br></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;
                                      <input type="text" name="amount" size="15" maxlength="50" value="<?=$transactionInfo['amount']?>" >
                                      </font></td>
                                  </tr>
                                  <tr>
                                    <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Billing Date(mm-dd-yyyy) : </font></td>
                                    <td class="tdbdr">&nbsp;<font color="#001188">
                                      <input type="text" name="setbilldate" size="20" value="<?=func_get_date_inmmddyy($transactionInfo['billingDate'])?>" >
                                      </font></td>
                                  </tr>
                                  <tr>
                                    <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Product Description : </font></td>
                                    <td class="tdbdr">&nbsp;<font color="#001188">
                                      <input type="text" name="txtproductDescription" size="30" maxlength="200" value="<?=$transactionInfo['productdescription']?>" >
                                      </font></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Date/Time :</font></td>
                                    <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;
                                      <?=func_get_date_time_12hr($transactionInfo['transactionDate'])?>
                                      </font></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="tdbdr2"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">IP Address :</font> </td>
                                    <td valign="middle" ><font size="1" face="Verdana" color="#000000">&nbsp;
                                      <?=$transactionInfo['ipaddress']?>
                                      </font> <img border="0" SRC="<?=$tmpl_dir?>/images/mastercard.jpg"> <img border="0" SRC="<?=$tmpl_dir?>/images/visa.jpg"> </td>
                                  </tr><tr bgcolor="#CCCCCC">
                                    <td colspan="2" align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Subscription Info </strong>&nbsp;
                                      <!--<a href="javascript:void(0)" onclick="showDetails('div3')">show/hide</a>-->
                                      </span></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">
                                      <table width="100%" cellpadding="0" cellspacing="0" align="center">
                                         <tr>
                                           <td width="50%" height="30" align="right" valign="middle" class="tdbdr1">Status : </td>
                                           <td valign="middle" class="tdbdr">&nbsp;<font size="1" face="Verdana" color="#000000">
                                           <span class="cl1"><font face='verdana' size='1'><strong>
                                           <font size="1" face="Verdana" color="#000000"><font face='verdana' size='1'><strong><font size="1" face="Verdana" color="#000000"><font face='verdana' size='1'><strong><font size="1" face="Verdana" color="#000000"><font face='verdana' size='1'><strong><font size="1" face="Verdana" color="#000000"><font face='verdana' size='1'><strong>
                                           <?=($transactionInfo['subAcc']['recur_day'] && $transactionInfo['subAcc']['recur_charge']?
		($transactionInfo['td_enable_rebill']?
			($transactionInfo['td_is_a_rebill']?"REBILL<BR>":"Rebilling Enabled<BR>").($transactionInfo['td_recur_next_date']?$transactionInfo['td_recur_next_date']:"")
		:"DISABLED REBILL<BR>")
	:"Not a rebill<BR>")?>
                                           <?=$act?></strong></font></font></strong></font></font></strong></font></font></strong></font></font></strong></font></span></font></td>
                                         </tr>   
										 <? if ($transactionInfo['td_username']) { ?>                                    
										 <tr>
                                          <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">UserName : </font></td>
                                          <td valign="middle" class="tdbdr">&nbsp;
                                            <font size="1" face="Verdana" color="#000000">
                                            <?=$transactionInfo['td_username']?>
                                           </font> </td>
                                        </tr>                                       
										 <tr>
                                          <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Password : </font></td>
                                          <td valign="middle" class="tdbdr">&nbsp;
                                            <font size="1" face="Verdana" color="#000000">
                                            <?=$transactionInfo['td_password']?>
                                           </font> </td>
                                        </tr>
										 <? } ?>         
                                        <tr>
                                          <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Initial :</font></td>
                                          <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;$
                                                <?=formatMoney($transactionInfo['subAcc']['rd_initial_amount'])?>
    Every
    <?=$transactionInfo['subAcc']['rd_trial_days']?>
    Days</font></td>
                                        </tr>
                                        <tr>
                                          <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Recurring : </font></td>
                                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <font size="1" face="Verdana" color="#000000"> $
                                                  <?=formatMoney($transactionInfo['subAcc']['recur_charge'])?>
    Every
    <?=$transactionInfo['subAcc']['recur_day']?>
    Days</font></font></td>
                                        </tr>
                                        <tr>
                                          <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Schedule : </font></td>
                                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <font size="1" face="Verdana" color="#000000">
                                            <?=$transactionInfo['subAcc']['payment_schedule']?>
                                          </font></font></td>
                                        </tr>
                                        <tr>
                                          <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><?=($transactionInfo['td_enable_rebill']?"Rebills":"Expires")?> : </font></td>
                                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <font size="1" face="Verdana" color="#000000">
                                            <font color="#001188"><font size="1" face="Verdana" color="#000000">
                                            <?=$transactionInfo['td_recur_next_date']?>
                                          </font></font>                                          </font></font></td>
                                        </tr>
                                      </table></td>
                                  </tr>
                                  <tr bgcolor="#CCCCCC">
                                    <td colspan="2" align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Shipping Info </strong>&nbsp;
                                      <!--<a href="javascript:void(0)" onclick="showDetails('div3')">show/hide</a>-->
                                      </span></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">
                                      <table width="100%" cellpadding="0" cellspacing="0" align="center">
                                         <tr>
                                           <td align="right" class="tdbdr1" ><p> Tracking Number: </p></td>
                                           <td class="tdbdr1" ><p> &nbsp;
                                                   <input name="td_tracking_id" type="text" id="td_tracking_id3" value="<?=$transactionInfo['td_tracking_id']?>" <?=$editable?>>
                                           </p></td>
                                         </tr>   
										 <? if ($transactionInfo['td_username']) { ?>                                    
										 <tr>
										   <td align="right" class="tdbdr1" ><p> Tracking Link: </p></td>
                                          <td class="tdbdr1" ><p> &nbsp;
                                                  <input name="td_tracking_link" type="text" id="td_tracking_id" value="<?=$transactionInfo['td_tracking_link']?>" size="35" <?=$editable?>>
                                          </p></td>
									    </tr>                                       
										 <tr>
										   <td align="right" class="tdbdr1" ><p> Shipping Company: </p></td>
                                          <td class="tdbdr1" > &nbsp;
                                                  <input name="td_tracking_company" type="text" id="td_tracking_company" value="<?=$transactionInfo['td_tracking_company']?>" size="35" <?=$editable?>>
                                                  </td>
									    </tr>
										 <? } ?>         
                                        <tr>
                                          <td align="right" class="tdbdr1" ><p> Date Shipped:</p></td>
                                          <td class="tdbdr1" ><p> &nbsp;
                                                  <input name="td_tracking_ship_date" type="text" id="td_tracking_ship_date" value="<?=$transactionInfo['td_tracking_ship_date']?>" size="25" <?=$editable?>>
                                                  </p></td>
                                        </tr>
                                        <tr>
                                          <td align="right" class="tdbdr1" ><p> Estimated Arrival Time:</p></td>
                                          <td class="tdbdr1" ><p> &nbsp;
                                                  <input name="td_tracking_ship_est" type="text" id="td_tracking_ship_est" value="<?=$transactionInfo['td_tracking_ship_est']?>" size="25" <?=$editable?>>
                                                  </p></td>
                                        </tr>
                                        <tr>
                                          <td align="right" class="tdbdr1" ><p> Additional Information:</p></td>
                                          <td class="tdbdr1" ><p> &nbsp;
                                                  <textarea name="td_tracking_info" cols="25" id="td_tracking_info" <?=$editable?>><?=$transactionInfo['td_tracking_info']?>
                                              </textarea>
                                          </p></td>
                                        </tr>
                                      </table></td>
                                  </tr>
                                </table></td>
                              <td height="2" valign="top" align="left" width="1" style="border-right:1px solid white"></td>
                            </tr>
                            <tr>
                              <td height="1" valign="top" align="left" width="19">&nbsp;</td>
                              <td height="1" valign="top" align="left" width="652">&nbsp;</td>
                              <td height="1" valign="top" align="left" width="28">&nbsp;</td>
                            </tr>
                          </table>
                          <input type="hidden" name="cancel" value="">
                          </input></td>
                      </tr>
                    </table></td>
                </tr>
                <tr>
                  <td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
                  <td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
                  <td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
                </tr>
              </table>
              <?
	}
	else
	{
?>
              <table border="0" cellpadding="0" width="100%" cellspacing="0"  align="center">
                <tr>
                  <td width="90%" valign="top" align="center" >&nbsp;
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
                        <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Check&nbsp;Transaction</span></td>
                        <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
                        <td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
                        <td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
                      </tr>
                      <tr>
                        <td class="lgnbd" width="987" colspan="5"><table border="0" cellpadding="0" cellspacing="0" width="700" align="center">
                            <tr>
                              <td width="100%" valign="top" align="left">&nbsp;
                                <table width="100%" cellspacing="0" cellpadding="2" style="border:1px solid black">
                                  <tr bgcolor="#CCCCCC">
                                    <td align="center" valign="middle" class="tdbdr"><span class="subhd"><strong>Customer Information
                                      </strong>&nbsp;
                                      <!--<a href="javascript:void(0)" onclick="showDetails('div2')">show/hide</a>-->
                                      </span></td>
                                  </tr>
                                  <tr>
                                    <td><table width="100%" cellpadding="2" cellspacing="0" align="center">
                                      <tr>
                                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">First Name : </font></td>
                                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp;
                                              <input type="text" name="firstname1" size="19" maxlength="75" value="<?=$transactionInfo['name']?>" >
                                        </font></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Last Name :</font></td>
                                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp;
                                              <input type="text" name="lastname1" size="19" maxlength="75" value="<?=$transactionInfo['surname']?>" >
                                        </font></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Address: </font><br></td>
                                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp;
                                              <input type="text" name="address"size="45" maxlength="100" value="<?=$transactionInfo['address']?>" >
                                        </font></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">City :</font></td>
                                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp;
                                              <input type="text" name="city"  size="35" maxlength="50" value="<?=$transactionInfo['city']?>" >
                                        </font></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Country :</font></td>
                                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp;
                                              <!--	<input type="text" name="country2" size="20" value="<?=$transactionInfo['country']?>" > -->
                                              <select name="country"  style="font-family:arial;font-size:11px;width:200px">
                                                <?=func_get_country_select($transactionInfo['country']) ?>
                                              </select>
                                        </font></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">State :</font></td>
                                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; <font color="#001188">
                                          <input type="text" name="zip"  size="20" value="<?=$transactionInfo['state']?>">
                                          </font>
                                              <!--<input type="text" name="state2" size="20" value="<?=$transactionInfo[15]?>" >-->
                                        </font></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Zip code :</font></td>
                                        <td valign="middle" class="tdbdr"><font color="#001188"><font color="#001188"> <font color="#001188">&nbsp;</font>
                                                <input name="zipcode" type="text" id="zipcode" value="<?=$transactionInfo['zipcode']?>" size="20" >
                                        </font> </font></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Phone : </font></td>
                                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; <font color="#001188">
                                          <input type="text" name="phonenumber" size="20" value="<?=$transactionInfo['phonenumber_format']?>" >
                                        </font> </font></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">An email confirmation of&nbsp;&nbsp;<br>
    this order will be sent to :</font></td>
                                        <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;
                                              <input type="text" name="email2" size="40" maxlength="100" value="<?=$transactionInfo['email']?>" >
                                        </font></td>
                                      </tr>
                                        <tr bgcolor="#CCCCCC">
                                          <td colspan="2" align="center" valign="middle" class="tdbdr"><strong><span class="subhd">Transaction Information</span></strong></td>
                                        </tr>
                                        <!--                <tr>
                  <td align="right" width=50% class="tdbdr1"><font size="2" face="Verdana" color="#000000">Invoice/Reference
                    ID : </font></td>
                  <td width=50% class="tdbdr">&nbsp;&nbsp;<font color="#001188">
                    <input type="text" name="invoiceid" size="20">
                    </font></td>
                </tr>
-->
                                        <tr>
                                          <td width="47%" align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Check # : </font></td>
                                          <td width="53%" class="tdbdr">&nbsp;&nbsp;<font color="#001188">
                                            <input type="text" name="chequenumber" size="20" maxlength="50" value="<?=$transactionInfo['bankaccountnumber']?>" >
                                            </font></td>
                                        </tr>
                                        <tr>
                                          <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Amount(US Dollars) : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188">
                                            <input type="text" name="amount"size="9" maxlength="50" value="<?=$transactionInfo['amount']?>" >
                                            </font></td>
                                        </tr>
                                        <tr>
                                          <td class="tdbdr1" align="right"><font size="2" face="Verdana" color="#000000">Account Type : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188">
                                            <!-- <input type="text" name="account" size="10" value="<?=$transactionInfo[30]?>" > -->
                                            <?php
						$checks="";
						$checkc="";
						if($transactionInfo['accounttype'] =="checking") {
							$checkc="Checked";
						} else {
							$checks="Checked";
						}
						?>
                                            <input type="radio" name="accounttype" value="checking" <?=$checkc?>>
                                            <font size="1" face="Verdana" color="#000000">Checking</font>&nbsp;&nbsp;
                                            <input type="radio" name="accounttype" value="savings" <?=$checks?>>
                                            <font size="1" face="Verdana" color="#000000">Savings</font> </font> </td>
                                        </tr>
                                        <tr>
                                          <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Billing Date(mm-dd-yyyy) : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp; <font color="#001188">
                                            <input type="text" name="setbilldate" size="20" value="<?=func_get_date_inmmddyy($transactionInfo['billingDate'])?>" >
                                          </font><font color="#001188">
                                            <!--	<input type="text" name="setbilldate2" size="20" value="<?=func_get_date_inmmddyy($transactionInfo[38])?>" > -->
                                            </font> </td>
                                        </tr>
                                        <tr>
                                          <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Product Description : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188">
                                            <font color="#001188">
                                            <input type="text" name="txtproductDescription" size="30" maxlength="200" value="<?=$transactionInfo['productdescription']?>" >
                                            </font>                                            </font></td>
                                        </tr>
                                        <tr bgcolor="#CCCCCC">
                                          <td colspan="2" align="center" valign="middle" class="tdbdr"><span class="subhd"><strong>Bank Information</strong>&nbsp;
                                            <!-- <a href="javascript:void(0)" onclick="showDetails('div1')">show/hide</a> -->
                                            </span></td>
                                        </tr>
                                        <tr>
                                          <td colspan="2"><table width="100%" cellpadding="2" cellspacing="0" align="center">
                                              <tr>
                                                <td align="right" class="tdbdr1" width="50%"><font size="2" face="Verdana" color="#000000"> Bank Name : </font></td>
                                                <td class="tdbdr" width="50%">&nbsp;&nbsp;<font color="#001188">
                                                  <input type="text" name="bankname" size="45" maxlength="75" value="<?=$transactionInfo['bankname']?>" >
                                                  </font></td>
                                              </tr>
                                              <tr>
                                                <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Bank Routing Code : </font></td>
                                                <td class="tdbdr">&nbsp;&nbsp;<font color="#001188">
                                                  <input type="text" name="bankroutingcode" size="9" maxlength="9" value="<?=$transactionInfo['bankroutingcode']?>" >
                                                  </font></td>
                                              </tr>
                                              <tr>
                                                <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font size="2" face="Verdana" color="#000000">Bank Account # : </font></font></td>
                                                <td class="tdbdr">&nbsp;&nbsp;<font color="#001188">
                                                  <input type="text" name="bankaccountno"size="25" maxlength="15"value="<?=$transactionInfo['bankaccountnumber']?>" >
                                                  </font></td>
                                              </tr>
                                            </table></td>
                                        </tr>
                                    </table></td>
                                  </tr>
                                  <tr bgcolor="#CCCCCC">
                                    <td align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Shipping Info </strong>&nbsp;
                                          <!--<a href="javascript:void(0)" onclick="showDetails('div3')">show/hide</a>-->
                                    </span></td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <table width="100%" cellpadding="0" cellspacing="0" align="center">
                                        <tr>
                                          <td align="right" class="tdbdr1" ><p> Tracking Number: </p></td>
                                          <td ><p> &nbsp;
                                                  <input name="td_tracking_id" type="text" id="td_tracking_id3" value="<?=$transactionInfo['td_tracking_id']?>" <?=$editable?>>
                                          </p></td>
                                        </tr>
                                        <? if ($transactionInfo['td_username']) { ?>
                                        <tr>
                                          <td align="right" class="tdbdr1" ><p> Tracking Link: </p></td>
                                          <td ><p> &nbsp;
                                                  <input name="td_tracking_link" type="text" id="td_tracking_id" value="<?=$transactionInfo['td_tracking_link']?>" size="35" <?=$editable?>>
                                          </p></td>
                                        </tr>
                                        <tr>
                                          <td align="right" class="tdbdr1" ><p> Shipping Company: </p></td>
                                          <td >
                                                  <input name="td_tracking_company" type="text" id="td_tracking_company" value="<?=$transactionInfo['td_tracking_company']?>" size="35" <?=$editable?>>
                                                  
</td>
                                        </tr>
                                        <? } ?>
                                        <tr>
                                          <td align="right" class="tdbdr1" ><p> Date Shipped:</p></td>
                                          <td ><p> &nbsp;
                                                  <input name="td_tracking_ship_date" type="text" id="td_tracking_ship_date" value="<?=$transactionInfo['td_tracking_ship_date']?>" size="25" <?=$editable?>>
                                          </p></td>
                                        </tr>
                                        <tr>
                                          <td align="right" class="tdbdr1" ><p> Estimated Arrival Time:</p></td>
                                          <td ><p> &nbsp;
                                                  <input name="td_tracking_ship_est" type="text" id="td_tracking_ship_est" value="<?=$transactionInfo['td_tracking_ship_est']?>" size="25" <?=$editable?>>
                                          </p></td>
                                        </tr>
                                        <tr>
                                          <td align="right" class="tdbdr1" ><p> Additional Information:</p></td>
                                          <td ><p> &nbsp;
                                                  <textarea name="td_tracking_info" cols="25" id="td_tracking_info" <?=$editable?>><?=$transactionInfo['td_tracking_info']?>
                                              </textarea>
                                          </p></td>
                                        </tr>
                                    </table></td>
                                  </tr>
                                  <!--Div -->
                                  <tr bgcolor="#CCCCCC">
                                    <td align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Set Recurring Date</strong>&nbsp;
                                      <!--<a href="javascript:void(0)" onclick="showDetails('div3')">show/hide</a>-->
                                      </span></td>
                                  </tr>
                                  <tr>
                                    <td><table width="100%" cellpadding="0" cellspacing="0" align="center">
                                        <tr>
                                          <td height="30" align="right" valign="middle" class="tdbdr1">Status : </td>
                                          <td valign="middle" class="tdbdr1">&nbsp;<font size="1" face="Verdana" color="#000000">
                                            <span class="cl1"><font face='verdana' size='1'><strong>
                                            <font size="1" face="Verdana" color="#000000"><font face='verdana' size='1'><strong>
                                            <font size="1" face="Verdana" color="#000000"><font face='verdana' size='1'><strong><font size="1" face="Verdana" color="#000000"><font face='verdana' size='1'><strong>
                                            <font size="1" face="Verdana" color="#000000"><font face='verdana' size='1'><strong><font size="1" face="Verdana" color="#000000"><font face='verdana' size='1'><strong><font size="1" face="Verdana" color="#000000"><font face='verdana' size='1'><strong><font size="1" face="Verdana" color="#000000"><font face='verdana' size='1'><strong><font size="1" face="Verdana" color="#000000"><font face='verdana' size='1'><strong>
                                            <?=($transactionInfo['subAcc']['recur_day'] && $transactionInfo['subAcc']['recur_charge']?
		($transactionInfo['td_enable_rebill']?
			($transactionInfo['td_is_a_rebill']?"REBILL<BR>":"Rebilling Enabled<BR>").($transactionInfo['td_recur_next_date']?$transactionInfo['td_recur_next_date']:"")
		:"DISABLED REBILL<BR>")
	:"Not a rebill<BR>")?>
                                            <?=$act?>
                                            </strong></font></font></strong></font></font></strong></font></font></strong></font></font></strong></font></font>                                            </strong></font></font></strong></font></font>                                            </strong></font></font>                                            </strong></font></span></strong>              

                                          </font></td>
                                        </tr>
                                        <tr>
                                          <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Rebilling Enabled : </font></td>
                                          <td valign="middle" class="tdbdr1">&nbsp;
                                              <input type="checkbox" name="chk_recur_date" value="Y" <?=($transactionInfo['td_enable_rebill']?"checked":"")?> >
                                          </td>
                                        </tr>
                                        <tr>
                                          <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Initial :</font></td>
                                          <td valign="middle" class="tdbdr1"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;$
                                                <?=formatMoney($transactionInfo['subAcc']['rd_initial_amount'])?>
    Every
    <?=$transactionInfo['subAcc']['rd_trial_days']?>
    Days</font></td>
                                        </tr>
                                        <tr>
                                          <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Recurring : </font></td>
                                          <td valign="middle" class="tdbdr1"><font color="#001188">&nbsp; <font size="1" face="Verdana" color="#000000"> $
                                                  <?=formatMoney($transactionInfo['subAcc']['recur_charge'])?>
    Every
    <?=$transactionInfo['subAcc']['recur_day']?>
    Days</font></font></td>
                                        </tr>
                                        <tr>
                                          <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Schedule : </font></td>
                                          <td valign="middle" class="tdbdr1"><font color="#001188">&nbsp; <font size="1" face="Verdana" color="#000000">
                                            <?=$transactionInfo['subAcc']['payment_schedule']?>
                                          </font></font></td>
                                        </tr>
                                        <tr>
                                          <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">
                                            <?=($transactionInfo['td_enable_rebill']?"Rebills":"Expires")?>
    : </font></td>
                                          <td valign="middle" class="tdbdr1"><font color="#001188">&nbsp; <font size="1" face="Verdana" color="#000000">
                                            <?=$transactionInfo['td_recur_next_date']?>
                                          </font></font></td>
                                        </tr>
                                      </table></td>
                                  </tr>
                                </table>
                                <?php if($act!="view") {?>                                <?php }?>
                              </td>
                            </tr>
                          </table>
                          <input type="hidden" name="cancel" value="cancel">
                          </input>
                          <table align="center">
                            <tr>
                              <td><?php if($act!="view") {?>
                                <a href="#" onclick="func_submit()"><img   SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>
                                <?php } else{ ?>
                                <a href= "#" onClick ="javascript:history.back()" > <img   SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>
                                <?php } ?>
</td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr>
                        <td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
                        <td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
                        <td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
              <?php
}
?>
            </td>
          </tr>
        </table>
      </form></td>
  </tr>
</table>
<?php
include 'includes/footer.php';
?>
