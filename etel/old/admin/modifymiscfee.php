<html>
<head>
<title><?=$_SESSION['gw_title']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="../styles/style.css" type="text/css" rel="stylesheet">
<link href="../styles/text.css" type="text/css" rel="stylesheet">
</head>
<body topmargin="0" leftmargin="0" bgcolor="#ffffff"  marginheight="0" marginwidth="0">
<iframe name="ifr" src="../refresh.php" width="0" height="0"></iframe>
<!--header-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="125" class="bdbtm">
<tr>
<td valign="top" align="left" bgcolor="#ffffff" width="35%"><img alt='' border='0' src='../images/logo2os_L.gif'></td>
<td bgcolor="#FFFFFF" width="65%" valign="top" align="right" nowrap><img alt="" border="0" SRC="<?=$tmpl_dir?>/images/top1.jpg" width="238" height="63" ><img alt="" border="0" SRC="<?=$tmpl_dir?>/images/top2.jpg" width="217" height="63"><img alt="" border="0" SRC="<?=$tmpl_dir?>/images/top3.jpg" width="138" height="63"><br>
<img alt="" border="0" SRC="<?=$tmpl_dir?>/images/top4.jpg" width="238" height="63"><img  alt="" border="0" SRC="<?=$tmpl_dir?>/images/top5.jpg" width="217" height="63"><img alt="" border="0" SRC="<?=$tmpl_dir?>/images/top6.jpg" width="138" height="63"></td>
</tr>
</table>
<!--header ends here-->
<?php include("../includes/displaytimer.php");
require_once( '../includes/function.php');
include '../includes/dbconnection.php';
?>
<?php

$invoiceid =isset($HTTP_GET_VARS["invoiceid"])?$HTTP_GET_VARS["invoiceid"]:"";
$userid=0;
	 $appamount=0;
	 $appcount=0;
	 $deccount=0;
	 $decamt=0;
	 $credit=0;
	 $creditamt=0;
	 $chargecount=0;
	 $chargeamt=0;
	 $cancelcount=0;
	 $cancelamt=0;
	 $discamt=0;
	 $tranno=0;
	 $tranamt=0;
	 $reserveamt=0;
	$creditrate=0;
	$chargerate=0;
	$disrate=0;
	$transrate=0;
	$pendcount=0;


 $qry_details="select *  from cs_invoicedetails where invoiceId=$invoiceid";

if(!$rst_details=mysql_query($qry_details,$cnn_cs))
{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
else
{
	$rst_invoice=mysql_fetch_array($rst_details);
		$netamount=$rst_invoice['netAmount'];
	 $userid=$rst_invoice['userId'];
	 $appamount=$rst_invoice['approvedAmt'];
	 $appcount=$rst_invoice['approvedno'];
	 $deccount=$rst_invoice['declinedno'];
	 $decamt=$rst_invoice['declinedAmt'];
	 $creditcount=$rst_invoice['creditno'];
	 $creditamt=$rst_invoice['credit'];
	 $chargecount=$rst_invoice['chargebackno'];
	 $chargeamt=$rst_invoice['chargeBack'];
	 $cancelcount=$rst_invoice['canceledno'];
	 $cancelamt=$rst_invoice['creditAmt'];
	 $discamt=$rst_invoice['discount'];
	 $tranno=$rst_invoice['transactionno'];
	 $tranamt=$rst_invoice['transactionFee'];
	 $reserveamt=$rst_invoice['reserveFee'];
	 $miscadd=$rst_invoice['miscadd'];
	 $miscsub=$rst_invoice['miscsub'];
	 $miscadddesc=$rst_invoice['miscadd_disc'];
	 $miscsubdesc=$rst_invoice['miscsub_disc'];
		$chqorcrd=$rst_invoice['checkorcard'];
		$wirefee=$rst_invoice['wirefee'];
		$approvedstatusdate=$rst_invoice['generateddate'];
		$rejectedcount=$rst_invoice['reject_count'];
		$rejectedamt=$rst_invoice['reject_amt'];
		$rejectedcreditamt=$rst_invoice['reject_creditamt'];
		$rejectedchargebackamt=$rst_invoice['reject_chargebackamt'];
		$rejectedtransfee=$rst_invoice['reject_transfee'];
		$rejectedcreditcount=$rst_invoice['reject_creditcount'];
		$rejectedchargebackcount=$rst_invoice['reject_chargebackcount'];
 $qry_select="Select credit ,chargeback,discountrate,transactionfee,reserve from cs_companydetails where userId=$userid";
	 if(!$rst_query=mysql_query($qry_select,$cnn_cs))
		{
			print(mysql_errno().": ".mysql_error()."<BR>");
			print("Cannot execute query here");
			exit();
		}
	else
		{
			$rst_rates=mysql_fetch_array($rst_query);
			$creditrate=$rst_rates[0];
			$chargerate=$rst_rates[1];
			$disrate=$rst_rates[2];
			$transrate=$rst_rates[3];
			$reserverate=$rst_rates[4];
		}
		$qry_netamount="select netAmount from cs_invoicedetails where userId =$userid and checkorcard ='$chqorcrd' and generateddate <' $approvedstatusdate' order by generateddate desc";
		$rst_execute=mysql_query($qry_netamount,$cnn_cs);
		$rst_netamount=mysql_fetch_array($rst_execute);
		
		
		 	$pevnetamount=$rst_netamount[0];
		
		
	 	
}
	


?>
<script type="text/javascript">
function funcValidate(objForm)
{
objElement	=	objForm.txt_appcount;
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
	objElement	=	objForm.txt_appamt;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}	
	objElement	=	objForm.txt_deccount;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
		objElement	=	objForm.txt_decamt;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
			objElement	=	objForm.txt_crecount;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
	objElement	=	objForm.txt_crerate;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
		objElement	=	objForm.txt_creamt;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}					
	objElement	=	objForm.txt_charcount;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}					
	objElement	=	objForm.txt_charrate;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
	objElement	=	objForm.txt_charamt;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
		objElement	=	objForm.txt_canccount;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
		objElement	=	objForm.txt_cancamt;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
	//modified
	<?php if($rejectedcount >0){?>
	objElement	=	objForm.txt_rejcreditamt;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
	objElement	=	objForm.txt_rejchargebackamt;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
	objElement	=	objForm.txt_rejtransfee;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
	objElement	=	objForm.txt_rejcancamt;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}//modifird
	<? }?>
	objElement	=	objForm.txt_disrate;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
	objElement	=	objForm.txt_disamt;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
	objElement	=	objForm.txt_trancount;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
	objElement	=	objForm.txt_tranrate;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
	objElement	=	objForm.txt_tranamt;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
	objElement	=	objForm.txt_resrate;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
	objElement	=	objForm.txt_resamt;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
	
	objElement	=	objForm.txt_miscadd;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
	objElement	=	objForm.txt_miscsub;	
	if ( isNaN(objElement.value) ) {
		alert("Please enter Numeric values only");
		objElement.focus()
		return false;
	}
	var miscadd= parseFloat ( document.frmBanks.txt_miscadd.value) + parseFloat (document.frmBanks.hid_miscadd.value);
	var miscsub=parseFloat(document.frmBanks.txt_miscsub.value) +parseFloat( document.frmBanks.hid_miscsub.value);	
	if((document.frmBanks.txt_miscadd.value!=0) || (document.frmBanks.txt_miscsub.value !=0))
	{
	if(!confirm(miscadd+"$ is added to the net amount "+miscsub+"$ is subtracted from the netamount")){
	return false;
	}
	}

	return true;
}


function reloadParent() {
  self.close()
}
function func_invoice()
{
	document.frmBanks.method="POST";
	document.frmBanks.action="invoiceregeneration.php";
	document.frmBanks.submit();
}
function addRatesFees(selection)
{
	if( selection=='disc')
	{
		<?php if($rejectedcount >0){?>
		document.frmBanks.txt_rejcreditamt.value=parseFloat(document.frmBanks.txt_rejcreditcount.value) * parseFloat(document.frmBanks.txt_rejchargebackcount.value)
		<? } ?>
		document.frmBanks.txt_creamt.value=parseFloat(document.frmBanks.txt_crecount.value) * parseFloat(document.frmBanks.txt_crerate.value)
	}
	if( selection=='crg')
	{
		<?php if($rejectedcount >0){?>
		document.frmBanks.txt_rejchargebackamt.value=parseFloat(document.frmBanks.txt_charrate.value) * parseFloat(document.frmBanks.txt_crerate.value)
		<? } ?>
		document.frmBanks.txt_charamt.value=parseFloat(document.frmBanks.txt_charrate.value) * parseFloat(document.frmBanks.txt_charcount.value)
	}
	if( selection=='app')
	{
		document.frmBanks.txt_resamt.value=parseFloat(document.frmBanks.txt_resrate.value) * parseFloat(document.frmBanks.txt_appamt.value)
		document.frmBanks.txt_disamt.value=parseFloat(document.frmBanks.txt_disrate.value) * parseFloat(document.frmBanks.txt_appamt.value)

	}
	if( selection=='tran')
	{
		<?php if($rejectedcount >0){?>
		document.frmBanks.txt_rejtransfee.value=parseFloat(document.frmBanks.txt_tranrate.value) * parseFloat(document.frmBanks.txt_rejcanccount.value)
		<? } ?>
		document.frmBanks.txt_tranamt.value=parseFloat(document.frmBanks.txt_tranrate.value) * parseFloat(document.frmBanks.txt_trancount.value)
	}
}

</script>
<form name="frmBanks" action="updateautoinvoice.php"  method="post" onSubmit="return funcValidate(document.frmBanks);" >
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="70%">
	<tr><td>
	<!--details table begins -->
	<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	    <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Modify Invoice           </span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5">
				<table align="center" width="95%" cellpadding="0" cellspacing="0">
					<tr height='20' bgcolor='#CCCCCC'>
							<td align='center' class='cl1' colspan="4" ><span class="subhd">Details</span></td>
					</tr>
					<tr>
						
                  <td colspan="2" valign="top" class="lgnbd"> 
<table cellpadding="0" cellspacing="0">
							<tr>
									<td align='left' class='cl1' ><font face='verdana' size='1'>Approved Quantity </font></td>
									<td align='left' class='cl1' ><font face='verdana' size='1'><input type="text" name="txt_appcount" value="<? echo $appcount;?>" disabled></font></td>
							</tr>
							<tr>
									<td  class='cl1' align="left"><font face='verdana' size='1'>Approved Amount</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_appamt" value="<? echo $appamount;?>" onChange="addRatesFees('app')"></font></td>
							</tr>
							<tr>
									<td align='left' class='cl1' ><font face='verdana' size='1'>Declined Quantity</font></td>
									<td align='left' class='cl1' ><font face='verdana' size='1'><input type="text" name="txt_deccount" value="<? echo $deccount;?>" disabled></font></td>
							</tr>
							<tr>
									<td align='left' class='cl1'><font face='verdana' size='1'>Declined Amount</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_decamt" value="<? echo $decamt;?>"></font></td>
							</tr>
							<tr>
									<td align='left' class='cl1' ><font face='verdana' size='1'>Credit Quantity</font></td>
									<td align='left' class='cl1' ><font face='verdana' size='1'><input type="text" name="txt_crecount" value="<? echo  $creditcount;?>" onChange="addRatesFees('disc')" disabled></font></td>
							</tr>
							<tr>	<td align='left' class='cl1' ><font face='verdana' size='1'>Credit Rate</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_crerate" value="<? echo $creditrate;?>" onChange="addRatesFees('disc')"></font></td>
							</tr>
							<tr>	<td align='left' class='cl1' ><font face='verdana' size='1'>Credit Amount</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_creamt" value="<? echo $creditamt;?>"></font></td>
							</tr>
							<tr>
									<td align='left' class='cl1' ><font face='verdana' size='1'>Chargeback Quantity</font></td>
									<td align='left' class='cl1' ><font face='verdana' size='1'><input type="text" name="txt_charcount" value="<? echo $chargecount;?>" onChange="addRatesFees('crg')" disabled></font></td>
							</tr>
							<tr>
									<td align='left' class='cl1' ><font face='verdana' size='1'>Chargeback Rate</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_charrate" value="<? echo $creditrate;?>" onChange="addRatesFees('crg')"></font></td>
							</tr>
							<tr>
									<td align='left' class='cl1' ><font face='verdana' size='1'>Chargeback Amount</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_charamt" value="<? echo $chargeamt;?>" ></font></td>
							</tr>
							</table>
					   </td>
					  
                  <td colspan="2" valign="top" class="lgnbd" width="50%"> 
<table width="100%" cellpadding="0" cellspacing="0">
							<tr valign="top">
									<td align='left' class='cl1' ><font face='verdana' size='1'>Cancelled count</font></td>
									<td align='left' class='cl1' ><font face='verdana' size='1'><input type="text" name="txt_canccount" value="<? echo $cancelcount;?>" disabled></font></td>
							</tr>
							<tr>	<td align='leftr' class='cl1'><font face='verdana' size='1'>Cancelled Amount</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_cancamt" value="<? echo $cancelamt;?>"></font></td>
							</tr>
							<?php if($rejectedcount >0){?>
							<tr >
									<td align='left' class='cl1' ><font face='verdana' size='1'>Rejected Cancelled count</font></td>
									<td align='left' class='cl1' ><font face='verdana' size='1'><input type="text" name="txt_rejcanccount" value="<? echo $rejectedcount;?>" disabled></font></td>
							</tr>
							<tr>	
                        <td align='leftr' class='cl1'><font face='verdana' size='1'>Rejected 
                          cancelled Amount</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_rejcancamt" value="<? echo $rejectedamt;?>"></font></td>
							</tr>
							<tr>	
                        <td align='leftr' class='cl1'><font face='verdana' size='1'>Rejected 
                          Transaction Fee</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_rejtransfee" value="<? echo $rejectedtransfee;?>"></font></td>
							</tr>
							<tr >
									<td align='left' class='cl1' ><font face='verdana' size='1'>Rejected credit count</font></td>
									<td align='left' class='cl1' ><font face='verdana' size='1'><input type="text" name="txt_rejcreditcount" value="<? echo $rejectedcreditcount;?>" disabled></font></td>
							</tr>
							<tr>	
                        <td align='leftr' class='cl1'><font face='verdana' size='1'>Rejected 
                          credit Amount</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_rejcreditamt" value="<? echo $rejectedcreditamt;?>"></font></td>
							</tr>
							<tr >
									<td align='left' class='cl1' ><font face='verdana' size='1'>Rejected chargeback count</font></td>
									<td align='left' class='cl1' ><font face='verdana' size='1'><input type="text" name="txt_rejchargebackcount" value="<? echo $rejectedchargebackcount;?>" disabled></font></td>
							</tr>
							<tr>	
                        <td align='leftr' class='cl1'><font face='verdana' size='1'>Rejected Chargeback amount
                          </font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_rejchargebackamt" value="<? echo $rejectedchargebackamt;?>"></font></td>
							</tr>
							<?php } ?>
							<tr>
									<td align='left' class='cl1' ><font face='verdana' size='1'>Discount Rate</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_disrate" value="<? echo $disrate;?>" onChange="addRatesFees('app')"></font></td>
							</tr>
							<tr>
									<td align='left' class='cl1' ><font face='verdana' size='1'>Discount Amount</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_disamt" value="<? echo $discamt;?>"></font></td>
							</tr>
							
							<tr>
									<td align='left' class='cl1' ><font face='verdana' size='1'>Transaction Quantity</font></td>
									<td align='left' class='cl1' ><font face='verdana' size='1'><input type="text" name="txt_trancount" value="<? echo $tranno;?>" disabled></font></td>
							</tr>
							<tr>
									<td align='left' class='cl1' ><font face='verdana' size='1'>Transaction Rate</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_tranrate" value="<? echo $transrate;?>" onChange="addRatesFees('tran')"></font></td>
							</tr>
							<tr>
									<td align='left' class='cl1' ><font face='verdana' size='1'>Transaction Amount</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_tranamt" value="<? echo $tranamt;?>" ></font></td>
							</tr>
							<tr>
									<td align='left' class='cl1' ><font face='verdana' size='1'>Reserve Rate</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_resrate" value="<? echo $reserverate;?>" onChange="addRatesFees('app')"></font></td>
							</tr>		
							<tr>	<td align='left' class='cl1' ><font face='verdana' size='1'>Reserve Amount</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type="text" name="txt_resamt" value="<? echo $reserveamt;?>" ></font></td>
									<input type='hidden' name='hid_netamt' value='<?=$netamount?>'>
							</tr>
							<?php if(($pevnetamount < 500) && ($pevnetamount!="") ){  ?>
							<tr>	<td align='left' class='cl1' ><font face='verdana' size='1'>Previous NetAmount</font></td>
									<td align='left' class='cl1'><font face='verdana' size='1'><input type='text' name='hid_prevnet' value='<?=$pevnetamount?>'></font></td>
									<?php }?>
							</tr>
						</table>
					</td>
				</tr>
				<tr height='20' bgcolor='#CCCCCC'>
						<td  class='cl1' colspan="4" align="center"><span class="subhd"><font face='verdana' size='1'>Misc Fee</font></span></td>		
				</tr>
				<tr>
						
                  <td width="15%" align='left' class='cl1' ><font face='verdana' size='1'>Misc 
                    Add</font></td>
						
                  <td align='left' class='cl1' width="35%" ><font face='verdana' size='1'> 
<input type="text" name="txt_miscadd" value="0">
                    <br>
                    (Amount added to the net amount)</font></td>
						<input type="hidden" name="hid_miscadd" value="<?= $miscadd?>">
						<td align='left' class='cl1' colspan="2"><font face='verdana' size='1'><input type="text" name="txt_miscadddisc" value="<?=$miscadddesc?>"> (Reason)</font></td>
				</tr>
				<tr>
						<td align='left' class='cl1' ><font face='verdana' size='1'>Misc Subtract</font></td>
						
                  <td align='left' class='cl1'width="35%" ><font face='verdana' size='1'> 
<input type="text" name="txt_miscsub" value="0">
                    <br>
                     (Amount subtracted from the net amount)</font></td>
						<input type="hidden" name="hid_miscsub" value="<?=$miscsub?>">
						<td align='left' class='cl1' colspan="2"><font face='verdana' size='1'><input type="text" name="txt_miscsubdesc" value="<?=$miscsubdesc?>">(Reason)</font></td>
				</tr>
				<tr>
						<td align='center' class='cl1' colspan="2"><font face='verdana' size='1'>Wire Fee</font></td>
						<td align='left' class='cl1' colspan="2" ><font face='verdana' size='1'><input type="text" name="txt_wirefee" value="<?=$wirefee?>">  </font></td>
						
				</tr>
				<tr>
						
                  <td align='left' class='cl1' colspan="4"><font face='verdana' size='1' color="#0066FF"> 
                    <?= $miscadd?>$
                    Already added to the net amount<br>
                    <?=$miscsub?>$
                    Already subtracted from netamount </font> </td>
						
						
				</tr>
				
				<tr>
					<td colspan="4" align="center"> 
					<a href="javascript:func_invoice()"><img border="0" SRC="<?=$tmpl_dir?>/images/reset.gif"></a>&nbsp;<input name="image" type="image" SRC="<?=$tmpl_dir?>/images/submit.jpg" alt="submit"> &nbsp;<a href="javascript:reloadParent()"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a>
					</td>
				</tr>
				<input type="hidden" name="invoiceid" value="<?=$invoiceid?>">
				<input type="hidden" name="hid_userid" value="<?=$userid?>"> 
				<input type="hidden" name="hid_gendate" value="<?=$approvedstatusdate?>">
						</table>
	</td>
 </tr>
<tr>
<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
</tr>
</table>
<!--details table ends -->
</td>
</tr>		
</table>
</form>
<?php
include("includes/footer.php");
?>
