<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,companyAdd.php,excelout.php,massmail.php,modifyCompany.php,modifyCompanyNext.php,report.php,reportTop.php, 
//				config.php,reportBottom.php,reportBottomNext.php,useraccount.php,viewCompany.php,viewCompanyNext.php,ViewreportPage.php
// batchuploads.php:	This admin page functions for uploading the company transactions. 
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
$headerInclude = "transactions";
include 'includes/header.php';

require_once( '../includes/function.php');

$Transtype = isset($HTTP_POST_VARS['trans_type1'])?quote_smart($HTTP_POST_VARS['trans_type1']):"";
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"A";
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"A";
if ($Transtype == "Submit") {
	if($companytype =="AC") {
		$qrt_select_subqry = " activeuser=1";
	} else if($companytype =="NC") {
		$qrt_select_subqry = " activeuser=0";	
	} else if($companytype =="RE") {
		$qrt_select_subqry = " reseller_id <> ''";	
	} else if($companytype =="ET") {
		$qrt_select_subqry = " reseller_id is null";	
	} else {
		$qrt_select_subqry = "";	
	}
	if($companytrans_type =="A") {
		$qrt_select_merchant_qry = "";
	} else {
		if($qrt_select_subqry =="") {
			$qrt_select_merchant_qry = " transaction_type='$companytrans_type'";
		} else {
			$qrt_select_merchant_qry = " and transaction_type='$companytrans_type'";
		}
	}

	$str_total_query = "";
	if ($qrt_select_subqry != "" || $qrt_select_merchant_qry != "") {
		$str_total_query = "where 1 and $qrt_select_subqry $qrt_select_merchant_qry";
	} else {
		$str_total_query = "where 1 ";
	}
$qrt_select_company="select distinct userId,companyname from cs_companydetails $str_total_query order by companyname";
} else {
	$qrt_select_company ="select distinct userId,companyname from cs_companydetails where 1 order by companyname";
}
//print($qrt_select_company);
if(!($show_company_sql =mysql_query($qrt_select_company,$cnn_cs))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
?>
<script language="javascript">
function Displaycompany(){
	if(document.FrmBatch.companymode.value=="A") {
		document.getElementById('allC').style.display = "";
		document.getElementById('active').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.FrmBatch.companymode.value=="AC") {
		document.getElementById('active').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.FrmBatch.companymode.value=="NC") {
		document.getElementById('nonactive').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('active').style.display = "none";
	}
	document.getElementById('all').selectedIndex = 0;
	document.getElementById('activename').selectedIndex = 0;
	document.getElementById('nonactivename').selectedIndex = 0;
}
function validation(){
	if(document.FrmBatch.companyname.value=="") {
	 alert("Please select the company.");
	 return false;
	}
  if(document.FrmBatch.fle_attachment.value==""){
    alert("Please select the batch file.")
    document.FrmBatch.fle_attachment.focus();
	return false;
  } else if(document.FrmBatch.fle_attachment.value.indexOf('.csv')== -1 ) {
	alert("Please enter the valid csv file");
	return false;
  }else {
		return true;
  }
}

function Displaycompanytype() {
	document.FrmBatch.trans_type1.value="Submit";
	document.FrmBatch.action = "batchuploads.php";
	document.FrmBatch.submit();
}

</script>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%" >
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
<table width="50%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
          <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Batch 
            Processing</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td class="lgnbd" colspan="5">
  <form action="batchprocessing.php" method="post" onsubmit="return validation()" name="FrmBatch"  enctype="multipart/form-data" >
	<input type="hidden" name="trans_type1" value="">
	<br>  
	<table  width="100%" cellspacing="0" cellpadding="0">
	 <tr><td  width="100%" valign="center" align="center">     
      <table border="0" cellpadding="0">	  
	  <tr>
		                <td height="30" valign="middle" align="right"  width="119"><font face="verdana" size="1">Company 
                          Type&nbsp;:&nbsp;</font></td>
                        <td  width="290"> 
                          <select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
		<?php print func_select_mailcompanytype($companytype); ?>
			</select>&nbsp;</td>
			</tr>
		<tr>
		                <td height="30" valign="middle" align="right"  width="119"><font face="verdana" size="1">Merchant 
                          Type&nbsp;:&nbsp;</font></td>
                        <td  width="290"> 
                          <select name="companytrans_type" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
				<?php print func_select_companytrans_type($companytrans_type); ?>
					</select>&nbsp;</td>
			</tr>
			<tr>
                        <td colspan="2"> 
<table width="100%">
<tr>
			                  <td   height="30"  valign="middle" align="right"  width="99"><font face="verdana" size="1">Select 
                                Company&nbsp;:</font></td>
                              <td  width="275"> 
                                <select id="all" name="companyname" style="font-family:verdana;font-size:10px;WIDTH: 210px">
                                  <?php func_select_company_from_query($qrt_select_company);
			?>
                                </select></td>
                            </tr></table>
			</td></tr>
       <tr>
                        <td align="right" valign="center" height="30" width="119"><font face="verdana" size="1">Batch 
                          File :&nbsp;</font></td>
		                <td align="left" height="30" width="290"> 
                          <input type="file" name="fle_attachment" size="30"></input>
		</td>
	  </tr>
       <tr>
	                    <td align="right" valign="center" height="30" width="119"><font face="verdana" size="1">Check 
                          :&nbsp;</font></td>
		                <td align="left" height="30" width="290"><font face="verdana" size="1"> 
                          <input type="radio" name="trans_type" value="Check" checked>&nbsp;&nbsp;&nbsp;Credit Card :<input type="radio" name="trans_type" value="Credit"></font>
		</td>
	  </tr>
		  <tr><td align="center" valign="center" height="30" colspan="2"><input type="image" id="submitupload" SRC="<?=$tmpl_dir?>/images/submit.jpg"></input></td></tr>
	<tr><td align="left" valign="center" height="30"  colspan="2">
<!--  <font face="verdana" size="2">Click here to download the <a href="#" onClick="javascript:func_batchtemplate('check');">Check template</a>&nbsp;/&nbsp;<a href="#" onClick="javascript:func_batchtemplate('credit');">Credit card template</a></font> -->
  <font face="verdana" size="1">Click here to download the <a href="../downloads/check_report.csv" onClick="" target="_blank">Check template</a>&nbsp;/&nbsp;<a href="../downloads/creditcard_report.csv" onClick="" target="_blank">Credit card template</a></font>
  </td></tr>
		  
	  </table>
  </td></tr></table></form>
	</td>
      </tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
    </table>
    </td>
     </tr>
</table>
<?php include("includes/footer.php");
?>