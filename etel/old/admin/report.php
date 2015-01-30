<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
//report.php:	The admin page functions for selecting the type of report view  for the company. 
$allowBank=true;
include("includes/sessioncheck.php");

$headerInclude="transactions";
include("includes/header.php");
include("../includes/companySubView.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$ptype = isset($HTTP_GET_VARS['ptype'])?quote_smart($HTTP_GET_VARS['ptype']):"";

$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2);

$i_from_year = (isset($_REQUEST["opt_from_year"])?quote_smart($_REQUEST["opt_from_year"]):$i_from_year);
$i_from_month = (isset($_REQUEST["opt_from_month"])?quote_smart($_REQUEST["opt_from_month"]):$i_from_month);
$i_from_day = (isset($_REQUEST["opt_from_day"])?quote_smart($_REQUEST["opt_from_day"]):$i_from_day);
$i_to_year = (isset($_REQUEST["opt_to_year"])?quote_smart($_REQUEST["opt_to_year"]):$i_to_year);
$i_to_month = (isset($_REQUEST["opt_to_month"])?quote_smart($_REQUEST["opt_to_month"]):$i_to_month);
$i_to_day = (isset($_REQUEST["opt_to_day"])?quote_smart($_REQUEST["opt_to_day"]):$i_to_day);

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2); 

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;
	
	
	if(!isset($period))
	{
	  $period="p";      
	}
    if($period=="p")
	{
	   $periodstring="Start Date";
	   $endperiodstring = "End Date";
       
	}
$Transtype = isset($_REQUEST['trans_type'])?quote_smart($_REQUEST['trans_type']):"";
$companymode = isset($_REQUEST['companymode'])?$_REQUEST['companymode']:"A";
$companytrans_type = isset($_REQUEST['companytrans_type'])?quote_smart($_REQUEST['companytrans_type']):"A";
$tele_nontele_type = isset($_REQUEST['tele_nontele_type'])?quote_smart($_REQUEST['tele_nontele_type']):"E";
$bank_id = isset($_REQUEST['bank_id'])?quote_smart($_REQUEST['bank_id']):"A";
$qrt_select_companies ="select distinct userId,companyname from cs_companydetails where 1 and transaction_type<>'tele' order by companyname";

if ($Transtype == "Submit")  {
	if($companymode =="AC") {
		$qrt_select_subqry = " activeuser=1";
	} else if($companymode =="NC") {
		$qrt_select_subqry = " activeuser=0";	
	} else if($companymode =="RE") {
		$qrt_select_subqry = " reseller_id <> ''";	
	} else if($companymode =="ET") {
		$qrt_select_subqry = " reseller_id is null";	
	} else {
		$qrt_select_subqry = "";	
	}

$qrt_select_tel_nontele_qry = "";
	if($companytrans_type =="A") {
		$qrt_select_merchant_qry = "";
		if($tele_nontele_type =="A") {
			$qrt_select_tel_nontele_qry = "";
		} else if($tele_nontele_type =="T") {
			if($qrt_select_subqry =="") {
				$qrt_select_tel_nontele_qry = " transaction_type='tele'";
			} else {
				$qrt_select_tel_nontele_qry = " and transaction_type='tele'";
			}
		} else if($tele_nontele_type =="E") {
			if($qrt_select_subqry =="") {
				$qrt_select_tel_nontele_qry = " transaction_type<>'tele'";
			} else {
				$qrt_select_tel_nontele_qry = " and transaction_type<>'tele'";
			}
		}
	} else {
		if($qrt_select_subqry =="" && $qrt_select_tel_nontele_qry == "") {
			$qrt_select_merchant_qry = " transaction_type='$companytrans_type'";
		} else {
			$qrt_select_merchant_qry = " and transaction_type='$companytrans_type'";
		}
	}

	$str_total_query = "";
	if ($qrt_select_subqry != "" || $qrt_select_tel_nontele_qry != "" || $qrt_select_merchant_qry != "") {
		$str_total_query = "where 1 and $qrt_select_subqry $qrt_select_tel_nontele_qry $qrt_select_merchant_qry";
	} else {
		$str_total_query = "where 1 ";
	}
$qrt_select_companies="select distinct userId,companyname from cs_companydetails $str_total_query order by companyname";
//print($qrt_select_companies);
}	
?>
<!--<script language="javascript" src="../scripts/calendar1.js"></script>
<script language="javascript" src="../scripts/general.js"></script>
-->
<script language="javascript" src="../scripts/general.js"></script>
<script language="javascript">

function datefn(){   
checkval=true          
datestring=document.forms[0].txtDate1.value  	
	 if(!checkDateString(datestring,'mdy','/')){
		 checkval=false
		 fname='txtDate1'
	   }
	 datestring=document.forms[0].txtDate.value
	 if(!checkDateString(datestring,'mdy','/')){
		 checkval=false
		 fname='txtDate'
	   }
	  if(!checkval){
		 alert("Please enter correct date") 
		 eval("document.forms[0]." + fname + ".focus()");
		 return false
	  }
	  else{
		return true
	  }
  
}

 function show()
  {
// 	if(datefn())
//	{
		objForm = document.ledger;
		var strCompany;
		strCompany = "";
		for($i=0;$i<objForm.companyname.length;$i++)
		{
			if(objForm.companyname.options[$i].selected == true)
			{
				if(strCompany =="" ) {
					strCompany = objForm.companyname.options[$i].value;
				}else{
					strCompany = strCompany +","+objForm.companyname.options[$i].value;
				}
			}	
		}
		objForm.hid_companies.value = strCompany;
		document.ledger.submit();
//	}
//	else
//	{
//		return false;
//	}
  }

function showType(){
	if(document.ledger.crorcq.options[document.ledger.crorcq.selectedIndex].value=="C") {
		document.ledger.type[0] = new Option("All","A");
		document.ledger.type[1] = new Option("Savings Account","S");
		document.ledger.type[2] = new Option("Checking Account","C");
		document.ledger.type.disabled = false;
	} else if(document.ledger.crorcq.options[document.ledger.crorcq.selectedIndex].value=="H") {
		document.ledger.type[0] = new Option("All","A");
		document.ledger.type[1] = new Option("Master Card","M");
		document.ledger.type[2] = new Option("Visa","V");
		document.ledger.type.disabled = false;
	}
	else{
		document.ledger.type.value= "";
		document.ledger.type.disabled = true;
	}
	return false;
}

function Displaycompany(){
	if(document.ledger.companymode.value=="A") {
		document.getElementById('allC').style.display = "";
		document.getElementById('active').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.ledger.companymode.value=="AC") {
		document.getElementById('active').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.ledger.companymode.value=="NC") {
		document.getElementById('nonactive').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('active').style.display = "none";
	}
	document.getElementById('all').selectedIndex = -1;
	document.getElementById('activename').selectedIndex = -1;
	document.getElementById('nonactivename').selectedIndex = -1;
}

function Displaycompanytype(tele_nontele) {
	document.ledger.trans_type.value="Submit";
	document.ledger.action = "report.php";
	if (tele_nontele) {
		document.ledger.companytrans_type.value="A";
	}
	document.ledger.submit();
}

function clearCheckCardNumber(type) {
	if (type == "card") {
		if (document.ledger.check_number.value != "") {
			document.ledger.check_number.value = "";
		}
	} else if (type == "check") {
		if (document.ledger.credit_number.value != "") {
			document.ledger.credit_number.value = "";
		}
	}
}
</script>
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="JavaScript" type="text/JavaScript">
function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	if (obj_element.name == "from_date"){
		document.getElementById('opt_from_day').value = dateSelected ;
		document.getElementById('opt_from_month').value = monthSelected ;
		document.getElementById('opt_from_year').value = yearSelected ;
	}
	if (obj_element.name == "from_to"){
		document.getElementById('opt_to_day').value = dateSelected ;
		document.getElementById('opt_to_month').value = monthSelected ;
		document.getElementById('opt_to_year').value = yearSelected ;
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
  <tr>
       <td width="83%" valign="top" align="center">
    
         <table width="98%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Transactions 
	        <?=$for_bank?>
	      </span></td>
	      <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">

		<form name="frmSelComp" id="frmSelComp"  method="GET" action="reportbottom1.php">
		<input type="hidden" name="hid_companies" value="">
		<input type="hidden" name="trans_type" value="">
             <br>
              <table align="center" cellpadding="0" cellspacing="0" width="100%">
                <tr> 
                  <td colspan="2" valign="top"><table width="100%">
                    <tr>
                      <td height="22" valign="middle"   align="left" width="124"><font face="verdana" size="1">
                        <?=$periodstring?>
                      </font></td>
                      <td align="left" width="228"  height="22" ><select name="opt_from_month" id="opt_from_month" style="font-size:10px">
                        <?php func_fill_month($i_from_month); ?>
                      </select>
                        <select name="opt_from_day" id="opt_from_day" class="lineborderselect" style="font-size:10px">
                          <?php func_fill_day($i_from_day); ?>
                        </select>
                        <select name="opt_from_year" id="opt_from_year" style="font-size:10px">
                          <?php func_fill_year($i_from_year); ?>
                        </select>
                        <input type="hidden" name="from_date" id="from_date" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="<?=$from_date?>">
                        <input style="font-family:verdana;font-size:10px;" type="button" value="..." onClick="init(350,90,document.getElementById('from_date'))">
                      </td>
                    </tr>
                    <tr>
                      <td height="30" valign="middle" align="left" width="124"><font face="verdana" size="1">
                        <?=$endperiodstring?>
                      </font></td>
                      <td align="left" width="228"  height="30"><select name="opt_to_month" id="opt_to_month" class="lineborderselect" style="font-size:10px">
                        <?php func_fill_month($i_to_month); ?>
                      </select>
                        <select name="opt_to_day" id="opt_to_day" class="lineborderselect" style="font-size:10px">
                          <?php func_fill_day($i_to_day); ?>
                        </select>
                        <select name="opt_to_year" id="opt_to_year" class="lineborderselect" style="font-size:10px">
                          <?php func_fill_year($i_to_year); ?>
                        </select>
                        <input type="hidden" name="from_to" id="from_to" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="<?=$from_to?>">
                        <input style="font-family:verdana;font-size:10px;" type="button" value="..." onClick="init(350,90,document.getElementById('from_to'))">
                      </td>
                    </tr>
                    <tr>
                      <td width="336" height="104" colspan="2"><table width="100%">
                          <tr >
                            <td width="35%"><font face="verdana" size="1">Pending&nbsp;&nbsp;</font></td>
                            <td align="left"><font face="verdana" size="1">
                              <input type="checkbox" name="trans_ptype" value="p">
                            </font></td>
                            <td><font face="verdana" size="1" style="font-weight:bold; " >Display UnTracked Orders</font></td>
                            <td><font face="verdana" size="1">
                              <input name="untracked_orders" type="checkbox" id="untracked_orders" value="1">
                              </font>
                                <!--   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Set to bill date&nbsp; 
                          <input type="radio" name="daterange" value="B"> -->
                            </td>
                          </tr>
                          <tr >
                            <td><font face="verdana" size="1">No Pass&nbsp;</font></td>
                            <td ><font face="verdana" size="1">
                              <input type="checkbox" name="trans_nopass" value="N">
                            </font></td>
                            <td ><font face="verdana" size="1">Declined</font></td>
                            <td ><input type="checkbox" name="trans_dtype" value="D"></td>
                          </tr>
                          <tr >
                            <td><font face="verdana" size="1">Refunded</font></td>
                            <td><font face="verdana" size="1">
                              <input type="checkbox" name="trans_ctype" value="C">
                            </font></td>
                            <td><font face="verdana" size="1"> Approved&nbsp;</font></td>
                            <td><input type="checkbox" name="trans_atype" value="A"></td>
                          </tr>
                          <tr >
                            <td><font face="verdana" size="1">Chargeback&nbsp;</font></td>
                            <td><font face="verdana" size="1">
                              <input name="trans_chargeback" type="checkbox" id="trans_chargeback" value="S">
                            </font></td>
                            <td><font face="verdana" size="1">Set to bill date </font></td>
                            <td><input name="radRange" type="checkbox" id="radRange" value="A"></td>
                          </tr>
                          <tr>
                            <td><font face="verdana" size="1">Display Test Transactions </font></td>
                            <td width="8%"><font face="verdana" size="1">
                              <input name="display_test_transactions" type="checkbox" id="display_test_transactions" value="1">
                              </font>
                                <!--   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Set to bill date&nbsp; 
                          <input type="radio" name="daterange" value="B"> -->
                            </td>
                            <td width="47%"><font face="verdana" size="1"> Recurring Transactions </font></td>
                            <td><input name="recur_transaction" type="checkbox" id="recur_transaction" value="1">
                            </td>
                          </tr>
                      </table></td>
                    </tr>
                  </table> 
				    <?php
beginTable();
echo genCompanyViewTable('reportbottom1.php','reportbottom1.php',$adminInfo['li_level']);
endTable("Company Payment");
				  ?>                  </td>
                  <td height="274" valign="top"><table width="100%">
				<tr height="25"><td><font face="verdana" size="1">First Name</font></td><td><input type="text" maxlength="100" name="firstname" style="font-family:arial;font-size:10px;width:150px"></input></td></tr>
						<tr height="25"><td><font face="verdana" size="1">Last Name</font></td><td><input type="text" maxlength="100" name="lastname" style="font-family:arial;font-size:10px;width:150px"></input></td></tr>
						<tr height="25"><td><font face="verdana" size="1">Telephone 
                    Number</font></td><td><input type="text" maxlength="10" name="telephone" style="font-family:arial;font-size:10px;width:150px"></input></td></tr>
					<tr height="25"><td><font face="verdana" size="1">Email Address</font></td><td><input type="text" maxlength="100" name="email" style="font-family:arial;font-size:10px;width:150px"></input></td></tr>					
					<tr height="25"><td><font face="verdana" size="1">Reference Number
                    </font></td><td><input type="text" maxlength="100" name="transactionId" style="font-family:arial;font-size:10px;width:150px"></input></td></tr>
					<tr height="25">
                        <td><font face="verdana" size="1">Credit Card Number</font></td>
						<td><input type="text" maxlength="16" name="credit_number" style="font-family:arial;font-size:10px;width:150px" onKeyDown="Javascript:clearCheckCardNumber('card')">
						</input></td>
					</tr>
					<tr height="25">
                        <td><font face="verdana" size="1">If Check,</font></td>
						<td>&nbsp;</td>
					</tr>
					<tr height="25">
                        <td><font face="verdana" size="1">Check Number</font></td>
						<td><input type="text" maxlength="15" name="check_number" style="font-family:arial;font-size:10px;width:150px" onKeyDown="Javascript:clearCheckCardNumber('check')"></input></td>
					</tr>
					
					<tr height="25">
                        <td><font face="verdana" size="1">Account Number</font></td>
						<td><input type="text" maxlength="15" name="account_number" style="font-family:arial;font-size:10px;width:150px"></input></td>
					</tr>
					<tr height="25">
                        <td><font face="verdana" size="1">Bank Routing Code</font></td>
						<td><input type="text" maxlength="15" name="routing_code" style="font-family:arial;font-size:10px;width:150px"></input></td>
					</tr></table></td>
                </tr>
              </table>
	</form>
	</td>
 </tr>
 <tr>
<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
</tr>
</table><br></td>
</tr>
</table>

<?php
include("includes/footer.php");
	
?>
