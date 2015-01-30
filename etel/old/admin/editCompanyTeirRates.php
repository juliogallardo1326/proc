<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// editCompanyProfile3.php:	This admin page functions for editing the company details. 

$allowBank=true;
include("includes/sessioncheck.php");

$headerInclude = "companies";
include("includes/header.php");


$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$str_update =isset($HTTP_POST_VARS["update"])?$HTTP_POST_VARS["update"]:"";
$iCheckBankId ="";
$iCreditBankId="";$val="";
$numrows=0;$modified=0;
	
if($sessionAdmin!="")
{
	$userid = (isset($_POST['userid'])?quote_smart($_POST['userid']):"");
	if($userid && !$repost_warning && $adminInfo['li_level'] == 'full')
	{
		//print_r($_POST);
		if($_POST['Submit'] == "Add New Rate") 
		{
			$sql = "INSERT INTO `cs_company_rates` (`cr_userId` ) VALUES ( '$userid' );";
			$result = mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
		}
		
		// Delete
		if (is_array($_POST['cr_delete']))
			foreach($_POST['cr_delete'] as $key => $cr_delete)
			{			
				$sql = "DELETE FROM `cs_company_rates` WHERE `cr_ID` = '$cr_delete' LIMIT 1;";
				$result = mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
			}
			
		// Update
		if (is_array($_POST['cr_ID']))
			foreach($_POST['cr_ID'] as $key => $cr_ID)
			{
				if($_POST['cr_teir_top'][$key]=="INF") $_POST['cr_teir_top'][$key] = 1000000000;
				if($_POST['cr_teir_bottom'][$key]=="INF") $_POST['cr_teir_bottom'][$key] = 1000000000;
				
				$sql="UPDATE `cs_company_rates` SET 
		`cr_feetype` = '".$_POST['cr_feetype'][$key]."',
		`cr_transtype` = '".$_POST['cr_transtype'][$key]."',
		`cr_merchant` = '".$_POST['cr_merchant'][$key]."',
		`cr_reseller` = '".$_POST['cr_reseller'][$key]."',
		`cr_total` = '".$_POST['cr_total'][$key]."',
		`cr_teir_top` = '".$_POST['cr_teir_top'][$key]."',
		`cr_teir_bottom` = '".$_POST['cr_teir_bottom'][$key]."' WHERE `cr_ID` ='$cr_ID' AND `cr_userId` = $userid ";
				
				$result = mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
			}


	}
	
	if (!$userid) $userid = (isset($_REQUEST['company_id'])?quote_smart($_REQUEST['company_id']):"");

	$date=date("Y-m-d H:i:s");

	$sql = "select * from cs_companydetails where userId=$userid";
	$result=mysql_query($sql) or dieLog(mysql_error());
	$companyInfo = mysql_fetch_assoc($result);

	$company_id = $userid;
	if ($company_id == "") {
		$company_id = (isset($HTTP_POST_VARS['userid'])?quote_smart($HTTP_POST_VARS['userid']):"");
	}
	$companyname = isset($HTTP_GET_VARS['companyname'])?$HTTP_GET_VARS['companyname']:"";
	$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"";
	$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"";

}
?>
<script language="javascript" src="../scripts/general.js"></script>
<script language="javascript">

function func_ischanged()
{ // rad_trans_activity
//varold txtShopeId;txtBankPassword
updatePayDelay();
addRatesFees();
return true;

}

function addRatesFees(cr_id) 
{
	document.getElementById("cr_merchant_"+cr_id).value = Math.round(100*(parseFloat(document.getElementById("cr_reseller_"+cr_id).value)+parseFloat(document.getElementById("cr_total_"+cr_id).value)))*.01;
	document.getElementById("cr_merchant_"+cr_id).onchange();
}

function formatThis(obj,type) 
{
	obj.value = parseFloat(obj.value);
	if (obj.value<0 || obj.value>100) obj.value = 0;
	obj.value = Math.round(100*obj.value)*.01;
	//if(type == "percent") obj.value += "%";
	//else obj.value = "$"+obj.value;
}

function funcOpen3VT(iCompanyId) {
	window.open("vtusers.php?id="+iCompanyId,null,"height=600,width=500,status=yes,toolbar=no,menubar=no,location=no,scrollbar=yes");
}
function funcOpenTSR(iCompanyId) {
	window.open("tsruserlist.php?id="+iCompanyId,null,"height=600,width=500,status=yes,toolbar=no,menubar=no,location=no,scrollbar=yes");
}
function funcOpenEcom(iCompanyId) {
	window.open("ecomlist.php?id="+iCompanyId,null,"height=600,width=600,status=yes,toolbar=no,menubar=no,location=no,scrollbar=yes");
}
</script>

<table border="0" align="center" cellpadding="0" width="98%" cellspacing="0" height="80%">
  <tr>
    <td width="100%" valign="top" align="center"  >&nbsp;
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
          <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">View / Edit&nbsp; Rates And Fees </span></td>
          <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
          <td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
          <td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
        </tr>
        <tr>
          <td class="lgnbd" colspan="5"><form action="" name="Frmrates" method="post">
              <table style="margin-top:10" align="center">
                <tr>
                  <td align="center"><a href="editCompanyProfile1.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/merchantdetails_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a> <a href="editCompanyProfile2.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/bankprocessing_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a> <IMG SRC="<?=$tmpl_dir?>/images/ratesandfees_tab1.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT="">
                    <a href="editCompanyProfile5.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/uploadown_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
                    <!--<a href="completeAccounting.php?company_id=<?= $company_id?>&script_display=<?= $script_display?>"><IMG SRC="<?=$tmpl_dir?>/images/completeaccounting_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>-->
                  </td>
                </tr>
              </table>
              <div align="center" style="font-size: 10px">
                <input type="hidden" name="userid" value="<?=$companyInfo['userId']?>">
                </input>
                -
                <?= $companyInfo['companyname'] ?>
                - </div>
              <?php

$sql = "SHOW COLUMNS FROM cs_company_rates like 'cr_transtype'";
$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
$cr_transtype = mysql_fetch_assoc($result);
eval('$enum_transtype = '.str_replace("enum(","array(",$cr_transtype['Type']).';');
foreach($enum_transtype as $enum)
	$transtype_list .= "<option value='$enum' >".ucfirst($enum)."</option>\n";
	
$sql = "SHOW COLUMNS FROM cs_company_rates like 'cr_feetype'";
$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
$cr_feetype = mysql_fetch_assoc($result);
eval('$enum_feetype = '.str_replace("enum(","array(",$cr_feetype['Type']).';');
foreach($enum_feetype as $enum)
	$feetype_list .= "<option value='$enum' >".ucfirst($enum)."</option>\n";
	
	

$sql="SELECT *
FROM `cs_company_rates`
WHERE `cr_userId` = $company_id
ORDER BY `cr_transtype` ASC, `cr_feetype` ASC, `cr_teir_bottom` ASC";

$result = mysql_query($sql) or dieLog(mysql_error(). " ~ $sql");
$cur_transtype = "";

?>
              <table width="98%" cellpadding="0" cellspacing="0" align="center" border="0">
                <tr>
                  <td align="center" valign="top"><table width="100%" cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="center">

<?php
while($cs_company_rates = mysql_fetch_assoc($result))
{

//if($cs_company_rates['cr_teir_bottom']>=1000000000) $cs_company_rates['cr_teir_bottom']="INF";
if($cs_company_rates['cr_teir_top']>=1000000000) $cs_company_rates['cr_teir_top']="INF";

$format_percent = "";
$format_dollar = "";
if($cs_company_rates['cr_feetype']=='discount' || $cs_company_rates['cr_feetype']=='reserve' || strpos($cs_company_rates['cr_feetype'],'refund') !== false) $format_percent = "%";
else $format_dollar = "$";

$cs_company_rates['cr_merchant'] = formatMoney($cs_company_rates['cr_merchant']);
$cs_company_rates['cr_reseller'] = formatMoney($cs_company_rates['cr_reseller']);
$cs_company_rates['cr_total'] = formatMoney($cs_company_rates['cr_total']);


if($cur_transtype != $cs_company_rates['cr_transtype'])
{
$cur_transtype = $cs_company_rates['cr_transtype'];
?>
                      <tr><td class='cl1' colspan="8" align="center"><BR>- <strong><?=ucfirst($cur_transtype)?></strong> -</td></tr>
                      <tr>
                        <td height="30" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Teir Rates for <?=ucfirst($cur_transtype)?></strong>&nbsp;</font></td>
                        <td colspan="2" align="center" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Monthly Volume</font></strong></td>
                        <td align="center" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Merchant Rate </font></strong></td>
                        <td align="center" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Reseller Rate </font></strong></td>
                        <td align="center" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><font face="verdana" size="1"><strong>
                          <?=$_SESSION['gw_title']?>
                        </strong></font> Rate </font></strong></td>
                        <td align="center" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Transaction Type </font></strong></td>
                        <td align="center" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Delete </font></strong></td>
                      </tr>
                      <?php 
}

?>
                      <tr>
                        <td height="30" class='cl1'><strong>
                          <input name="cr_ID[]" type="hidden" id="cr_ID[]" value="<?=$cs_company_rates['cr_ID']?>">
                          <select name="cr_feetype[]" class="normaltext" id="cr_feetype_<?=$cs_company_rates['cr_ID']?>">
                            <?=$feetype_list?>
                          </select>
						  <script language="javascript"> document.getElementById('cr_feetype_<?=$cs_company_rates['cr_ID']?>').value= '<?=$cs_company_rates['cr_feetype']?>' </script>
                          </strong></td>
                        <td align="center" class='cl1'><strong> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Max<br>
                          <br>
                          Min</strong></font> </td>
                        <td align="left" class='cl1'><strong>
                          <strong>
                          <input name="cr_teir_top[]" type="text" class="normaltext" id="cr_teir_top[]" value="<?=$cs_company_rates['cr_teir_top']?>" size="10">
                          </strong>
                          <br>
                          <input name="cr_teir_bottom[]" type="text" class="normaltext" id="cr_teir_bottom[]" value="<?=$cs_company_rates['cr_teir_bottom']?>" size="10">
                        </strong></td>
                        <td align="center" class='cl1'><strong>
                          <font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?=$format_dollar?><input name="cr_merchant[]" type="text" class="normaltext" onChange="formatThis(this)" id="cr_merchant_<?=$cs_company_rates['cr_ID']?>" value="<?=$cs_company_rates['cr_merchant']?>" size="5"><?=$format_percent?>
                          </font></strong></td>
                        <td align="center" class='cl1'><strong>
                          <font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?=$format_dollar?><input name="cr_reseller[]" type="text" class="normaltext" onChange="formatThis(this)" id="cr_reseller_<?=$cs_company_rates['cr_ID']?>" onKeyUp="addRatesFees(<?=$cs_company_rates['cr_ID']?>)" value="<?=$cs_company_rates['cr_reseller']?>" size="5"><?=$format_percent?>
                          </font></strong></td>
                        <td align="center" class='cl1'><strong>
                          <font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?=$format_dollar?><input name="cr_total[]" type="text" class="normaltext" onChange="formatThis(this)" id="cr_total_<?=$cs_company_rates['cr_ID']?>" onKeyUp="addRatesFees(<?=$cs_company_rates['cr_ID']?>)" value="<?=$cs_company_rates['cr_total']?>" size="5"><?=$format_percent?>
                          </font></strong></td>
                        <td align="center" class='cl1'><strong>
                          <select name="cr_transtype[]" class="normaltext" id="cr_transtype_<?=$cs_company_rates['cr_ID']?>">
                            <?=$transtype_list?>
                          </select>
						  <script language="javascript"> document.getElementById('cr_transtype_<?=$cs_company_rates['cr_ID']?>').value= '<?=$cs_company_rates['cr_transtype']?>' </script>
                          </strong></td>
                        <td align="center" class='cl1'><strong>
                          <input name="cr_delete[]" type="checkbox" id="cr_delete[]" value="<?=$cs_company_rates['cr_ID']?>">
                          </strong></td>
                      </tr>
                      <? } ?>
                  </table></td>
                </tr>
              </table>
              <center>
                <table align="center">
                  <tr>
                    <td align="center" valign="center" height="30" colspan="2" >
                      <input type="submit" name="Submit" value="Update Rates">
                      <input type="submit" name="Submit" value="Add New Rate">
                      <br>
                      <a href="editCompanyProfile2.php?company_id=<?= $company_id?>"><img  SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a></td>
                  </tr>
                </table>
              </center>

            </form></td>
        </tr>
        <tr>
          <td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
          <td colspan="3" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
          <td width="3%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
        </tr>
      </table>
      <br>
    </td>
  </tr>
</table>
<?php
include("includes/footer.php");
?>
