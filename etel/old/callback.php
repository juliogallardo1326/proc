<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
// CompanyUser.php:	The admin page functions for selecting the company for adding company user. 
include("includes/sessioncheck.php");

require_once("includes/function.php");
include("includes/header.php");
$headerInclude = "customerservice";
include("includes/topheader.php");
include("includes/message.php");
?>
<?php
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
	
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?Trim($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?Trim($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?Trim($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?Trim($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?Trim($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?Trim($HTTP_POST_VARS["opt_to_day"]):$i_to_day);

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2);
	
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?Trim($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?Trim($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?Trim($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?Trim($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?Trim($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?Trim($HTTP_POST_VARS["opt_to_day"]):$i_to_day);

$str_report = (isset($HTTP_POST_VARS["hid_report"])?Trim($HTTP_POST_VARS["hid_report"]):"");


$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day." 00:00:00";
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day." 23:59:59";
//$i_count =  func_sel_count($str_from_date,$str_to_date);
?>
<form name="frm_enquires" action="callback.php" method="post">
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="55%" align="center">
  <tr>
       <td width="95%" valign="top" align="center">
<?php
			 
			 if ($str_report == "")
		  	 {			  
?>
			<table width="50%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
			      <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Call Back</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5">
			<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="10">  
			<tr>
			   <td   height="10"  valign="middle" align="center" width="50%">
		  </td>
			</tr>
			<tr>
			<td   height="50"  valign="middle" align="center" width="50%"> 
			 <font face="Verdana" size="1">Start Date</font>
		   <select name="opt_from_month" style="font-size:10px">
			<?php func_fill_month($i_from_month); ?>
		   </select>
			<select name="opt_from_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_from_day); ?>
		   </select>
		     <select name="opt_from_year" style="font-size:10px">
			<?php func_fill_year($i_from_year); ?>
		   </select>
		   </td></tr>
		   <tr>
		    <td height="70"  valign="middle" align="center"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font face="Verdana" size="1">End Date</font>
              <select name="opt_to_month" class="lineborderselect" style="font-size:10px">
			<?php func_fill_month($i_to_month); ?>
		  </select>
		  <select name="opt_to_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_to_day); ?>	
		  </select>
		  <select name="opt_to_year" class="lineborderselect" style="font-size:10px">
			<?php func_fill_year($i_to_year); ?>
		  </select>
		  &nbsp;&nbsp;&nbsp;<br><br><input type="image" id="viewcompany" src="images/view.jpg"></input>
		  </td>
		  </tr>		  		  
		</table> 
		</td>
		</tr>
		<tr>
		<td width="1%"><img src="images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="images/menubtmright.gif"></td>
		</tr>
		</table>
<?php
}
 if ($str_report != "" )
 {
			if ($str_from_date != "")
					{
					   $qry_select="Select A.callBackId,A.userid,A.transactionid,A.dateandtime,B.phonenumber from cs_callback A,cs_transactiondetails B where B.transactionid = A.transactionid and A.userid = ".$_SESSION["sessionlogin"]." and A.dateandtime  >= '".$str_from_date."'";
					   if($str_to_date != "")
						{
						$qry_select .= " AND A.dateandtime  <= '".$str_to_date."'";
						}
						$qry_select .= " Order by A.dateandtime asc";
						$rssel_report = mysql_query($qry_select);
						$i_count = mysql_num_rows($rssel_report);
						//print($qry_select);
						if ($i_count==0)
						{
							$msgtodisplay="No Reports for this period.";
							$outhtml="y";				
							message($msgtodisplay,$outhtml,$headerInclude);									
							exit();	   
						}
						if (mysql_num_rows($rssel_report)>0)
						{
?>
			<table width="49%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
			      <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Call Back Details</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5">
			  <table width="100%" cellspacing="1" cellpadding="1" border="0" align="center"  class="lgnbd">

                <tr>
	              <td colspan="4"><font size="1" face="Verdana"><strong>Total Records :
                    <?=$i_count; ?></strong></font>
                  </td>
			   </tr>	  
				 <tr>
		          <td width="1%" bgcolor="#78B6C2" height="30"><span class="subhd">No.</span></td>
		          <td width="10%" bgcolor="#78B6C2"><span class="subhd">Transaction 
                    ID</span></td>
		          <td width="10%" bgcolor="#78B6C2"><span class="subhd">Phone 
                    Number</span></td>
		          <td width="15%" bgcolor="#78B6C2"><span class="subhd">Call 
                    DateTime</span></td>
			 </tr>
<?					
					  for($i=0;$i<mysql_num_rows($rssel_report);$i++)
					  {
					  	$i_callback_id = mysql_result($rssel_report,$i,0);
						$i_transid = mysql_result($rssel_report,$i,2);
						$str_calldatetime = mysql_result($rssel_report,$i,3);					
						$str_phonenumber = mysql_result($rssel_report,$i,4);					
				?>
		     
		 <tr>
		 <td bgcolor="#E2E2E2" height="30"><font size="1" face="Verdana" ><?=($i+1); ?></font></td>
		 <td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$i_transid; ?></font></td>
		 <td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$str_phonenumber; ?></font></td>
		 <td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$str_calldatetime; ?></font></td>
		 </tr>
		 <?php
		    }			
		?>
		
		</table>							
		<br>
		</td>
		</tr>
		<tr>
		<td width="1%"><img src="images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="images/menubtmright.gif"></td>
		</tr>
		</table>							
<?php
	           }
		 }  
}				 
?>
   </td>
  </tr>
</table> 
<input type="hidden" name="hid_report" value="report">
	</form>

<?php
include("includes/footer.php");
?>