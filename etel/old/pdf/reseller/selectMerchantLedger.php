<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// selectMerchant.php:	The page functions for the company select setup. 

include ("includes/sessioncheck.php");
$headerInclude="reports";
include("includes/header.php");
require_once("../includes/function.php");
include("includes/message.php");

$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";


$qry_details="SELECT companyname,userId FROM `cs_companydetails` WHERE `reseller_id` = '$resellerLogin' AND activeuser=1 ORDER BY `companyname` ASC";	
$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");


$compSQL = "AND `reseller_id` = '$resellerLogin' AND activeuser=1 ";
$compID = $_POST['selectComp'];
if (!$compID) $compID = -1;
if ($compID != -1)  $compSQL .= "AND (t.`userId` = '$compID')";

while($comp = mysql_fetch_assoc($rst_details))
{
	$compList.= "<option value='".$comp['userId']."' ".($comp['userId']==$compID?"selected":"").">".$comp['companyname']."</option>";
}


if($resellerLogin!="")
{
$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");

$merchant_type= isset($HTTP_GET_VARS['merchant_type'])?quote_smart($HTTP_GET_VARS['merchant_type']):"";
if($merchant_type =="") {
	$qrt_select_allcompany="Select distinct userId, companyname from cs_companydetails where reseller_id=$resellerLogin and activeuser=1";
} else if($merchant_type =="A") {
	$qrt_select_allcompany="Select distinct userId, companyname from cs_companydetails where reseller_id=$resellerLogin and activeuser=1 ";
} else {
	$qrt_select_allcompany="Select distinct userId, companyname from cs_companydetails where reseller_id=$resellerLogin and activeuser=1 and transaction_type='$merchant_type'";
}

?>
<script>
function submitMerchant() {
	document.FrmMerchant.action="selectMerchantLedger.php";
	document.FrmMerchant.submit();
}
function Displaycompany(){
	if(document.FrmMerchant.merchant_type.value=="A") {
		document.getElementById('allC').style.display = "";
		document.getElementById('active').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.FrmMerchant.merchant_type.value=="AC") {
		document.getElementById('active').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.FrmMerchant.merchant_type.value=="NC") {
		document.getElementById('nonactive').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('active').style.display = "none";
	}
	document.getElementById('all').selectedIndex = 0;
	document.getElementById('activename').selectedIndex = 0;
	document.getElementById('nonactivename').selectedIndex = 0;
}
function validation() {
	if(document.FrmMerchant.nonactive_merchants.value=="" ) {
		alert("Please select a company");
		return false;
	} else {
		return true;
	}
}
</script>
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="JavaScript" type="text/JavaScript">
function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	var obj_form = document.FrmMerchant;
	if (obj_element.name == "from_date"){
		obj_form.opt_from_day.selectedIndex = dateSelected-1 ;
		obj_form.opt_from_month.selectedIndex = monthSelected ;
		obj_form.opt_from_year.selectedIndex = func_returnselectedindex(yearSelected) ;
	}
	if (obj_element.name == "from_to"){
		obj_form.opt_to_day.selectedIndex = dateSelected-1 ;
		obj_form.opt_to_month.selectedIndex = monthSelected ;
		obj_form.opt_to_year.selectedIndex = func_returnselectedindex(yearSelected);
	}
}
function func_returnselectedindex(par_selected)
{
	var dt_new =  new Date();
	var str_year = dt_new.getFullYear()
	for(i=2003,j=0;i<str_year+10;i++,j++)
	{
		if (i==par_selected)
		{
			return j;
		}
	}
}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" >
  <tr><td width="83%" valign="top" align="center"  >
<br>	
<form action="reportBottomSummary.php" method="GET" name="FrmMerchant" >
  <table border="0" cellpadding="0" cellspacing="0" width="50%" >
    <tr>
      <td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
      <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Ledgers</span></td>
      <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
      <td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
      <td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
    </tr>
    <tr>
      <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5"><input type="hidden" name="period" value="<?=$period?>">
          <br>
          <table align="center" cellpadding="0" cellspacing="0" width="100%" height="100%">
            <tr>
              <td height="30" valign="middle" align="right" width="40%"><font face="verdana" size="1">
                <?=$periodstring?>
              </font></td>
              <td align="left" width="60%"  height="30" >&nbsp;
                  <!--	 <input type="text" name="txtDate" style="font-family:verdana;font-size:10px;WIDTH: 140px" value=<?=$dateval2?>></input>&nbsp;&nbsp;<input type="button" value="..." onclick="init()" style="font-family:verdana;font-size:10px;"> -->
                  <select name="opt_from_month" style="font-size:10px">
                    <?php func_fill_month($i_from_month); ?>
                  </select>
                  <select name="opt_from_day" class="lineborderselect" style="font-size:10px">
                    <?php func_fill_day($i_from_day); ?>
                  </select>
                  <select name="opt_from_year" style="font-size:10px">
                    <?php func_fill_year($i_from_year); ?>
                  </select>
              </td>
            </tr>
            <tr>
              <td height="30" valign="middle" align="right" width="40%"><font face="verdana" size="1">
                <?=$endperiodstring?>
              </font></td>
              <td align="left" width="60%"  height="30"  >&nbsp;
                  <!--	<input type="text" name="txtDate1" style="font-family:verdana;font-size:10px;WIDTH: 140px" value=<?=$dateval2?>></input>&nbsp;&nbsp;<input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init1()">-->
                  <select name="opt_to_month" class="lineborderselect" style="font-size:10px">
                    <?php func_fill_month($i_to_month); ?>
                  </select>
                  <select name="opt_to_day" class="lineborderselect" style="font-size:10px">
                    <?php func_fill_day($i_to_day); ?>
                  </select>
                  <select name="opt_to_year" class="lineborderselect" style="font-size:10px">
                    <?php func_fill_year($i_to_year); ?>
                  </select>
              </td>
            </tr>
            <tr>
              <td height="30" valign="middle" align="right" width="40%">Select Company <font face="verdana" size="1">&nbsp;
                
              </font></td>
              <td align="left" width="60%"  height="30"  >&nbsp;
                <select name="selectComp" class="lineborderselect" style="font-size:10px" id="selectComp">
                  <option value="-1">All Companies</option>
                  <?=$compList?>
                </select>                  
                <!--	<input type="text" name="txtDate1" style="font-family:verdana;font-size:10px;WIDTH: 140px" value=<?=$dateval2?>></input>&nbsp;&nbsp;<input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init1()">-->
              </td>
            </tr>
            <input type="hidden" value="" name="id" >
            <input type="hidden" value="" name="cnumber">
            <tr>
              <td  height="50"  valign="middle" align="center" colspan='2'><?php
		  		if ( trim($_SESSION["sessionactivity_type"]) != "Test Mode" ) {
	  ?>
                  <input type="image" id="reportview" src="../images/view.jpg">
                  <?php
	  	} else {
				print("<font size=\"1\" face=\"Verdana\" color=\"Red\">Only active companies can view ledgers.</font>");
		}
	   ?>
              </td>
            </tr>
        </table></td>
    </tr>
    <tr>
      <td width="1%"><img src="images/menubtmleft.gif"></td>
      <td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
      <td width="1%" ><img src="images/menubtmright.gif"></td>
    </tr>
  </table>
</form>
    </td>
     </tr>
</table>
<?php
}
include("includes/footer.php");
?>