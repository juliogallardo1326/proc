<?php 
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyleft (C) etelegate.com 2003-2004, All lefts Reserved.       //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
// companyEdit.php:	The  page used to modify the company profile. 
include 'includes/sessioncheck.php';
$headerInclude="startHere";
require_once("includes/header.php");
$invalidlogin = "";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

$email = (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");
	
if($sessionlogin!="" && $email){

	$username = (isset($HTTP_POST_VARS['username'])?quote_smart($HTTP_POST_VARS['username']):"");
	$company_type = (isset($HTTP_POST_VARS['company_type'])?quote_smart($HTTP_POST_VARS['company_type']):"");
	$other_company_type = (isset($HTTP_POST_VARS['other_company_type'])?quote_smart($HTTP_POST_VARS['other_company_type']):"");
	$customerservice_phone = (isset($HTTP_POST_VARS['customerservice_phone'])?quote_smart($HTTP_POST_VARS['customerservice_phone']):"");
	$customerservice_email = (isset($HTTP_POST_VARS['customerservice_email'])?quote_smart($HTTP_POST_VARS['customerservice_email']):"");

	$url1= (isset($HTTP_POST_VARS['url1'])?quote_smart($HTTP_POST_VARS['url1']):"");
	$url2= (isset($HTTP_POST_VARS['url2'])?quote_smart($HTTP_POST_VARS['url2']):"");
	$url3= (isset($HTTP_POST_VARS['url3'])?quote_smart($HTTP_POST_VARS['url3']):"");
	$url4= (isset($HTTP_POST_VARS['url4'])?quote_smart($HTTP_POST_VARS['url4']):"");
	$url5= (isset($HTTP_POST_VARS['url5'])?quote_smart($HTTP_POST_VARS['url5']):"");
$qry_select_user = "select email from cs_companydetails where (  email='$email' ) and userid<>$sessionlogin";
if ($etel_debug_mode) $qry_select_user = "select email from cs_companydetails where (  email='345513465' )";
		//print($qry_select_user);
		if(!($show_sql =mysql_query($qry_select_user)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		else if(mysql_num_rows($show_sql) >0) 
		{
			 if(mysql_result($show_sql,0,0) == $email) {
				$msgtodisplay="Email id $email already exists.";
				dieLog("","$msgtodisplay",false);
			}
			exit();
		}
	$sql_select_qry ="select *  from cs_companydetails where userid=$sessionlogin";
	$qry_currency="select * from cs_companydetails_ext where userId=$sessionlogin";
	if(!($run_select_qry =mysql_query($sql_select_qry,$cnn_cs))) {
		echo mysql_errno().": ".mysql_error()."<BR>";
		echo "Cannot execute query.";
		exit();
	}
	if(!($rst_currency =mysql_query($qry_currency,$cnn_cs))) {
		echo mysql_errno().": ".mysql_error()."<BR>";
		echo "Cannot execute query.";
		exit();
	}
	else{
		$rst_currencydetails=mysql_fetch_array($rst_currency);
		 $mastercurrency=$rst_currencydetails[1];
		 $visacurrency=$rst_currencydetails[2];
	}	
	if($show_select_value = mysql_fetch_array($run_select_qry)){ 
		$str_update_query  = "update cs_companydetails set company_type = '$company_type', other_company_type = '$other_company_type', customer_service_phone = '$customerservice_phone', ";
		$str_update_query .= "email = '$email' where userid=$sessionlogin";

		if (!mysql_query($str_update_query,$cnn_cs)) {
			echo mysql_errno().": ".mysql_error()."<BR>";
			echo "Cannot execute update query.";
			exit();
		}
		if($customerservice_email!="")
		{
			func_company_ext_entry($sessionlogin,$customerservice_email,$cnn_cs);	
		}
	
	}
}
include 'includes/header.php';
		
?>
<script language="JavaScript" src="scripts/general.js"></script>
<script language="javascript">
function HelpWindow() {
   advtWnd=window.open("aboutprocess.htm","Help","'status=1,scrollbars=1,width=500,height=450,left=0,top=0'");
   advtWnd.focus();
}
</script>
<?php beginTable() ?>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
       <td width="83%" valign="top" align="center"  height="333"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="disbd">

            <tr>
              <td width="100%" valign="top" align="center">

			<table  width="100%"  height="40"  valign="bottom">			
			  <tr>
                <td width="100%" valign="middle" align="left" height="40" bgcolor="#DDDDDD"><img border="0" src="<?=$tmpl_dir?>images/application.gif"><img border="0" src="<?=$tmpl_dir?>images/aboutyou.gif"><img border="0" src="<?=$tmpl_dir?>images/yourcompany.gif"><img border="0" src="<?=$tmpl_dir?>images/yourprocess1.gif"><img border="0" src="<?=$tmpl_dir?>images/yourbank.gif"><img border="0" src="<?=$tmpl_dir?>images/finishingline.gif"></td>
            </tr> 
			</table> 
			<input type="hidden" name="username" value="<?=$companyInfo['password']?>"></input>
            <table border="0" cellpadding="0"  height="100" width="100%">
                      <tr>
                        <td align="center" valign="center" height="30" colspan="2" bgcolor="#CCCCCC" class="whitehd">Processing Information</td>
                      </tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                          &nbsp; Projected monthly sales volume $&nbsp;&nbsp;</font></td>
                        
                  <td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC"><select name="volume" title="reqmenu" onChange="document.getElementById('cd_previous_processor').src=(!(this.value<=10000)?'req':'');document.getElementById('prepro').disabled=(!(this.value<=10000));" style="font-family:arial;font-size:10px;width:120px">
                      <?php						func_select_merchant_volume($companyInfo['volumenumber']); ?>
                    </select> </td>
                      </tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                          &nbsp; Average ticket&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' maxlength="10" name="avgticket" style="font-family:arial;font-size:10px;width:80px" value="<?=$companyInfo['avgticket']?>"> 
                  </td>
                      </tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                          &nbsp; Charge back %&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' maxlength="10" name="chargeper" style="font-family:arial;font-size:10px;width:80px" value="<?=$companyInfo['chargebackper']?>"> 
                  	</td>
                      </tr>
					 <tr>
                        <td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp;
                          Merchant 
                          Type &nbsp;&nbsp;</font></td>
						  <td align="left" height="30"  width="50%" valign="middle" bgcolor="#F8FAFC"><select name="rad_order_type" id="rad_order_type" title="reqmenu" style="font-family:arial;font-size:10px;width:100px">
							<option value="select">Select</option>
							<option value="ecom">General Ecommerce</option>
							<option value="trvl">Travel</option>
							<option value="phrm">Pharmacy</option>
							<option value="game">Gaming</option>
							<option value="adlt">Adult</option>
							<option value="tele">Telemarketing</option>
							<!--option value="crds">Card swipe</option-->
						  </select></td>
						<script language="javascript">
							 document.getElementById('rad_order_type').value='<?=$companyInfo['transaction_type']?>';	
						</script>
                      </tr>	
					  <tr>
                        <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                          &nbsp; Previous Processing &nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><select onChange="document.getElementById('cd_previous_processor').src=(this.value=='Yes'?'req':'');" name="prepro" id="prepro" style="font-family:verdana;font-size:10px;width:50px">
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select> </td>
						 <script language="javascript">
							 document.getElementById('prepro').value='<?=$companyInfo['preprocess']?>';
						</script> 
                      </tr>	
					  
					<tr>
					<td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
					  &nbsp; Who was your Previous Processor? &nbsp;&nbsp;</font></td>
					<td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input name="cd_previous_processor" type="text" id="cd_previous_processor" style="font-family:arial;font-size:10px;width:150px" value="<?=htmlentities($companyInfo['cd_previous_processor'])?>" src='<?=$companyInfo['volume_last_month']>=10000?"req":""?>' maxlength="100"> 
                  </td>
                </tr>
					<tr>
					<td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
					  &nbsp; What was your Previous Processing Discount Rate? &nbsp;&nbsp;</font></td>
					<td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input name="cd_previous_discount" type="text" id="cd_previous_discount" style="font-family:arial;font-size:10px;width:150px" value="<?=htmlentities($companyInfo['cd_previous_discount'])?>" src='<?=$companyInfo['volume_last_month']>=10000?"req":""?>' maxlength="100"> 
                  </td>
                </tr>
					<tr>
					<td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
					  &nbsp; What was your Previous Transaction Fee? &nbsp;&nbsp;</font></td>
					<td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input name="cd_previous_transaction_fee" type="text" id="cd_previous_transaction_fee" style="font-family:arial;font-size:10px;width:150px" value="<?=htmlentities($companyInfo['cd_previous_transaction_fee'])?>" src='<?=$companyInfo['volume_last_month']>=10000?"req":""?>' maxlength="100"> 
                  </td>
                </tr>
					<tr>
					<td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
					  &nbsp; If Previous Processing, why did you leave? or why do you need new or additional processing? &nbsp;&nbsp;</font></td>
					<td align="left" height="30" width="50%" bgcolor="#F8FAFC"><textarea name="cd_processing_reason" id="cd_processing_reason" style="font-family:arial;font-size:10px;width:150px"><?=htmlentities($companyInfo['cd_processing_reason'])?></textarea> 
                  </td>
                </tr>
					  <tr>
                        <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                          &nbsp; Recurring billing &nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><select name="rebill" id="rebill" style="font-family:verdana;font-size:10px;width:50px">
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                            </select> </td>
 						<script language="javascript">
							 document.getElementById('rebill').value='<?=$companyInfo['recurbilling']?>';
						</script>                       
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                          &nbsp; Currently Processing &nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><select name="currpro" id="currpro" style="font-family:verdana;font-size:10px;width:50px">
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                        </select> </td>
 						<script language="javascript">
							 document.getElementById('currpro').value='<?=$companyInfo['currprocessing']?>';
						</script>                       
						</tr>
						<tr>
							<td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
							  &nbsp; Processing Currency(Mastercard)&nbsp;&nbsp;</font></td>
							<td align="left" height="30" width="50%" bgcolor="#F8FAFC">
								<select name="currencymaster" id="currencymaster" style="font-family:verdana;font-size:10px;width:125px">
								  <option value="USD" selected>US Dollar</option>
								  <option value="EUR">Euro</option>
								  <option value="GBP">UK Pound</option>
								  <option value="CAD">Canadian Dollar</option>
								  <option value="AUD">Australian Dollar</option>
								</select> 
							</td>
						<script language="javascript">
							 document.getElementById('currencymaster').value='<?=$mastercurrency?>';
						</script>    
						</tr>
						<tr>
							<td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
							  &nbsp; Processing Currency(Visa)&nbsp;&nbsp;</font></td>
							<td align="left" height="30" width="50%" bgcolor="#F8FAFC">
								<select name="currencyvisa" id="currencyvisa" style="font-family:verdana;font-size:10px;width:125px">
								  <option value="USD" selected>US Dollar</option>
								  <option value="EUR">Euro</option>
								  <option value="GBP">UK Pound</option>
								  <option value="CAD">Canadian Dollar</option>
								  <option value="AUD">Australian Dollar</option>
								</select> 
							</td>
						<script language="javascript">
							 document.getElementById('currencyvisa').value='<?=$visacurrency?>';
						</script>    
						</tr>
                        <input type="hidden" name="company" value="company">
                      <tr>
                        
                  <td align="center" valign="center" height="30" colspan="2"><a href="javascript:HelpWindow();"><img border="0" src="images/help_s.gif"></a>&nbsp;&nbsp;<a href="mailto:<?=$_SESSION['gw_emails_sales']?>"><img border="0" src="images/mailme_s.gif"></a>&nbsp;&nbsp;<a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a> 
                    &nbsp;<input name="image" type="image" id="modifycompany" src="images/continue.gif">
					<br>
                        </td>
                      </tr>
                    </table>
           		</form>
              </td>
            </tr>
          </table>
      
    </td>
     </tr>
</table>
  <?php endTable("Merchant Application","application_submit.php?showheader=".$_REQUEST['showheader']);
  
include 'includes/footer.php';


function func_company_ext_entry($userid,$customerservice_email,$cnn_cs){
	$qry_exist="select * from cs_companydetails_ext where userid='$userid'";
	if(!$rst_exist=mysql_query($qry_exist,$cnn_cs))
	{
		echo "Cannot execute Query";
	}
	else{
		$num=mysql_num_rows($rst_exist);
		if($num==0)
		{
			$qry_companyext="insert into cs_companydetails_ext (userId, customerservice_email ) values('$userid','$customerservice_email')";	
		}
		if(!$rst_update=mysql_query($qry_companyext,$cnn_cs))
		{
			echo "Cannot execute query";
		}
	}

}

?>