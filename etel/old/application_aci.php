<?php 
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//include 'includes/sessioncheck.php';
include 'includes/sessioncheck.php';
$headerInclude="startHere";
require_once("includes/header.php");

$invalidlogin = "";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$message = (isset($HTTP_GET_VARS['msgcom'])?quote_smart($HTTP_GET_VARS['msgcom']):"");
if($sessionlogin!=""){
		     
	$sql_select_qry ="select *  from cs_companydetails where userid=$sessionlogin";
	if(!($run_select_qry =mysql_query($sql_select_qry,$cnn_cs))) {
		echo mysql_errno().": ".mysql_error()."<BR>";
		echo "Cannot execute query.";
		exit();
	}	
	if($show_select_value = mysql_fetch_array($run_select_qry)) { 
		if($message=="" && $HTTP_POST_VARS['first_name']){
			$first_name = (isset($HTTP_POST_VARS['first_name'])?quote_smart($HTTP_POST_VARS['first_name']):"");
			$family_name = (isset($HTTP_POST_VARS['family_name'])?quote_smart($HTTP_POST_VARS['family_name']):"");
			$job_title = (isset($HTTP_POST_VARS['job_title'])?quote_smart($HTTP_POST_VARS['job_title']):"");
			$contact_email = (isset($HTTP_POST_VARS['contact_email'])?quote_smart($HTTP_POST_VARS['contact_email']):"");
			$confirm_contact_email = (isset($HTTP_POST_VARS['confirm_contact_email'])?quote_smart($HTTP_POST_VARS['confirm_contact_email']):"");
			$contact_phone = (isset($HTTP_POST_VARS['contact_phone'])?quote_smart($HTTP_POST_VARS['contact_phone']):"");
			$how_about_us = (isset($HTTP_POST_VARS['how_about_us'])?quote_smart($HTTP_POST_VARS['how_about_us']):"");
			$how_about_us_other = (isset($HTTP_POST_VARS['how_about_us_other'])?quote_smart($HTTP_POST_VARS['how_about_us_other']):"");
			$reseller_id = (isset($HTTP_POST_VARS['reseller_other'])?quote_smart($HTTP_POST_VARS['reseller_other']):1);
			$sTitle 				= (isset($HTTP_POST_VARS['cboTitle'])?quote_smart($HTTP_POST_VARS['cboTitle']):"");
			$sYear 					= (isset($HTTP_POST_VARS['cboYear'])?quote_smart($HTTP_POST_VARS['cboYear']):"");
			$sMonth					= (isset($HTTP_POST_VARS['cboMonth'])?quote_smart($HTTP_POST_VARS['cboMonth']):"");
			$sDay					= (isset($HTTP_POST_VARS['cboDay'])?quote_smart($HTTP_POST_VARS['cboDay']):"");	
			$sDateOfBirth			= ($sYear."-".$sMonth."-".$sDay);
			$sSex					= (isset($HTTP_POST_VARS['cboSex'])?quote_smart($HTTP_POST_VARS['cboSex']):"");
			$sAddress				= (isset($HTTP_POST_VARS['txtAddress'])?quote_smart($HTTP_POST_VARS['txtAddress']):"");
			$sPostCode				= (isset($HTTP_POST_VARS['txtPostCode'])?quote_smart($HTTP_POST_VARS['txtPostCode']):"");
			$sResidenceTelephone	= (isset($HTTP_POST_VARS['residence_telephone'])?quote_smart($HTTP_POST_VARS['residence_telephone']):"");
			$sFax					= (isset($HTTP_POST_VARS['fax'])?quote_smart($HTTP_POST_VARS['fax']):"");
			$cd_contact_im			= (isset($HTTP_POST_VARS['cd_contact_im'])?quote_smart($HTTP_POST_VARS['cd_contact_im_type'].$HTTP_POST_VARS['cd_contact_im']):"");

			$str_update_query  = " update cs_companydetails set cd_contact_im='$cd_contact_im', first_name = '$first_name', family_name = '$family_name', ";
			$str_update_query .= " job_title = '$job_title', contact_email = '$contact_email', contact_phone = '$contact_phone', how_about_us = '$how_about_us', ";
			$str_update_query .= " stitle = '$sTitle',sdateofbirth='$sDateOfBirth',ssex='$sSex',sAddress='$sAddress',sPostCode='$sPostCode',sResidenceTelephone='$sResidenceTelephone',sFax='$sFax',reseller_other ='$how_about_us_other'";
			$str_update_query .= " where userid=$sessionlogin";
		
			//print($str_update_query);
		
			mysql_query($str_update_query,$cnn_cs) or dieLog(mysql_error());
		
	}
?>
<script language="javascript" src="scripts/general.js"></script>
<script language="javascript">

function HelpWindow() {
   advtWnd=window.open("aboutcompany.htm","Help","'status=1,scrollbars=1,width=500,height=550,left=0,top=0'");
   advtWnd.focus();
}

</script>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
    <td width="83%" valign="top" align="center"  height="333">&nbsp;
      <?php beginTable() ?>
      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="disbd">
        <tr>
          <td width="100%" valign="top" align="center"><table  width="100%" height="40"  valign="bottom" >
              <tr>
                <td width="100%" valign="middle" align="left" height="40" bgcolor="#DDDDDD"><img border="0" src="<?=$tmpl_dir?>images/application.gif"><img border="0" src="<?=$tmpl_dir?>images/aboutyou.gif"><img border="0" src="<?=$tmpl_dir?>images/yourcompany1.gif"><img border="0" src="<?=$tmpl_dir?>images/yourprocess.gif"><img border="0" src="<?=$tmpl_dir?>images/yourbank.gif"><img border="0" src="<?=$tmpl_dir?>images/finishingline.gif"></td>
              </tr>
            </table>
            <?php 
			
			if($message!=""){
			?>
            <table>
              <tr>
                <td><font face='verdana' color='#ff0000' size="1"><?php echo $message ?></font> </td>
              </tr>
            </table>
            <?php }?>
&nbsp;
              <table border="0" cellpadding="0"  height="100" width="100%">
                <tr>
                  <td align="center"  height="30" colspan="2" bgcolor="#CCCCCC" class="whitehd">Company Information </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Company Name &nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' maxlength="100" name="companyname" style="font-family:arial;font-size:10px;width:240px" value="<?=htmlentities($show_select_value['companyname'])?>">
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Legal Company Name &nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' maxlength="100" name="legal_companyname" style="font-family:arial;font-size:10px;width:240px" value="<?=htmlentities($show_select_value['legal_name'])?>">
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Company Website &nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input name="url1" type="text" src='url' id="url1" style="font-family:arial;font-size:10px;width:240px" value="<?=$show_select_value['url1']?htmlentities($show_select_value['url1']):"http://"?>" maxlength="100">
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; User Name &nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"><b>
                    <?=$show_select_value['username']?>
                    </b></font></td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Address &nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' name="address" value="<?=htmlentities($show_select_value['address'])?>" style="font-family:arial;font-size:10px;width:240px">
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; City &nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' name="city" value="<?=htmlentities($show_select_value['city'])?>" style="font-family:arial;font-size:10px;width:240px">
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Country&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><select name="country" id="country" style="font-family:arial;font-size:10px;width:200px" onchange="return validator()">
				     <?=func_get_country_select($show_select_value['bank_country'],1)?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; State&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><select name="state" id="state" style="font-family:arial;font-size:10px;width:200px">
                      <?=func_get_state_select($show_select_value['state']) ?>
                    </select>
                  </td>
                </tr>
                <input type="hidden" name="company" value="company">
                <input type="hidden" name="username" value="<?=$show_select_value['state']?>">
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Other State&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" name="ostate" id="ostate" style="font-family:arial;font-size:10px;width:150px" value="<?=htmlentities($show_select_value['ostate'])?>">
                  </td>
                </tr>
                <script language="javascript">
						if (document.getElementById('country').selectedIndex != -1) {
							if(document.getElementById('country').options[document.getElementById('country').selectedIndex].text=="United States") {
								document.getElementById('ostate').disabled= true;
								document.getElementById('ostate').value= "";
								document.getElementById('state').disabled = false;
							} else {
								document.getElementById('state').disabled = true;
								document.getElementById('state').value= "";
								document.getElementById('ostate').disabled= false;
							}
						}
					</script>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Incorporated Country&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><select name="inc_country"  style="font-family:arial;font-size:10px;width:200px">
                      <?=func_get_country_select($show_select_value['bank_country'],$show_select_value['incorporated_country'])?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Incorporated Number &nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='' name="inc_number" value="<?=$show_select_value['incorporated_number']?>" style="font-family:arial;font-size:10px;width:240px">
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Zipcode&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' name="zipcode" value="<?=$show_select_value['zipcode']?>" style="font-family:arial;font-size:10px;width:140px">
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Physical Company Address&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><textarea title="req" name="physical_address"  style="font-family:arial;font-size:10px;width:240px;height:60px"><?=htmlentities($show_select_value['physical_address'])?>
</textarea>
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Fax Number&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' name="faxnumber" value="<?=$show_select_value['fax_number']?>" style="font-family:arial;font-size:10px;width:140px">
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Fax DBA&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' name="fax_dba" value="<?=$show_select_value['fax_dba']?>" style="font-family:arial;font-size:10px;width:140px">
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Telephone Number &nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' maxlength="15" name="phonenumber" style="font-family:arial;font-size:10px;width:100px" value="<?=$show_select_value['phonenumber']?>">
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Cellular &nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' maxlength="15" name="cellular" style="font-family:arial;font-size:10px;width:100px" value="<?=$show_select_value['cellular']?>">
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Maximum Ticket Amount&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='numeric' name="max_ticket_amt" value="<?=$show_select_value['max_ticket_amt']?>" style="font-family:arial;font-size:10px;width:140px">
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Minimum Ticket Amount&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='numeric' name="min_ticket_amt" value="<?=$show_select_value['min_ticket_amt']?>" style="font-family:arial;font-size:10px;width:140px">
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Technical Contact Details&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><textarea name="tech_contact_details"  style="font-family:arial;font-size:10px;width:240px;height:60px"><?=htmlentities($show_select_value['technical_contact_details'])?>
</textarea>
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Administrative Contact Details&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><textarea name="admin_contact_details"  style="font-family:arial;font-size:10px;width:240px;height:60px"><?=htmlentities($show_select_value['admin_contact_details'])?>
</textarea>
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Goods/Services List And Description&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><textarea name="goods_list"  style="font-family:arial;font-size:10px;width:240px;height:60px"><?=htmlentities($show_select_value['goods_list'])?>
</textarea>
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Explain Currently Used Anti Fraud System&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><textarea name="current_anti_fraud_system"  style="font-family:arial;font-size:10px;width:240px;height:60px"><?=htmlentities($show_select_value['current_anti_fraud_system'])?>
</textarea>
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Explain In Detail Your Customer Service Program&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><textarea name="customer_service_program"  style="font-family:arial;font-size:10px;width:240px;height:60px"><?=htmlentities($show_select_value['customer_service_program'])?>
</textarea>
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Describe Your Refund Policy&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><textarea name="refund_policy"  style="font-family:arial;font-size:10px;width:240px;height:60px"><?=htmlentities($show_select_value['refund_policy'])?>
</textarea>
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; <b>Previous Sales Volume</b>&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Last Month&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><select name="volume_last_month" style="font-family:arial;font-size:10px;width:120px"  title="reqmenu">
                      <?php func_select_merchant_volume($show_select_value['volume_last_month']); ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; 30 days previous&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><select name="volume_prev_30days" style="font-family:arial;font-size:10px;width:120px" title="reqmenu">
                      <?php func_select_merchant_volume($show_select_value['volume_prev_30days']); ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; 60 days previous&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><select name="volume_prev_60days" style="font-family:arial;font-size:10px;width:120px" title="reqmenu">
                      <?php func_select_merchant_volume($show_select_value['volume_prev_60days']); ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Totals &nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' maxlength="15" name="totals" style="font-family:arial;font-size:10px;width:120px" value="<?=$show_select_value['totals']?>">
                  </td>
                </tr>
                <?php 
$gateway_id = $show_select_value['gateway_id'];
	if($gateway_id !=-1) {
		$gatewayCompanyName = func_get_value_of_field($cnn_cs,"cs_companydetails","companyname","userid",$gateway_id);
	}
?>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; <b>Forecasted Volume </b>&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; First Month&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><select name="forecast_first_month" style="font-family:arial;font-size:10px;width:120px" title="reqmenu">
                      <?php func_select_merchant_volume($show_select_value['forecast_volume_1month']); ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Second Month&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><select name="forecast_second_month" style="font-family:arial;font-size:10px;width:120px" title="reqmenu">
                      <?php func_select_merchant_volume($show_select_value['forecast_volume_2month']); ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Third Month&nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><select name="forecast_third_month" style="font-family:arial;font-size:10px;width:120px" title="reqmenu">
                      <?php func_select_merchant_volume($show_select_value['forecast_volume_3month']); ?>
                    </select>
                  </td>
                </tr>
                <input type="hidden" name="company" value="company">
                <tr align="center" >
                  <td height="30" colspan="2" valign="middle"><a href="javascript:HelpWindow();"><img border="0" src="images/help_s.gif"></a>&nbsp;&nbsp;<a href="mailto:<?=$_SESSION['gw_emails_sales']?>"><img border="0" src="images/mailme_s.gif"></a>&nbsp;&nbsp;<a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a> &nbsp;
                    <input type="image" id="modifycompany" src="images/continue.gif">
                  </td>
                </tr>
              </table>
            </td>
        </tr>
      </table>
	  
	<?php endTable("Merchant Application","application_awi.php") ?>
	  </td>
  </tr>
</table>
<br>
<?
}
include 'includes/footer.php';
}
?>
