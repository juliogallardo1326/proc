<?php
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
$headerInclude="transactions";	
include 'includes/header.php';


?>
<script language="javascript" src="../scripts/general.js"></script>
<?
$str_fromdate = isset($HTTP_POST_VARS["txtDate"])?$HTTP_POST_VARS["txtDate"]:"";
$str_todate = isset($HTTP_POST_VARS["txtDate1"])?$HTTP_POST_VARS["txtDate1"]:"";
$i_userid = isset($HTTP_POST_VARS["opt_company"])?$HTTP_POST_VARS["opt_company"]:"";
if ($str_fromdate != "")
{
	list ($mm, $dd, $yyyy) = split ('[/.-]', $str_fromdate);
	$str_from_date=$yyyy."-".$mm."-".$dd." 00:00:00";
}
if ($str_todate != "")
{
	list ($mm1, $dd1, $yyyy1) = split ('[/.-]', $str_todate);
	$str_to_date=$yyyy1."-".$mm1."-".$dd1." 23:59:59";
}
$str_condition = "";
$str_text = "Transaction details ";
$qry_select_details = "Select transactionId,transactionDate,userId,phonenumber,voiceAuthorizationno,cancelstatus,status,passStatus from cs_transactiondetails"	;
if ($str_fromdate != "")
{
	$str_text .= " From <strong>".$str_fromdate."</strong>";
	if ($str_condition == "")
	{
		$str_condition .= " Where transactionDate >= '".$str_from_date."'";
	}
	else
	{
		$str_condition .= " AND transactionDate >= '".$str_from_date."'";
	}
}
if ($str_todate != "")
{
	$str_text .= " Till <strong>".$str_todate."</strong>";
	if ($str_condition == "")
	{
		$str_condition .= " Where transactionDate <= '".$str_to_date."'";
	}
	else
	{
		$str_condition .= " AND transactionDate <= '".$str_to_date."'";
	}
}
if ($i_userid != "")
{
	$str_text .= " For <strong>".func_getcompanyname($i_userid,$cnn_cs)."</strong>";
	if ($str_condition == "")
	{
		$str_condition .= " Where userId = ".$i_userid;
	}
	else
	{
		$str_condition .= " AND userId = ".$i_userid;
	}
}
$qry_select_details .= $str_condition." Order by transactionDate Desc";
?>

 <table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
       <td width="100%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="90%" >
      <tr>
        <td width="100%" height="22">
          <table border="0" cellpadding="0" cellspacing="0" width="100%" height="22">
            <tr>
              <td width="17" height="22"><img border="0" SRC="<?=$tmpl_dir?>/images/leftcurve.gif" width="17" height="22"></td>
              <td bgcolor="#1c5abc" >
                <p style="margin-left: 25"><font size="1" face="Verdana" color="#FFFFFF"><b>Transaction Details</b></font></p>
              </td>
              <td width="17" height="22"><img border="0" SRC="<?=$tmpl_dir?>/images/rightcurve.gif" width="17" height="22"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
	      <td width="100%"  valign="top" align="left" style="border:1px solid #1c5abc"> 
		  <form name="frm_transactiondetails"  method="POST" action="transactiondetailsfb.php">
              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr height="30">
					<td colspan="12"><span class="normaltext"><?php print $str_text ;?></span></td>
				</tr>
				<tr bgcolor='#aebbd2'>
					<td align='center'  class='cl1' rowspan="2"><span class="normaltext"><strong>Transaction Id</strong></span></td>
					<td align='center'  class='cl1' rowspan="2"><span class="normaltext"><strong>Company</strong></span></td>
					<td align='center'  class='cl1' rowspan="2"><span class="normaltext"><strong>Phone No:</strong></span></td>
					<td align='center'  class='cl1' rowspan="2"><span class="normaltext"><strong>Authorisation No:</strong></span></td>
					<td align='center'  class='cl1' colspan="6"><span class="normaltext"><strong>Voice Authorisation status</strong></span></td>
					<td align='center'  class='cl1' rowspan="2"><span class="normaltext"><strong>Status</strong></span></td>
					<td align='center'  class='cl1' rowspan="2"><span class="normaltext"><strong>Cancel Status</strong></span></td>
                </tr>
				<tr bgcolor='#aebbd2'>
					<td align='center'  class='cl1'><span class="normaltext"><strong>Pass</strong></span></td>
					<td align='center'  class='cl1'><span class="normaltext"><strong>No pass</strong></span></td>
					<td align='center'  class='cl1'><span class="normaltext"><strong>Pending</strong></span></td>
					<td align='center'  class='cl1'><span class="normaltext"><strong>Incomplete</strong></span></td>
					<td align='center'  class='cl1'><span class="normaltext"><strong>Cancelled</strong></span></td>
					<td align='center'  class='cl1'><span class="normaltext"><strong>&nbsp;</strong></span></td>
					
                </tr>
				<?php
				$rst_select_details = mysql_query($qry_select_details,$cnn_cs);
				$i_count = 0;
				if (mysql_num_rows($rst_select_details)>0)
				{
					for($i=0;$i<mysql_num_rows($rst_select_details);$i++)
					{
						$i_count = $i_count + 1;
						$str_select_pass = "";
						$str_select_nopass = "";
						$str_select_pending = "";
						$str_select_incomplete = "";
						$str_select_cancelled = "";
						$i_transaction_id = mysql_result($rst_select_details,$i,0);
						$str_transaction_date = mysql_result($rst_select_details,$i,1);
						$i_company_id = mysql_result($rst_select_details,$i,2);
						$str_phone = mysql_result($rst_select_details,$i,3);
						$str_auth_no = mysql_result($rst_select_details,$i,4);
						$str_cancelstatus = mysql_result($rst_select_details,$i,5);
						$str_status = mysql_result($rst_select_details,$i,6);
						$str_passstatus = mysql_result($rst_select_details,$i,7);
						if ($str_auth_no == "")
						{
							$str_auth_no = "&nbsp;";
						}
						if ($str_cancelstatus == "Y")
						{
							$str_cancel = "Cancelled";
						}
						else
						{
							$str_cancel = "&nbsp;";
						}
						switch($str_passstatus)
						{
							case "PA" :
								$str_passed = "Passed";
								$str_select_pass = "checked";
								break;
							case "PE" :
								$str_passed = "Pending";
								$str_select_pending = "checked";
								break;
							case "NP" :
								$str_passed = "Not Passed";
								$str_select_nopass = "checked";
								break;
							case "IC" :
								$str_passed = "Incomplete";
								$str_select_incomplete = "checked";
								break;
							case "CA" :
								$str_passed = "Cancelled";
								$str_select_cancelled = "checked";
								break;
							case "ND" :
								$str_passed = "ND";
								$str_select_cancelled = "checked";
								break;
							default: 
								$str_passed = "Pending";
								$str_select_pending = "checked";
						}
						switch($str_status)
						{
							case "P" :
								$str_statustype = "Pending";
								break;
							case "A" :
								$str_statustype = "Approved";
								break;
							case "D" :
								$str_statustype = "Declined";
								break;
							default: 
								$str_statustype = "Pending";
						}
					?>
						<tr height='30'>
							<td class='cl1'><span class="normaltext"><?php print $i_transaction_id ;?></span></td>
							<td class='cl1'><span class="normaltext"><?php print func_getcompanyname($i_company_id,$cnn_cs) ;?></span></td>
							<td class='cl1'><span class="normaltext"><?php print $str_phone ;?></span></td>
							<td class='cl1'><span class="normaltext"><?php print $str_auth_no ;?></span></td>
							<td align='center'  class='cl1'>
							<input name="rd_passstatus_<?php print $i; ?>" type="radio" value="PA" <?php print $str_select_pass; ?>>
							</td>
							<td align='center'  class='cl1'>
							<input name="rd_passstatus_<?php print $i; ?>" type="radio" value="NP" <?php print $str_select_nopass; ?>>
							</td>
							<td align='center'  class='cl1'>
							<input name="rd_passstatus_<?php print $i; ?>" type="radio" value="PE" <?php print $str_select_pending; ?>>
							</td>
							<td align='center'  class='cl1'>
							<input name="rd_passstatus_<?php print $i; ?>" type="radio" value="IC" <?php print $str_select_incomplete; ?>>
							</td>
							<td align='center'  class='cl1'>
							<input name="rd_passstatus_<?php print $i; ?>" type="radio" value="CA" <?php print $str_select_cancelled; ?>>
							</td>
							<td align='center'  class='cl1'><span class="normaltext"><?php print $str_passed ;?></span></td>
							<td align='center'  class='cl1'><span class="normaltext"><?php print $str_statustype ;?></span></td>
							<td align='center'  class='cl1'><span class="normaltext"><?php print $str_cancel ;?></span></font></td>
							<input type="hidden" name="hid_transactionid_<?php print $i; ?>" value="<?php print $i_transaction_id; ?>">
						</tr>	
				<?php
					}
				?>
					<input type="hidden" name="hid_count" value="<?php print $i_count; ?>">
					
				<?php 
				}
				else
				{
					echo "<tr><td colspan='12' class='cl1'><span class='normaltext'>No Records found in the database</span></td></tr>";
				}
				?>
				<input type="hidden" name="txtDate" value="<?php print $str_fromdate; ?>">
				<input type="hidden" name="txtDate1" value="<?php print $str_todate; ?>">
				<input type="hidden" name="opt_company" value="<?php print $i_userid; ?>">
				<tr>
					<td colspan="12" class='cl1' align="right">
						<input name="Update" type="submit" value="Update">
						<input name="Update" type="button" value="Back" onClick="javascript:window.location='override.php';">
					</td>
				</tr>
	          </table>
			</form>
			 </td>
     </tr>
    </table>
    </td>
     </tr>
</table>
<?php
	include 'includes/footer.php';
?>
<?php
function func_getcompanyname($companyid,$cnn_connection)
{
	$str_returnstring="";
	$qry_select = "Select companyname from cs_companydetails where userId = ".$companyid;
	$rst_select = mysql_query($qry_select,$cnn_connection);
	if (mysql_num_rows($rst_select)>0)
	{
		$str_returnstring = mysql_result($rst_select,0,0);
	}
	return $str_returnstring;
}

?>