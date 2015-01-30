<?php
	
	include("includes/sessioncheck.php");
	include("includes/header.php");
	$headerInclude="transactions";
	include("includes/topheader.php"); 
	//"SELECT name,surname,recur_mode,recur_times,recur_start_date,userid,rebill_transactionid,recur_charge,recur_date  FROM cs_rebillingdetails where recur_times>=0";
	$numrows=0;
	$id=$_SESSION["sessionlogin"];
//	 $qry_select="SELECT a.name,a.surname,a.recur_mode,a.recur_times,a.recur_start_date,a.userid,a.rebill_transactionid,a.recur_charge,a.recur_date  FROM cs_rebillingdetails as a ,cs_companydetails as b where a.userId=b.userId and ((a.recur_times>=0 and b.gateway_id=-1) and (b.userId=$id and a.company_user_id=0))";
	 $qry_select="SELECT a.name,a.surname,a.recur_mode,a.recur_times,a.recur_start_date,a.userid,a.rebill_transactionid,a.recur_charge,a.recur_date  FROM cs_rebillingdetails as a ,cs_companydetails as b where a.userId=b.userId and ((a.recur_times>=0 ) and (b.userId=$id and a.company_user_id=0))";
	
	//echo $qry_select;
	//echo $id; //exit();
	$result = mysql_query($qry_select);
	$numrows =mysql_num_rows($result);
	
if($numrows!=0){	?>
<form name="frmrecurlist" method="get" action="updaterebillinglist.php" >
<table width="89%" height="131" border="0" align="center" cellpadding="0" cellspacing="0" >
<tr>
	<td height="22" align="left" valign="top" width="3%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
	<td height="22" align="center" valign="middle" width="29%" background="images/menucenterbg.gif" ><span class="whitehd">Recurring&nbsp;Details</span></td>
	  <td height="22" align="left" valign="top" width="6%" nowrap><img border="0" src="images/menutopcurve.gif" width="56" height="22"></td>
	  <td height="22" align="left" valign="top" width="59%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="3%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
</tr>
	<tr>
	<td height="96" colspan="5" class="lgnbd"> 
<table width="100%" height="47" cellpadding="0" cellspacing="0">
<tr valign="middle" bgcolor='#78B6C2' height='18'> 
            <td align='left' class='cl1'><span class="subhd">Ref: number</span></td>
		    <td align='left' class='cl1'><span class="subhd">Name</span></td>
		    <td align='left' class='cl1'><span class="subhd">Surname</span></td>
		    <td align='left' class='cl1'><span class="subhd">Recurring mode</span></td>
		    <td align='left' class='cl1'><span class="subhd">Recur. times</span></td>
		    <td align='left' class='cl1'><span class="subhd">Recur.start date</span></td>
		    <td align='left' class='cl1'><span class="subhd">Recur. next date</span></td>
			<td align='left' class='cl1'><span class="subhd">Recur. charge</span></td>
		    <td align='left' class='cl1'><span class="subhd">Deactivate</span></td>
		</tr>
	
<?php 	for($i=1;$i<=$numrows;$i++) { 
		$value=mysql_fetch_row($result);
		$userid=$value[5];
		//$qry_company="select companyname ,processing_currency from cs_companydetails where userId =$value[5]";
		//$company_res= mysql_query($qry_company);
		//$company_row=mysql_fetch_row($company_res);
		
		$qry_select_details="select a.companyname, a.processing_currency,b.reference_number from cs_companydetails as a,cs_transactiondetails as b where a.userId=$id and b.transactionId=$value[6]";
		$company_res= mysql_query($qry_select_details);
		$details_row=mysql_fetch_row($company_res);
		$refnum=$details_row[2];
		
		/*$qry_ref_no="select reference_number from cs_transactiondetails where transactionId=$value[6]";
		$refno_res= mysql_query($qry_ref_no);
		$ref_row=mysql_fetch_row($refno_res);
		*/
		//print "<tr><td  width='7%' class='cl1'>$value[0] &nbsp;</td></tr>";
		//print "<tr><td  width='7%' class='cl1'>$value[1] &nbsp;</td></tr>";
		//print "<tr><td  width='7%' class='cl1'>$value[2] &nbsp;</td></tr>";
		$mode="";$times="";
		if ($value[3]==0){$times="Infinite";}else {$times=$value[3];}
		if($value[2]=='M'){$mode="Monthly";}
		else if ($value[2]=='Y'){$mode="Yearly";}
		else if ($value[2]=='W'){$mode="Weekly";}
		else  if($value[2]=='D'){$mode="Daily";}
?>
	      <tr align="left" valign="middle"> 
            <td  class="cl1"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
             <a href="editrebilling.php?id=<?=$value[6]?>"> <?=$refnum?></a>
              &nbsp;</font></td>
		   
		    <td  class="cl1"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
              <?=$value[0]?>
              &nbsp;</font></td>
		    <td  class="cl1"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
              <?=$value[1]?>
              &nbsp;</font></td>
		    <td  class="cl1"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
              <?=$mode ?>
              &nbsp;</font></td>
		    <td  class="cl1"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
              <?=$times?>
              &nbsp;</font></td>
		    <td  class="cl1"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
              <?=$value[4]?>
              &nbsp;</font></td>
		    <td  class="cl1"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
              <?=$value[8]?>
              &nbsp;</font></td>
		    <td  class="cl1"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
              <?=$value[7]?>
              &nbsp;</font></td>
		    <td class="cl1"> 
              <input type="checkbox" name="check<?=$i?>"  value="<?=$value[6]?>" ></td>
		
	</tr>
	
<?Php		

}?>
	<tr><td colspan='10' align="center"><input type="image" id="viewcompany" src="images/submit.jpg" width="49" height="20"></input></td></tr>
		</table>
      <!-- Reports ends here -->
      <br></td>
  </tr>	
	
  
<tr>
      <td width="3%"><img src="images/menubtmleft.gif" width="23" height="10"></td>
    <td colspan="3" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
    <td width="3%" ><img src="images/menubtmright.gif" width="16" height="11"></td>
</tr>
<tr><td><input type="hidden" name= "count" value="<?=$numrows?>" ></td></tr>
 </table>
 </form>
 <?php }//mum!=0  
 else {?>
 
 <table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
<tr>
<td width="83%" valign="top" align="center">&nbsp;
<table border="0" cellpadding="0" cellspacing="0" width="50%" >
<tr>
<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
<td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Message</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
 
<table height='50%' width='90%' cellspacing='0' cellpadding='0'>
<tr><td  width='100%'  align='center'><p><font face='verdana' size='3'></font></p>
<table width='400' border='0' cellpadding='0' >
<tr><td align='CENTER' valign='center' height='50' ><font face='verdana' size='1'><?php print "No recurring Transactions"; ?></font>
</td></tr></table></td></tr>
<tr><td height="50" valign="center" align="center">
<a href="#" onclick='javascript:window.history.back()'><img src="images/back.jpg" border="0"></a>
</td></tr></table>

<tr>
<td width="1%"><img src="images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img src="images/menubtmright.gif"></td>
</tr>
</form>
</td></tr>
</table>
</td></tr>
</table>
<?php }?>
<?php
	include("includes/footer.php");
?>