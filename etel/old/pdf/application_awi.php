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
$message = (isset($HTTP_GET_VARS['msg'])?quote_smart($HTTP_GET_VARS['msg']):"");
	if($sessionlogin && $HTTP_POST_VARS['companyname']){
		$companyname = (isset($HTTP_POST_VARS['companyname'])?quote_smart($HTTP_POST_VARS['companyname']):"");
		$username = (isset($HTTP_POST_VARS['username'])?quote_smart($HTTP_POST_VARS['username']):"");
		$phonenumber = (isset($HTTP_POST_VARS['phonenumber'])?quote_smart($HTTP_POST_VARS['phonenumber']):"");
		$address = (isset($HTTP_POST_VARS['address'])?quote_smart($HTTP_POST_VARS['address']):"");
		$city = (isset($HTTP_POST_VARS['city'])?quote_smart($HTTP_POST_VARS['city']):"");
		$country = (isset($HTTP_POST_VARS['country'])?quote_smart($HTTP_POST_VARS['country']):"");
		$state = (isset($HTTP_POST_VARS['state'])?quote_smart($HTTP_POST_VARS['state']):"");
		$ostate = (isset($HTTP_POST_VARS['ostate'])?quote_smart($HTTP_POST_VARS['ostate']):"");
		$zipcode = (isset($HTTP_POST_VARS['zipcode'])?quote_smart($HTTP_POST_VARS['zipcode']):"");
		$faxnumber = (isset($HTTP_POST_VARS['faxnumber'])?quote_smart($HTTP_POST_VARS['faxnumber']):"");
		$url1 = (isset($HTTP_POST_VARS['url1'])?quote_smart($HTTP_POST_VARS['url1']):"");

		$legal_companyname= (isset($HTTP_POST_VARS['legal_companyname'])?quote_smart($HTTP_POST_VARS['legal_companyname']):"");
		$inc_country= (isset($HTTP_POST_VARS['inc_country'])?quote_smart($HTTP_POST_VARS['inc_country']):"");
		$inc_number= (isset($HTTP_POST_VARS['inc_number'])?quote_smart($HTTP_POST_VARS['inc_number']):"");
		$physical_address= (isset($HTTP_POST_VARS['physical_address'])?quote_smart($HTTP_POST_VARS['physical_address']):"");
		$fax_dba= (isset($HTTP_POST_VARS['fax_dba'])?quote_smart($HTTP_POST_VARS['fax_dba']):"");
		$cellular= (isset($HTTP_POST_VARS['cellular'])?quote_smart($HTTP_POST_VARS['cellular']):"");
		$tech_contact_details= (isset($HTTP_POST_VARS['tech_contact_details'])?quote_smart($HTTP_POST_VARS['tech_contact_details']):"");
		$admin_contact_details= (isset($HTTP_POST_VARS['admin_contact_details'])?quote_smart($HTTP_POST_VARS['admin_contact_details']):"");
		$max_ticket_amt= (isset($HTTP_POST_VARS['max_ticket_amt'])?quote_smart($HTTP_POST_VARS['max_ticket_amt']):"");
		$min_ticket_amt= (isset($HTTP_POST_VARS['min_ticket_amt'])?quote_smart($HTTP_POST_VARS['min_ticket_amt']):"");
		$goods_list= (isset($HTTP_POST_VARS['goods_list'])?quote_smart($HTTP_POST_VARS['goods_list']):"");
		$current_anti_fraud_system= (isset($HTTP_POST_VARS['current_anti_fraud_system'])?quote_smart($HTTP_POST_VARS['current_anti_fraud_system']):"");
		$customer_service_program= (isset($HTTP_POST_VARS['customer_service_program'])?quote_smart($HTTP_POST_VARS['customer_service_program']):"");
		$refund_policy= (isset($HTTP_POST_VARS['refund_policy'])?quote_smart($HTTP_POST_VARS['refund_policy']):"");
		$volume_last_month= (isset($HTTP_POST_VARS['volume_last_month'])?quote_smart($HTTP_POST_VARS['volume_last_month']):"");
		$volume_prev_30days= (isset($HTTP_POST_VARS['volume_prev_30days'])?quote_smart($HTTP_POST_VARS['volume_prev_30days']):"");
		$volume_prev_60days= (isset($HTTP_POST_VARS['volume_prev_60days'])?quote_smart($HTTP_POST_VARS['volume_prev_60days']):"");
		$totals= (isset($HTTP_POST_VARS['totals'])?quote_smart($HTTP_POST_VARS['totals']):"");
		$forecast_first_month= (isset($HTTP_POST_VARS['forecast_first_month'])?quote_smart($HTTP_POST_VARS['forecast_first_month']):"");
		$forecast_second_month= (isset($HTTP_POST_VARS['forecast_second_month'])?quote_smart($HTTP_POST_VARS['forecast_second_month']):"");
		$forecast_third_month= (isset($HTTP_POST_VARS['forecast_third_month'])?quote_smart($HTTP_POST_VARS['forecast_third_month']):"");
		$qry_select_user = "select companyname from cs_companydetails where (  companyname='$companyname' ) and userid<>$sessionlogin";
		//print($qry_select_user);
		if(!($show_sql =mysql_query($qry_select_user)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		else if(mysql_num_rows($show_sql) >0) 
		{
			 if(mysql_result($show_sql,0,0) == $companyname) {
				$msgtodisplay="Companyname $companyname already exists.";
				dieLog($msgtodisplay,$msgtodisplay,false);
			}
			exit();
		}
		$cd_max_volume = intval($forecast_first_month); 
		if ($cd_max_volume<25000)$cd_max_volume=25000;
		
		if($max_ticket_amt == "") 
			$max_ticket_amt = 0;
		if($min_ticket_amt == "") 
			$min_ticket_amt = 0;
			
			
		if($message=="")
		{	
		$str_update_query  = "update cs_companydetails set companyname = '$companyname', address = '$address', city = '$city', ";
		$str_update_query .= "country = '$country', state = '$state', ostate = '$ostate', zipcode = '$zipcode', phonenumber = '$phonenumber', ";
		$str_update_query .= "url1 = '$url1', fax_number = '$faxnumber', legal_name = '$legal_companyname', ";

		$str_update_query .= "incorporated_country = '$inc_country', incorporated_number = '$inc_number', fax_dba = '$fax_dba', physical_address = '$physical_address', ";
		$str_update_query .= "cd_max_volume = '$cd_max_volume', cellular = '$cellular', technical_contact_details = '$tech_contact_details', admin_contact_details = '$admin_contact_details', max_ticket_amt = '$max_ticket_amt', min_ticket_amt = '$min_ticket_amt', ";
		$str_update_query .= "goods_list = '$goods_list', volume_last_month = '$volume_last_month', volume_prev_30days = '$volume_prev_30days', volume_prev_60days = '$volume_prev_60days', totals = '$totals', ";
		$str_update_query .= "forecast_volume_1month = '$forecast_first_month', forecast_volume_2month = '$forecast_second_month', forecast_volume_3month = '$forecast_third_month', ";
		$str_update_query .= "current_anti_fraud_system = '$current_anti_fraud_system', customer_service_program = '$customer_service_program', refund_policy = '$refund_policy' ";

		$str_update_query .= "where userid='$sessionlogin'";
		//print($str_update_query);
		mysql_query($str_update_query,$cnn_cs) or dieLog($str_update_query);
			
	}
}

			$str_company_type = $companyInfo['company_type'];

			//$qry_customeremail ="select customerservice_email from cs_companydetails_ext where userid=$sessionlogin";
		
			//$rst_customeremail =mysql_query($qry_customeremail,$cnn_cs) or dieLog($str_update_query);

		//$customeremail=mysql_fetch_array($rst_customeremail);
		//$customerservice_email=$customeremail[0];

?>
<script language="JavaScript" src="scripts/general.js"></script>
<script language="javascript">
function HelpWindow() {
   advtWnd=window.open("aboutcompany.htm","Help","'status=1,scrollbars=1,width=500,height=550,left=0,top=0'");
   advtWnd.focus();
}
</script>
<?php beginTable() ?>

<table border="0" cellpadding="0" width="700" cellspacing="0" height="80%">
  <tr>
    <td width="83%" valign="top" align="center"  height="333"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="disbd">
        <tr>
          <td width="100%" valign="top" align="center"><table  width="100%"  height="40"  valign="bottom">
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
            <input type="hidden" name="username" value="<?=$username?>">
            <table border="0" cellpadding="0"  height="100" width="100%">
              <tr>
                <td align="center" valign="center" height="30" colspan="2" bgcolor="#CCCCCC" class="whitehd">Company Information</td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Type Of Company &nbsp;</font></td>
                <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><select name="company_type" title='reqmenu' style="font-family:arial;font-size:10px;width:160px" >
                    <option value="select" selected>--Choose one --</option>
                    <option value="part" <?= $str_company_type == "part" ? "selected" : ""?>>Limited Partnership</option>
                    <option value="ltd" <?= $str_company_type == "ltd" ? "selected" : ""?>>Limited Liability Company</option>
                    <option value="corp" <?= $str_company_type == "corp" ? "selected" : ""?>>Corporation</option>
                    <option value="sole" <?= $str_company_type == "sole" ? "selected" : ""?>>Sole Proprietor</option>
                    <option value="other" <?= $str_company_type == "other" ? "selected" : ""?>>Other</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; If 'Other', please specify:</font></td>
                <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" maxlength="100" name="other_company_type" style="font-family:arial;font-size:10px;width:150px" value="<?=htmlentities($companyInfo['other_company_type'])?>">
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Customer services telephone number&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" maxlength="20" name="customerservice_phone" style="font-family:arial;font-size:10px;width:150px" value="<?=$companyInfo['customer_service_phone']?>">
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Email&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='email' maxlength="100" name="email" style="font-family:arial;font-size:10px;width:240px" value="<?=$companyInfo['email']?>">
                </td>
              </tr>
              <tr>
                <td align="center" valign="center" height="30" colspan="2"><a href="javascript:HelpWindow();"><img border="0" src="images/help_s.gif"></a>&nbsp;&nbsp;<a href="mailto:<?=$_SESSION['gw_emails_sales']?>"><img border="0" src="images/mailme_s.gif"></a>&nbsp;&nbsp;<a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a> &nbsp;
                  <input name="image" type="image" id="modifycompany" src="images/continue.gif">
                  <br>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table></td>
  </tr>
</table>
            <?php endTable("Merchant Application","application_api.php") ?>
<br>
<?
include 'includes/footer.php';
?>
