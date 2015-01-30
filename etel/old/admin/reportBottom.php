<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,companyAdd.php,excelout.php,massmail.php,modifyCompany.php,modifyCompanyNext.php,report.php,reportTop.php, 
//				config.php,reportBottom.php,reportBottomNext.php,useraccount.php,viewCompany.php,viewCompanyNext.php,ViewreportPage.php
// reportBottom.php:	The admin page functions for report view of the company. 
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
$headerInclude="transactions";
include 'includes/header.php';



$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$i=0;
$periodhead ="";
$status_qry ="";		
$dateToEnter = (isset($HTTP_POST_VARS['dateToEnter'])?quote_smart($HTTP_POST_VARS['dateToEnter']):"");
$dateToEnter1 = (isset($HTTP_POST_VARS['dateToEnter1'])?quote_smart($HTTP_POST_VARS['dateToEnter1']):"");
$querystrN = (isset($HTTP_POST_VARS['querystrN'])?quote_smart($HTTP_POST_VARS['querystrN']):"");
$qrt_select_details =  (isset($HTTP_POST_VARS['qrt_select_details'])?quote_smart($HTTP_POST_VARS['qrt_select_details']):"");
$companyname = $HTTP_POST_VARS['companyname'];
$querycc = (isset($HTTP_POST_VARS['querycc'])?quote_smart($HTTP_POST_VARS['querycc']):"");
$trans_period = (isset($HTTP_POST_VARS['period'])?quote_smart($HTTP_POST_VARS['period']):"");
$txtDate = (isset($HTTP_POST_VARS['txtDate'])?quote_smart($HTTP_POST_VARS['txtDate']):"");
$txtDate1 = (isset($HTTP_POST_VARS['txtDate1'])?quote_smart($HTTP_POST_VARS['txtDate1']):"");
$noitems = (isset($HTTP_POST_VARS['noitems'])?quote_smart($HTTP_POST_VARS['noitems']):"");
$page =  (isset($HTTP_POST_VARS['page'])?quote_smart($HTTP_POST_VARS['page']):"");
if(isset($HTTP_POST_VARS['tid']))
{
	$tid = $HTTP_POST_VARS['tid'];
}
if(isset($HTTP_POST_VARS['status']))	
{
	$status = $HTTP_POST_VARS['status'];
}	
if(isset($HTTP_POST_VARS['cancel']))
{
	$cancel = $HTTP_POST_VARS['cancel'];
}
if(isset($HTTP_POST_VARS['cancelreason']))	
{
	$cancelreason = $HTTP_POST_VARS['cancelreason'];
}
if(isset($HTTP_POST_VARS['other']))
{	
	$other = $HTTP_POST_VARS['other'];
}	

$noitems = (isset($HTTP_POST_VARS['noitems'])?quote_smart($HTTP_POST_VARS['noitems']):"");
$crorcq = (isset($HTTP_POST_VARS['crorcq'])?quote_smart($HTTP_POST_VARS['crorcq']):"");
$submittransaction = (isset($HTTP_POST_VARS['submittransaction'])?quote_smart($HTTP_POST_VARS['submittransaction']):"");
$yyyyCur = (isset($HTTP_POST_VARS['yyyyCur'])?quote_smart($HTTP_POST_VARS['yyyyCur']):"");
$mmCur = (isset($HTTP_POST_VARS['mmCur'])?quote_smart($HTTP_POST_VARS['mmCur']):"");
$ddCur = (isset($HTTP_POST_VARS['ddCur'])?quote_smart($HTTP_POST_VARS['ddCur']):"");
$trans_approved =(isset($HTTP_GET_VARS['trans_atype'])?quote_smart($HTTP_GET_VARS['trans_atype']):"");
$trans_pending =(isset($HTTP_GET_VARS['trans_ptype'])?quote_smart($HTTP_GET_VARS['trans_ptype']):"");
$trans_canceled =(isset($HTTP_GET_VARS['trans_ctype'])?quote_smart($HTTP_GET_VARS['trans_ctype']):"");
$trans_declined =(isset($HTTP_GET_VARS['trans_dtype'])?quote_smart($HTTP_GET_VARS['trans_dtype']):"");
$trans_billdate =(isset($HTTP_GET_VARS['settobilldate'])?quote_smart($HTTP_GET_VARS['settobilldate']):"");
$trans_orderentry =(isset($HTTP_GET_VARS['orderentry'])?quote_smart($HTTP_GET_VARS['orderentry']):"");
if(!($trans_period)){ $trans_period = (isset($HTTP_GET_VARS['period'])?quote_smart($HTTP_GET_VARS['period']):"");}
$transactionId=(isset($HTTP_POST_VARS['transactionId'])?quote_smart($HTTP_POST_VARS['transactionId']):"");
$trans_status_qry ="";
$displaybetween= $txtDate . " and " . $txtDate1;
if($querystrN==""){$querystrN =(isset($HTTP_GET_VARS['querystrN'])?quote_smart($HTTP_GET_VARS['querystrN']):"");}
if($dateToEnter1==""){$dateToEnter1 =(isset($HTTP_GET_VARS['dateToEnter1'])?quote_smart($HTTP_GET_VARS['dateToEnter1']):"");}
if($companyname ==""){$companyname =(isset($HTTP_GET_VARS['companyname'])?quote_smart($HTTP_GET_VARS['companyname']):"");}
if($transactionId ==""){$transactionId=(isset($HTTP_GET_VARS['transactionId'])?quote_smart($HTTP_GET_VARS['transactionId']):"");}
if($txtDate ==""){$txtDate=(isset($HTTP_GET_VARS['txtDate'])?quote_smart($HTTP_GET_VARS['txtDate']):"");}
if($dateToEnter ==""){$dateToEnter=(isset($HTTP_GET_VARS['dateToEnter'])?quote_smart($HTTP_GET_VARS['dateToEnter']):"");}
if($noitems ==""){$noitems=(isset($HTTP_GET_VARS['noitems'])?quote_smart($HTTP_GET_VARS['noitems']):"");}

if($sessionAdmin!="")
{ 
	  if($submittransaction)
	  {
			if(isset($noitems))
			{		
			   for($j=0;$j<$noitems;$j++)
			   {
					$tid1=(isset($tid[$j])?quote_smart($tid[$j]):"");
					$val = (isset($status[$j])?quote_smart($status[$j]):"");	
					if($val != "")
					{
						$qrt_update_status = "update cs_transactiondetails set status='$val' where transactionId=$tid1";
						if(!($show_update_sql =mysql_query($qrt_update_status,$cnn_cs)))
						{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

						}
					 }	
				 }
			 }
			 if(isset($cancel))
			 {
				for($j=0;$j<count($cancel) ;$j++)
				{
					$creason=(isset($cancelreason[$cancel[$j]])?quote_smart($cancelreason[$cancel[$j]]):"");
					$cother=(isset($other[$cancel[$j]])?quote_smart($other[$cancel[$j]]):"");
					if(isset($cancel[$j]))
					{
						$qrt_update_cancel = "update cs_transactiondetails set cancelstatus='Y',reason='$creason',other='$cother' where transactionId=$cancel[$j]";
						if(!($show_update_sql =mysql_query($qrt_update_cancel,$cnn_cs)))
						{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

						}
					}	
				}
			 }
			$outhtml="y";
			$msgtodisplay="Selected transactions has been updated";
			message($msgtodisplay,$outhtml,$headerInclude); 								
			exit();
	  }
	  else 
	  { 
	  	if($trans_period=="p" )
		{
		   if($txtDate)
		   {
			   list ($mm, $dd, $yyyy) = split ('[/.-]', $txtDate);
			   if($mm<10) 
			   {
				   	$mm = "0".$mm;
			   }
			   $dateToEnter="$yyyy-$mm-$dd";
               list ($mm1, $dd1, $yyyy1) = split ('[/.-]', $txtDate1);
			   if($mm1<10) 
			   {
			   		$mm1 = "0".$mm1;
			   }
			   $dateToEnter1="$yyyy1-$mm1-$dd1 23:59:59";
		   } 
		   else 
		   {
				$ddCur=date("d");
				$mmCur=date("n");
				$yyyyCur=date("Y");
				$dateToEnter="$yyyyCur-$mmCur-$ddCur";
				$dateToEnter1="$yyyyCur-$mmCur-$ddCur 23:59:59:";
		   }
		}
			$trans_datebetween = "a.transactionDate between '$dateToEnter' and '$dateToEnter1'";
			if($trans_approved){$trans_status_qry = "and a.status='A'";}
			if($trans_pending){$trans_status_qry  = "and a.status='P'";}
			if($trans_declined){$trans_status_qry = "and a.status='D'";}
			if($trans_canceled){$trans_status_qry = "and a.cancelstatus='Y'";}
			if($trans_billdate){$trans_status_qry = "a.billingDate between '$dateToEnter' and '$yyyy1-$mm1-$dd1'"; $trans_datebetween="";}
			if($trans_orderentry){$trans_status_qry = "a.transactionDate between '$dateToEnter' and '$dateToEnter1'"; $trans_datebetween="";}
			if($crorcq)
			{
				  if($crorcq!="A")
				  {
					  $querycc="and a.checkorcard='$crorcq'";
				  }
			}
		if(!$trans_period)
		{
			  $dateToEnter="$yyyyCur-$mmCur-$ddCur";
			  $dateToEnter1="$yyyyCur-$mmCur-$ddCur 23:59:59";
			  $qrt_select_details ="select a.status,a.transactionId,b.companyname,a.name,a.surname,a.checkorcard,a.amount,a.misc,a.transactionDate,a.cancelstatus,a.reason,a.other from cs_transactiondetails as a,cs_companydetails as b where $trans_datebetween $trans_status_qry and a.userid=b.userid ".$querycc;
			  $qrt_select_total="select sum(a.amount) as totamount from cs_transactiondetails as a,cs_companydetails as b where $trans_datebetween $trans_status_qry and a.userid=b.userid ".$querycc;
		}
	  	if($trans_period=="p" )
		{
			$qrt_select_details ="select a.status,a.transactionId,b.companyname,a.name,a.surname,a.checkorcard,a.amount,a.misc,a.transactionDate,a.cancelstatus,a.reason,a.other from cs_transactiondetails as a,cs_companydetails as b where $trans_datebetween $trans_status_qry  and a.userid=b.userid ".$querycc;
			$qrt_select_total="select sum(a.amount) as totamount from cs_transactiondetails as a,cs_companydetails as b where $trans_datebetween $trans_status_qry and a.userid=b.userid ".$querycc;
				   
		}
		if (!$noitems)
		{
		  	$noitems=100;
		}
						
		if($trans_period=="p")
		{	  
			 $periodhead="Periodic Transaction Report";
		}
		if($page) 
		{
			  $qrt_select_details="select a.status,a.transactionId,b.companyname,a.name,a.surname,a.checkorcard,a.amount,a.misc,a.transactionDate,a.cancelstatus,a.reason,a.other from cs_transactiondetails as a,cs_companydetails as b where $trans_datebetween $trans_status_qry  and a.userid=b.userid ".$querycc;
			  $qrt_select_total="select sum(a.amount) as totamount from cs_transactiondetails as a,cs_companydetails as b where $trans_datebetween $trans_status_qry and a.userid=b.userid ".$querycc;
		}
		//limiting no of items,query preparation
		if (!$querystrN)
		{
		    $noitems1 = $noitems+1;				    
			$qrt_select_details1 =$qrt_select_details." order by a.transactionId desc limit 0,$noitems1";
		}
		else 
		{
			$qrt_select_details=urldecode($querystrN);
			$qrt_select_details=str_replace("\'","'",$$qrt_select_details);
			$noitems1 = $noitems+1;						
			$qrt_select_details1=$qrt_select_details." and a.transactionId <=$transactionId order by a.transactionId desc limit 0,$noitems1";
			$backstr="<a class='link1' href='javascript:window.history.back()'>Previous</a>";
		}

		if(!($show_select_total = mysql_query($qrt_select_total,$cnn_cs)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		while($qrt_total_run = mysql_fetch_array($show_select_total))
		{
			 $totalamount=$qrt_total_run[0];
		}
				
				
?>
<script language="javascript">
function pagecall() {
document.dates1.submittransaction.value="";
document.dates1.submit();
}
function func_update()
{
	objForm = document.dates1;
	objForm.action = "updatetransactions.php";
	objForm.method = "post";
	objForm.submit();
}
</script>

	<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%" >
	  <tr>
		   <td width="83%" valign="top" align="center"  height="333">
		&nbsp;
		<table border="0" cellpadding="0" cellspacing="0" width="90%" height="303">
		  <tr>
			<td width="100%" height="22">&nbsp;
			  
			</td>
		  </tr>
	  <tr>
	<td width="100%" height="277" valign="top" align="left" class="lgnbd">
		<form name="dates1" action="reportBottom.php"  method="POST">
		<?php
			for($iLoop=0;$iLoop<count($companyname);$iLoop++)
			{	?>
				<input type="hidden" name="companyname[]" value="<?=$companyname[$iLoop]?>">
		<?php
			} ?>		
		<input type="hidden" name="dateToEnter" value="<?=$dateToEnter?>">
		<input type="hidden" name="dateToEnter1" value="<?=$dateToEnter1?>">
		<input type="hidden" name="page" value="P">
		<input type="hidden" name="period" value="<?=$trans_period?>">
		<input type="hidden" name="txtDate" value=<?=$HTTP_POST_VARS["txtDate"]?>>
		<input type="hidden" name="txtDate1" value=<?=$HTTP_POST_VARS["txtDate1"]?>>
		<input type="hidden" name="querycc">
		<input type="hidden" name="crorcq" value="<?=$crorcq?>">
		<input type="hidden" name="trans_ptype" value="<?=$trans_pending?>">
		<input type="hidden" name="trans_atype" value="<?=$trans_approved?>">
		<input type="hidden" name="trans_dtype" value="<?=$trans_declined?>">
		<input type="hidden" name="trans_ctype" value="<?=$trans_canceled?>">
		<input type="hidden" name="settobilldate" value="<?=$trans_billdate?>">		
		<input type="hidden" name="orderentry" value="<?=$trans_orderentry?>">
		<input type="hidden" name="querystrN" >
		<input type="hidden" name="transactionId" value="<?=$transactionId?>">
		<input type="hidden" name="companyname" value="<?=$companyname?>">

				
	
		
				
		<table width="100%">		
		<tr><td  width="100%" valign="bottom" align="right" height="20">
		<select name="noitems" style="font-family:verdana;font-size:10px;WIDTH: 150px" onchange="pagecall()">
<? 
			if($noitems==20)
			{
				$nselect="selected";
			}
			else
			{
				$nselect="";
			}
?>			<option value=20 <?=$nselect?>>20 Records per page</option>
<?         
			$nselect="";
			if($noitems==50)
			{
				$nselect="selected";	
			}
?>
			<option value="50" <?=$nselect?>>50 Records per page</option>
<?
			$nselect="";
			if($noitems==100)
			{
			  $nselect="selected";
			}
?>
			<option value="100" <?=$nselect?>>100 Records per page</option>
			</select>&nbsp;&nbsp;
			</td></tr>
			<tr><td  width="100%" valign="bottom" align="left" >
			<font face="verdana" size="1">Total Amount between  <?=$displaybetween?> is : <b><?=$totalamount?></b></font>
			</td></tr>
			</table>
<?php   
		if($companyname[0] !="A")
		{
			for($iLoop=0;$iLoop<count($companyname);$iLoop++)
			{   echo $companyname[$iLoop];
				$iUserId = $companyname[$iLoop];
				$qrt_select_details1 = $qrt_select_details1." and a.userid=" . $iUserId;
				func_table_body($qrt_select_details1,$cnn_cs,$noitems);	
	
			}
		}
		else
		{
		
			func_table_body($qrt_select_details1,$cnn_cs,$noitems);
		}
	}		
?>	 
	</form>
	</td>
      </tr>
    </table>
    </td>
   </tr>
</table><br>
<?
include 'includes/footer.php';
}



function func_table_body($qrt_select_details1,$cnn_cs,$noitems) {

	if(!($show_select_details =mysql_query($qrt_select_details1,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if(mysql_num_rows($show_select_details )==0 )
	{	
		$headerInclude="transactions";
		$outhtml="y";
		$msgtodisplay="NO transactions for this period";
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();
	}
	$totamount = 0;
	$i=0;
	while($show_select_val = mysql_fetch_array($show_select_details)) 
	{
		$amount=$show_select_val[6]; 
		$totamount=$totamount+$show_select_val[6];
		 if($i < $noitems) 
		 {
				if($i==0)
				{
?>
					<table class='lefttopright' cellpadding='0' cellspacing='0' width='98%'  valign=left  bgColor='#ffffff'  ID='Table1' style=' margin-left: 10;  margin-bottom: 5'>
					<tr height='30' bgcolor='#aebbd2'>
						<td align='left'  class='cl1'><font face='verdana' size='1'><b>Transaction Id</b></font></td>
						<td align='left'   class='cl1' ><font face='verdana' size='1'><b>First name</b></font></td>
						<td align='left'   class='cl1' ><font face='verdana' size='1'><b>Last name</b></font></td>
						<td align='left'   class='cl1' ><font face='verdana' size='1'><b>Type</b></font></td>
						<td  align='left' class='cl1'><font face='verdana' size='1'><b>Amount</b></font></td>
						<td  align='left'  class='cl1'><font face='verdana' size='1'><b>Misc</b></font></td>
						<td align='left'  class='cl1'><font face='verdana' size='1'><b>Pending</b></font></td>
						<td align='left'  class='cl1'><font face='verdana' size='1'><b>Approved</b></font></td>
						<td align='left'  class='cl1'><font face='verdana' size='1'><b>Declined</B></font></td>
						<td align='left'  class='cl1'><font face='verdana' size='1'><b>Cancelled </b></font></td>
						<td align='left'  class='cl1'><font face='verdana' size='1'><b>Cancellation Reason</b></font></td>
					</tr>
<?				} 

				  if($show_select_val[5]=="C")
				  {
					 $ctype="Check";
				  }
				  else
				  {
					$ctype="Creditcard";
				  }
				  if($show_select_val[7]=="") 
				  {
					$misc="&nbsp;";
				  } 
				  else 
				  {
					$misc = $show_select_val[7];
				  }
?>
				<tr height='30' bgcolor='#ffffff'>
				<td align='center'  class='cl1'><font face='verdana' size='1'> 
				<a href="viewreportpage.php?date=<?=$show_select_val[8]?>&%20id=<?=$show_select_val[1]?>" class="link1">&nbsp;<?=$show_select_val[1]?></a></font></td>
				<td align='left' class='cl1' ><font face='verdana' size='1'>&nbsp;<?=$show_select_val[3]?></font></td>
				<td align='left' class='cl1' ><font face='verdana' size='1'>&nbsp;<?=$show_select_val[4]?></font></td>
				<td align='left' class='cl1'><font face='verdana' size='1'>&nbsp;<?=$ctype?></font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;<?=number_format($amount,2)?>&nbsp;</font></td>
				<td align='left' class='cl1'><font face='verdana' size='1'>&nbsp;<?=$misc?></font></td>
<? 		   
					$cval="";
					if($show_select_val[0]=="P")   
					{
						$cval="checked";
					}
?>	
				<td align='center'  class='cl1'><input type="radio" <?=$cval?> name="status[<?=$i?>]" value="P"></input></td>
<? 
					$cval="";
				   if($show_select_val[0]=="A")   
				   {
						$cval="checked";
				   }
?>	
				<td align='center'  class='cl1'><input type="radio" name="status[<?=$i?>]" <?=$cval?> value="A"></input></td>
<? 
				  $cval="";
				  if($show_select_val[0]=="D")  
				  {
						$cval="checked";
				  }
?>	
				<td align='center'  class='cl1'><input type="radio" name="status[<?=$i?>]" value="D" <?=$cval?>></input></td>
<?	 			if($show_select_val[9]=="Y")   
				{
?>	 				<td  align='center'  class='cl1'><input type="checkbox" checked value="<?=$show_select_val[1]?>"   name="cancel[]"></input></td><td class='cl1' align='center'>
					<select name="cancelreason[]" style="font-family:verdana;font-size:10px;WIDTH: 125px">
					<option value="<?=$show_select_val[1]?>">Select reason</option>
<?
					if($show_select_val[10]=="Bank return")
					{
						$selectcanc="selected";
					} 
					else
					{
					   $selectcanc="";
					}
?>			   
					<option value="Bank return" <?=$selectcanc?>>Bank return</option>
<?
					$selectcanc="";
					if($show_select_val[10]=="Customer cancel")
					{
					   $selectcanc="selected";
					} 
?>
					<option value="Customer cancel" <?=$selectcanc?>>Customer cancel</option>
<?
					$selectcanc="";
					if($show_select_val[10]=="Chargeback")
					{
					   $selectcanc="selected";
					} 
?>
					<option value="Chargeback" <?=$selectcanc?>>Chargeback</option>
<?
					$selectcanc="";
					if($show_select_val[10]=="Credit")
					{
					   $selectcanc="selected";
					} 
?>
					<option value="Credit" <?=$selectcanc?>>Credit</option>
<?
					$selectcanc="";
					if($show_select_val[10]=="NSF")
					{
					   $selectcanc="selected";
					} 
?>
					<option value="NSF" <?=$selectcanc?>>NSF</option>
<?
					$selectcanc="";
					if($show_select_val[10]=="Invalid Account #")
					{
					   $selectcanc="selected";
					} 
?>
					<option value="Invalid Account #" <?=$selectcanc?>>Invalid Account #</option>
<?
					$selectcanc="";
					if($show_select_val[10]=="Invalid Account")
					{
					   $selectcanc="selected";
					} 
?>
					<option value="Invalid Account" <?=$selectcanc?>>Invalid Account</option>
<?
					$selectcanc="";
					if($show_select_val[10]=="Invalid Routing #")
					{
					   $selectcanc="selected";
					} 
?>
					<option value="Invalid Routing #" <?=$selectcanc?>>Invalid Routing #</option>
<?
					$selectcanc="";
					if($show_select_val[10]=="Invalid Card")
					{
					   $selectcanc="selected";
					} 
?>
					<option value="Invalid Card" <?=$selectcanc?>>Invalid Card</option>
<?
					$selectcanc="";
					if($show_select_val[10]=="Invalid Card #")
					{
					   $selectcanc="selected";
					} 
?>
					<option value="Invalid Card #" <?=$selectcanc?>>Invalid Card #</option>
<?
					$selectcanc="";
					if($show_select_val[10]=="AVS Return")
					{
					   $selectcanc="selected";
					} 
?>
					<option value="AVS Return" <?=$selectcanc?>>AVS Return</option> 
					</select><input type="text" name="other[<?=$show_select_val[1]?>]" style="font-family:verdana;font-size:10px;WIDTH: 175px" value="<?=$show_select_val[11]?>"></input>
					</td>
<? 
				} 
				else
				{
?>
					<td  align='center'  class='cl1'><input type="checkbox" value="<?=$show_select_val[1]?>"   name="cancel[]"></input></td><td class='cl1' align='center'>
					<select name="cancelreason[]" style="font-family:verdana;font-size:10px;WIDTH: 125px">
					  <option value="">Select reason</option>
					  <option value="Bank Return">Bank Return</option>
					  <option value="Customer cancel">Customer cancel</option>
					  <option value="Chargeback">Chargeback</option>
					  <option value="Credit">Credit</option>
					  <option value="NSF">NSF</option>
					  <option value="Invalid Account #">Invalid Account #</option>
					  <option value="Invalid Account">Invalid Account</option>
					  <option value="Invalid Routing #">Invalid Routing #</option>
					  <option value="Invalid Card">Invalid Card</option>
					  <option value="Invalid Card Number">Invalid Card #</option>
					  <option value="AVS Return">AVS Return</option> 
					</select><input type="text" name="other[]" style="font-family:verdana;font-size:10px;WIDTH: 175px"></input>
					</td>
<?php
				}   
?>					<input type="hidden" value="<?=$show_select_val[1]?>" name="tid[]"></input>
					</tr>
<?			} 
			else 
			{	
?>
				<script language="javascript">
				function hreffill(){							
				document.dates1.querystrN.value="next"
				document.dates1.transactionId.value=<?=$show_select_val[1]?>		                  
				document.dates1.submit()                              
				}
				</script>
<?
				$qrt_select_details=urlencode($qrt_select_details);
				$companyname=urlencode($companyname);
				$nextstr="<a class='link1' href='javascript:hreffill()'><img src='../images/next.jpg' border='0'></a>";
		
	 		}
			$i=$i+1;
		}
?></table>
<?	if($i!= 0)
	{
 		if(!isset($nextstr))
		{
			$nextstr = "";
		}
 ?>
	 <table width="98%" cellspacing="0" cellpadding="0">
	 <tr><td height="50" align="center" valign="middle" width="100%" colspan="2">
	 <a href="#" onclick="window.history.back()"><img   SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>
	 &nbsp;&nbsp;<input type="hidden" name="submittransaction" value="submittransaction"></input>
	 &nbsp;&nbsp;<a href="javascript:func_update();"><img SRC="<?=$tmpl_dir?>/images/submitcompanydetails.jpg" border="0"></a>
	 &nbsp;&nbsp;<?=$nextstr?></td></tr>
	 </table>

<?	}
}
?>