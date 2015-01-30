<?php

	
	
	include("includes/sessioncheck.php");
	$headerInclude="transactions";
	include("includes/header.php");
	 
	//"SELECT name,surname,recur_mode,recur_times,recur_start_date,userid,rebill_transactionid,recur_charge,recur_date  FROM cs_rebillingdetails where recur_times>=0";
	$numrows=0;
	//$qry_select ="SELECT a.name,a.surname,a.recur_mode,a.recur_times,a.recur_start_date,a.userid,a.rebill_transactionid,a.recur_charge,a.recur_date  FROM cs_rebillingdetails as a ,cs_companydetails as b where a.userId=b.userId and (a.recur_times>=0 and b.gateway_id=-1)";
	
	$bank_id=$_POST['bank_id'];
	if($bank_id) $bank_sql = "AND t.bank_id = '$bank_id' ";
	$sql="SELECT transactionId,bank_name FROM `cs_transactiondetails` as t,`cs_bank` as b WHERE `td_enable_rebill` = 1 AND `td_recur_attempts` = 0 AND `td_recur_processed` = 0 AND `status`= 'A' AND `td_rebillingID` <> -1 AND `cancelstatus` = 'N' AND `td_is_chargeback` = '0' $bank_sql AND t.bank_id=b.bank_id  ORDER BY t.`bank_id` ASC ";	
	
	$result = mysql_query($sql) or dieLog(mysql_error()." $sql");
	$numrows =mysql_num_rows($result);
	$total = 0;?>
<style type="text/css">
<!--
.tdbdr {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	border-top: 0px inset #999999;
	border-right: 0px inset #999999;
	border-bottom: 1px inset #999999;
	border-left: 1px inset #999999;
	text-align: center;
}
.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
<form name="frmrecurlist" method="post" action="" >
  <table width="89%" height="131" border="0" align="center" cellpadding="0" cellspacing="0" >
    <tr>
      <td height="22" align="left" valign="top" width="2%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
      <td height="22" align="center" valign="middle" width="27%" background="../images/menucenterbg.gif" ><span class="whitehd">Recurring&nbsp;Details</span></td>
      <td height="22" align="left" valign="top" width="6%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="56" height="22"></td>
      <td height="22" align="left" valign="top" width="56%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
      <td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="9%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
    </tr>
    <tr>
      <td height="96" colspan="5" class="lgnbd">
	  <table width="100%" height="47" cellpadding="0" cellspacing="0">
          <tr align="left" valign="middle" height="20">
	 	    <td colspan=10>
		  		Show 
		  		<select name="bank_id">
		  		  <option value="">All Banks</option>
				<?php func_fill_combo_conditionally("Select * from cs_bank",$bank_id,$cnn_cs) ?>
				</select>
		  		<input type="image" id="viewcompany" SRC="<?=$tmpl_dir?>/images/submit.jpg" width="49" height="20">
		 	 </td>
		  </tr>

          <?php 	for($i=1;$i<=$numrows;$i++) { 
		$value=mysql_fetch_assoc($result);
		$transInfo = getTransactionInfo($value['transactionId']);
		//$qry_company="select companyname ,processing_currency from cs_companydetails where userId =$value[5]";
		//$company_res= mysql_query($qry_company);
		//$company_row=mysql_fetch_row($company_res);
		
		//$qry_select_details="select a.companyname, a.processing_currency,b.reference_number from cs_companydetails as a,cs_transactiondetails as b where a.userId=$userid and b.transactionId=$value[6]";
		//$company_res= mysql_query($qry_select_details);
		//$details_row=mysql_fetch_row($company_res);
		//$refnum=$details_row[2];
		
		/*$qry_ref_no="select reference_number from cs_transactiondetails where transactionId=$value[6]";
		$refno_res= mysql_query($qry_ref_no);
		$ref_row=mysql_fetch_row($refno_res);
		*/
		//print "<tr><td  width='7%' class='cl1'>$value[0] &nbsp;</td></tr>";
		//print "<tr><td  width='7%' class='cl1'>$value[1] &nbsp;</td></tr>";
		//print "<tr><td  width='7%' class='cl1'>$value[2] &nbsp;</td></tr>";

$totalrecur += floatval($transInfo['subAcc']['recur_charge']);
$total++;

if ($bankid != $transInfo['bank_id']) { 
	$bankid = $transInfo['bank_id'];
	$totalrecur = 0;
	$total = 1;
	$totalrecur += floatval($transInfo['subAcc']['recur_charge']);
	
	if ($i != 1) { 
?>
          <tr align="left" valign="middle" height="20" >
            <td>&nbsp;</td>
            <td></font></td>
            <td>&nbsp;</td>
            <td  valign ='middle'>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td  class="tdbdr">Total: <?=$total ?></td>
            <td  class="tdbdr">$<?=$totalrecur?></td>
          </tr> 
          <tr height='20'>
		   <td colspan=10></td>
		  </tr>
<? } ?>
          <tr align="center" valign="middle" bgcolor='#CCCCCC' height='30'>
		   <td colspan=10>
		   	 <span class="style1"> - <?=$value['bank_name']?> - </span></td>
		  </tr>
          <tr valign="middle" bgcolor='#CCCCCC' height='18'>
            <td align='left' class='cl1'><div align="center"><span class="subhd">-</span></div></td>
            <td align='left' class='cl1'><span class="subhd">Ref: number</span></td>
            <td align='left' class='cl1'><span class="subhd">Company Name</span></td>
            <td align='left' class='cl1'><span class="subhd">Name</span></td>
            <td width="120" align='left' class='cl1'><span class="subhd">Recurring mode</span></td>
            <td align='left' class='cl1'><span class="subhd">Recur. times</span></td>
            <td align='left' class='cl1'><span class="subhd">Recur.start date</span></td>
            <td align='left' class='cl1'><span class="subhd">Recur. next date</span></td>
            <td align='left' class='cl1'><span class="subhd">Recur. charge</span></td>
          </tr>
<? } 

?>
		  
          <tr align="left" valign="middle" height="20" >
            <td  class="tdbdr"><?=$total?>.</td>
            <td  class="tdbdr"><a href="viewreportpage.php?id=<?=$transInfo['transactionId']?>"><?=$transInfo['reference_number']?></a>&nbsp;</font></td>
            <td   class="tdbdr"><?=$transInfo['companyname']?>
&nbsp;</font></td>
            <td  valign ='middle' class="tdbdr"><?=$transInfo['surname']?>,
              <?=$transInfo['name']?>
&nbsp;</font></td>
            <td  class="tdbdr">$<?=formatMoney($transInfo['subAcc']['recur_charge'])?>
              Every
              <?=$transInfo['subAcc']['recur_day']?>
              Days</td>
            <td  class="tdbdr"><?=$transInfo['td_recur_num']?>
&nbsp;</font></td>
            <td  class="tdbdr"><?=date('Y-m-d',strtotime($transInfo['transactionDate']))?>
&nbsp;</font></td>
            <td  class="tdbdr"><?=$transInfo['td_recur_next_date']?>
&nbsp;</font></td>
            <td  class="tdbdr">$<?=formatMoney($transInfo['subAcc']['recur_charge'])?>              </font></td>
          </tr>
          <?Php		
}
if($numrows){
?>
          <tr align="left" valign="middle" height="20" >
            <td>&nbsp;</td>
            <td></font></td>
            <td>&nbsp;</td>
            <td  valign ='middle'>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td  class="tdbdr">Total: <?=$total?></td>
            <td  class="tdbdr">$<?=$totalrecur?></td>
          </tr>
		  <?php } ?>
          <tr>
            <td colspan='10' align="center" >            </input></td>
          </tr>
        </table>
        <!-- Reports ends here -->
        <br></td>
    </tr>
    <tr>
      <td width="2%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif" width="23" height="10"></td>
      <td colspan="3" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
      <td width="9%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif" width="16" height="11"></td>
    </tr>
    <tr>
      <td><input type="hidden" name= "count" value="<?=$numrows?>" ></td>
    </tr>
  </table>
</form>
<?php
	include("includes/footer.php");
?>
