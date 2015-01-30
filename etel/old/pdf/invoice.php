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
//ledger.php:	The admin page functions for selecting the type of report view  for the company. 
include("includes/sessioncheck.php");

require_once("includes/function.php");
include("includes/header.php");
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$headerInclude="reports";
$periodhead="Ledgers";
include("includes/topheader.php");

if($sessionAdmin!="")
{
/*	$dayVal=date("d");
	$monthVal=date("n");
	$yearVal=date("Y");
	$ddCur=date("d");
	$mmCur=date("n");
	$yyyyCur=date("Y");
	$dateval2=$mmCur."/".$ddCur."/".$yyyyCur;
*/
?>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="50%" >
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Invoice</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
	</tr>      
	<tr>
        <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
		<table align="center" cellpadding="8" cellspacing="0" width="100%" height="100%" border="1">  
		  
				<form name="ledger" action="invoiceReport.php" method="POST">
					<?php
						$qrySelect	= "select * from cs_invoice_setup where company_id = $sessionAdmin";
						$rstSelect	=	mysql_query($qrySelect,$cnn_cs);
						if ( mysql_num_rows($rstSelect)>0 ) {
							$iFrequesncy		=	mysql_result($rstSelect,0,2);
							$iNumberOfDaysBack	=	mysql_result($rstSelect,0,3);
							$iFromWeekDay		=	mysql_result($rstSelect,0,4);
							$iToWeekDay			=	mysql_result($rstSelect,0,5);
							$iMiscFee			=	mysql_result($rstSelect,0,6);
							$sFre	=	(($iFrequesncy == "D")?("Daily"):(($iFrequesncy == "W") ? ("Weekly") : (($iFrequesncy == "M") ? ("Monthly"):"")));
							?>					
								<tr>
									<td align="right" width="50%"><font face="Verdana, Arial, Helvetica, sans-serif" size="-2">Frequency</font></td>
									<td><font face="Verdana, Arial, Helvetica, sans-serif" size="-2"><?= $sFre ?></font></td>
								</tr>
								<tr>
									<td align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="-2">Number of days back</font></td>
									<td><font face="Verdana, Arial, Helvetica, sans-serif" size="-2"><?= $iNumberOfDaysBack ?></font></td>
								</tr>
								<tr>
									<td align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="-2">From week day</font></td>
									<td><font face="Verdana, Arial, Helvetica, sans-serif" size="-2"><?= funcGetWeekDays($iFromWeekDay) ?></font></td>
								</tr>
								<tr>
									<td align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="-2">To week day</font></td>
									<td><font face="Verdana, Arial, Helvetica, sans-serif" size="-2"><?= funcGetWeekDays($iToWeekDay) ?></font></td>
								</tr>
								<tr>
									<td colspan="2" align="center">
									 <?php
		  								if ( trim($_SESSION["sessionactivity_type"]) != "Test Mode" ) {
									  ?> 
									<input type="image" src="images/view.jpg" alt="View">
									<?php
										} else {
											print("<font size=\"1\" face=\"Verdana\" color=\"Red\">Only active companies can view invoice.</font>");
										}
									?>	
									</td>
								</tr>
								<input type="hidden" value="<?= $sessionAdmin ?>" name="companyname">
								<input type="hidden" value="<?= $iFrequesncy ?>" name="frequency">
								<input type="hidden" value="<?= $iNumberOfDaysBack ?>" name="num_days_back">
								<input type="hidden" value="<?= $iFromWeekDay ?>" name="from_week_day">
								<input type="hidden" value="<?= $iToWeekDay ?>" name="to_week_day">
								<input type="hidden" value="<?= $iMiscFee ?>" name="misc_fee">
							<?php
						} else {
					?>	
						<tr>
                        <td align="center"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif">Sorry! 
                          the site administrator did not set the values for invoice 
                          yet. <br>
                          Please contact etelegate site administrator.</font> <br><br>
                        </td>
						</tr>			
<?php				} 
					?>			
				</form>
		</table>
	</td>
 </tr>
<tr>
<td width="1%"><img src="images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img src="images/menubtmright.gif"></td>
</tr>
</table>
</td>
</tr>
</table>
	
<?php
include("includes/footer.php");
}		
//--------	Function for mapping week days ------------
//-----------------------------------------------------
function funcGetWeekDays($iWeekId) {
	$arrWeekDays[1] = "Monday";
	$arrWeekDays[2] = "Tuesday";
	$arrWeekDays[3] = "Wednesday";
	$arrWeekDays[4] = "Thursday";
	$arrWeekDays[5] = "Friday";
	$arrWeekDays[6] = "Saturday";
	$arrWeekDays[7] = "Sunday";
	return($arrWeekDays[$iWeekId]);
}
?>