<?php
require_once('editPricePoint.php');
die();
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
$headerInclude = "transactions";
include 'includes/header.php';


require_once( 'includes/function.php');
include 'includes/function1.php';
$str_currency="";
$sessionlogin = $companyInfo['userId'];
$str_company_id = $companyInfo['userId'];
//$show_sql =mysql_query("select *  from cs_companydetails where userid=$str_company_id",$cnn_cs);	
//$companyInfo = mysql_fetch_assoc($show_sql);
$updateMsgOne = "This form will create a One Time Payment:";
$updateMsgRecur = "This form will create a Price Point:";
if($_GET['rd_subName']){
	$rd_subName = quote_smart($_GET['rd_subName']);
	
	$sql = "SELECT * FROM `cs_rebillingdetails` WHERE `rd_subName` = '$rd_subName' AND `company_user_id` = " .$str_company_id ;
	$result = mysql_query($sql,$cnn_cs) or dieLog(mysql_error());

	$subAcc = mysql_fetch_assoc($result);
	if($subAcc)
	{
		$subAccountName = $subAcc['rd_subName'];
		$updateMsgOne = "This form will update account '$subAccountName' as a One Time Payment:";
		$updateMsgRecur = "This form will update account '$subAccountName':";
	}
}

$str_current_date = func_get_current_date();
	$i_to_year = substr($str_current_date,0,4);
	$i_to_month = substr($str_current_date,5,2);
	$i_to_day = substr($str_current_date,8,2);
?>
<script type="text/javascript" src="<?=$etel_domain_path?>/fckedit/fckeditor.js"></script>
<script language="javascript">
function validation(){

if(document.FrmRecur.txt_recurAmount.value==""){
	alert("Enter the Initial recur Amount");
	document.FrmRecur.txt_recurAmount.focus();
	return false;
	}
	if(isNaN(document.FrmRecur.txt_recurAmount.value)){
	alert("Enter numeric values only")
	document.FrmRecur.txt_recurAmount.focus()
	return false;
	}
		
	
	
	if(document.FrmRecur.rebill_amt.value==""){
	alert("Enter Recur Amount");
	document.FrmRecur.rebill_amt.focus();
	return false;
	}
	if(isNaN(document.FrmRecur.rebill_amt.value)){
	alert("Enter numeric values only")
	document.FrmRecur.rebill_amt.focus()
	return false;
	}
	
	var recur_mode = "";
for(i=0;i<document.FrmRecur.recurdatemode.length;i++)
{
	if(document.FrmRecur.recurdatemode[i].checked)
	{
		recur_mode = document.FrmRecur.recurdatemode[i].value;
		break;
	}
}
if(recur_mode == "")
	{
		alert("Please select a recurring mode.")
		document.FrmRecur.recurdatemode[0].focus();
		return false;

	}
	else if(recur_mode == "D")
	{
		if(document.FrmRecur.recur_day.value == "")
		{
			alert("Please enter the recurring days.")
			document.FrmRecur.recur_day.focus();
			return false;
		}
		else if(isNaN(document.FrmRecur.recur_day.value))
		{
			alert("Please enter numeric values.")
			document.FrmRecur.recur_day.focus();
			return false;
		}
	}
	
	/*
	if(document.FrmRecur.txt_transAmount.value!=""){
		if(isNaN(document.FrmRecur.txt_transAmount.value))
			{
				alert("Please enter numeric values for Trial Amount.")
				document.FrmRecur.txt_transAmount.focus();
				document.FrmRecur.txt_transAmount.select(); 
				return false;
			}
		}
		*/
	
	return true;
}

function toggleRecur(status)
{
	if(status) $('rd_recur_enabled').checked = status;
	if(status) $('tbl_recur').style.display = 'block';
	else $('tbl_recur').style.display = 'none';
	if(status) $('recur_charge').src = 'between|1.00|<?=$companyInfo['cd_max_transaction']?>';
	else $('recur_charge').src = '';
	if(!status) $('trial_amount').src = 'between|1.00|<?=$companyInfo['cd_max_transaction']?>';
	else $('trial_amount').src = '';
	
}
var oFCKeditor;
function toggleLanding(status)
{
	if(status) 
	{
		$('chk_enable_landing').checked = status;
		$('tbl_landing').style.display = 'block';
		if(oFCKeditor) return;
			
		var sBasePath = '<?=$etel_domain_path?>/fckedit/';
		oFCKeditor = new FCKeditor( 'rd_ibill_landing_html','545','600' ) ;//( instanceName, width, height, toolbarSet, value )
		oFCKeditor.BasePath	= sBasePath ;
		oFCKeditor.ReplaceTextarea() ;
		
	}
	else 
	{
		$('chk_enable_landing').checked = status;
		$('tbl_landing').style.display = 'none';
		$('rd_ibill_landing_html').value = '';
		
	}
	
}



function toggleVerif(status)
{
	if(status) $('secretKey').style.display = 'block';
	else $('secretKey').style.display = 'none';
	if(status) $('cd_secret_key').src = 'minlen|5';
	else $('cd_secret_key').src = '';	
}
function randomString() {
	var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var string_length = 16;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	return randomstring;
}
function genkey()
{
	$('cd_secret_key').value=randomString();
}
function showDemo()
{
	URL = 'checksumDemo.php';
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=1,menubar=0,resizable=0,width=420,height=350,left = 336,top = 332');");
}
</script>
 <table border="0" cellpadding="0" width="100%" cellspacing="0" height="75%" > 
  <tr> 
     <td width="83%" valign="top" align="center"  height="333"><p><font face="verdana" size="1"><a href="recurTransProcessing.php">View/Edit Sub Accounts </a></font>&nbsp; &nbsp;<font size="1" face="verdana">
        </font></p> 
      <p><font size="5">Price Point </font></p> 
      <table width="500" border="0" cellpadding="0" cellspacing="0"> 
         <tr> 
          <td><p><font size="2">This is where you set up your Price Points. </font></p> 
             <ul> 
              <li><font size="2"> Price Points can be either One Time Payments or Recurring Transactions. One Time Payments will only be billed once and will not recur. </font></li> 
              <li><font size="2">Recurring Transactions may include a trial price and trial period and pending the completion of the trial, will charge the customer the Recurring Amount according to the schedule until the customer cancels.</font><font size="2"><br> 
               </font> </li> 
            </ul></td> 
        </tr> 
       </table> 
      <p>&nbsp;</p> 

				  <?php beginTable(); ?>
              <input type="hidden" name="company" value="<?=$sessionlogin?>"> 
              <input name="mode" type="hidden" value="<?=$_GET['mode']?>"> 
              <input name="rd_subName" type="hidden" value="<?=$_GET['rd_subName']?>"> 
				  <table width="550" cellpadding="0"  > 
                      <tr> 
                        <td colspan="2" align="center"><font face="verdana" size="2"> 
                          <?=$updateMsgRecur?> 
                          </font></td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="center" height="30" width="170"><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; </font> </td> 
                        <td><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif">All Amounts must be greater than $1.00
                          <?php if($companyInfo['cd_max_transaction']) echo "and less than $".$companyInfo['cd_max_transaction']; ?> 
                          .</font></td> 
                      </tr> 
                      <tr > 
                        <td align="right" valign="center" height="30"><font face="verdana" size="2">Trial/Product Amount :</font></td> 
                        <td align="left" height="30"><input name="trial_amount" type="text" id="trial_amount" value="<?=$subAcc['rd_initial_amount']?>" size="12" src="between|1.00|99"> 
                          for <span class="tdbdr1"> 
                          <select name="trial_days" class="lineborderselect" style="font-size:10px"> 
						  	<option value="0">No Period</option>
                            <?php func_fill_day($subAcc['rd_trial_days'],91); ?> 
                          </select> 
                          <font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif"> day(s)</font></span> </td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="center" height="10">&nbsp;</td> 
                        <td><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif">Enter the Trial Amount to be billed and the Trial Period duration for which the customer will have access to the site or product until they are rebilled. If your product will have no subscription, or is simply a tangible good then select 'No Period' above. </font></td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="center" height="30"><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif">Enable Recurring Billing </font> </td> 
                        <td><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          <input name="rd_recur_enabled" <?=$subAcc['rd_recur_enabled']?"checked":""?> onClick="toggleRecur(this.checked)" type="checkbox" id="rd_recur_enabled" value="1"> 
                          </font></td> 
                      </tr> 
                      <tr> 
                        <td colspan="2"><table id="tbl_recur" > 
                            <tr> 
                              <td align="right" valign="center" height="30" width="170"><font face="verdana" size="2">Recurring Amount :</font></td> 
                              <td align="left" height="30"><span class="tdbdr1"><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                                <input name ="recur_charge" type="text" id="recur_charge" value="<?=$subAcc['recur_charge']?>" size='12'> 
                                </font></span> </td> 
                            </tr> 
                            <!--modification to include recurring details --> 
                            <tr> 
                              <td align="right" valign="center" height="10">&nbsp;</td> 
                              <td><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif">Enter the Recurring Billing Schedule by which the customer will be billed. You may select this by day or by month. The customer will be charged the Recurring Amount by this schedule. </font></td> 
                            </tr> 
                            <tr> 
                              <td align="right" valign="middle" class="tdbdr1" height="30">&nbsp;</td> 
                              <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp; <span class="tdbdr1"> 
                                <select name="recur_day" class="lineborderselect" id="recur_day" style="font-size:10px"> 
                                  <?php func_fill_day($subAcc['recur_day'],90,false,true); ?> 
                                </select> 
                                </span></font> </td> 
                            </tr> 
                          </table></td> 
                      </tr> 
                      <tr> 
                        <td height="30" align="right" valign="center"><font face="verdana" size="2">Description:<font size="1"><br> 
                          (Not Required- This will appear on the product's payment form)</font></font></td> 
                        <td height="30" align="left" valign="top"><textarea name="txt_description" cols="35" rows="2" id="txt_description"><?=$subAcc['rd_description']?>
</textarea> </td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="center" height="30"><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif">Custom Landing Page </font> </td> 
                        <td><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                          <input name="chk_enable_landing" onClick="toggleLanding(this.checked)" type="checkbox" id="chk_enable_landing" value="1"> 
                          </font></td> 
                      </tr> 
					                        <tr> 
                        <td colspan="2"><table width="550" id="tbl_landing" > 
                            <tr> 
                              <td align="right" valign="center" height="30" width="170"><font face="verdana" size="2">Variables Available: </font></td> 
                              <td align="left" height="30"><select>
							  <option selected="selected"></option>
							  <option>%%CUSTADDR1?</option>
<option>%%CUSTADDR2?</option>
<option>%%CUSTADDR?</option>
<option>%%CUSTCITY?</option>
<option>%%CUSTCOUNTRY?</option>
<option>%%CUSTEMAIL?</option>
<option>%%CUSTFIRSTNAME?</option>
<option>%%CUSTLASTNAME?</option>
<option>%%CUSTPHONE?</option>
<option>%%CUSTSTATE?</option>
<option>%%CUSTZIP?</option>
<option>%%DESC?</option>
<option>%%REBILL?</option>
<option>%%REMOTEIP?</option>
<option>%%STATE?</option>
<option>%%TRANS?</option>
<option>%%CODE?</option>
<option>%%USERNAME?</option>
<option>%%PASSWORD?</option>
<option>%%TRANS?</option>
							  </select></td> 
                            </tr> 
							<tr> <td colspan="2" align="center"><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif">Please select the <strong>Source</strong> Button to paste Source Code.</font></td>
							</tr>
                            <!--modification to include recurring details --> 
                            <tr> 
                              <td colspan="2" align="right" valign="center">
							  
							  		   <textarea name="rd_ibill_landing_html" cols="50" rows="10"  wrap="virtual" id="rd_ibill_landing_html"><?=$subAcc['rd_ibill_landing_html']?></textarea>
		   
		  
							  </td> 
                            </tr> 
                             
                          </table></td> 
                      </tr> 
       </table>
					
				  <?php endTable("Set up a Price Point",'recurTransProcessing.php',NULL,NULL,TRUE); ?>

    </td>
  </tr> 
</table> 
<script language="javascript">
	toggleRecur( <?=$subAcc['rd_recur_enabled']?"true":"false"?>);
	toggleLanding( <?=$subAcc['rd_ibill_landing_html']?"true":"false"?>);
	setupForm($('FrmRecur'));
</script> 
<?php 
include("includes/footer.php");
?> 
