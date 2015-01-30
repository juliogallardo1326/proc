<?php

include("includes/sessioncheck.php");
$headerInclude="reseller";
include("includes/header.php"); 

$numrows=0;
 $i_reseller_id = isset($HTTP_GET_VARS["reseller_id"])?$HTTP_GET_VARS["reseller_id"]:"";
$qry_selectdetails = "select companyname,transaction_type,email,volumenumber from cs_companydetails where reseller_id = $i_reseller_id";
if (!($rst_selectdetails = mysql_query($qry_selectdetails)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$numrows= mysql_num_rows($rst_selectdetails);
	if($numrows!=0){
?>
<table  width='100%' >
<tr><td  >
<br>
<br>
<table width="86%" height="131" border="0" align="center" cellpadding="0" cellspacing="0" >
<tr>
	      <td height="22" align="left" valign="top" width="2%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="28%" background="../images/menucenterbg.gif" ><span class="whitehd">Merchant&nbsp;Details</span></td>
	      <td height="22" align="left" valign="top" width="8%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="91" height="22"></td>
	      <td height="22" align="left" valign="top" width="56%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	      <td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="6%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
</tr>
	<tr>
	<td height="96" colspan="5" class="lgnbd" > 
<table width="100%" height="47" cellpadding="0" cellspacing="0">
<tr valign="middle" bgcolor='#CCCCCC' height='18'> 
                <td width="6%" align='left' class='cl1'><span class="subhd">No:</span></td>
		       
		    
                <td width="27%" align='left' class='cl1'><span class="subhd">Company 
                  Name</span></td>
		   
		    
                <td width="17%" align='left' class='cl1'><span class="subhd">Merchant 
                  Type</span></td>
		    
                <td width="29%" align='left' class='cl1'><span class="subhd">Email</span></td>
			 
                <td width="21%" align='left' class='cl1'><span class="subhd">Volume</span></td>
        </tr>
			<?Php for($i=1;$i<=$numrows;$i++) { 
			$value=mysql_fetch_row($rst_selectdetails);
			?>
			<tr height ='20'>
			<td valign="middle" class='cl1'><font face="verdana" size="1">&nbsp;<?=$i?></font></td>
			<td valign="middle" class='cl1'><font face="verdana" size="1">&nbsp;<?=$value[0]?></font></td>
			<td valign="middle" class='cl1'><font face="verdana" size="1">&nbsp;<?= func_get_merchant_name($value[1])?></font></td>
			<td valign="middle" class='cl1'><font face="verdana" size="1">&nbsp;<?=$value[2]?></font></td>
			<td valign="middle" class='cl1'><font face="verdana" size="1">&nbsp;<?=$value[3]?></font></td>
		
			</tr>
		<?Php }?> 
		<tr><td>&nbsp;</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td colspan='5' align="center" ><a href="javascript:history.back()"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a> </td></tr>
		</table>
      <!-- Reports ends here -->
      <br></td>
  </tr>	
	
  
<tr>
          <td width="2%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif" width="20" height="10"></td>
    <td colspan="3" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="10" height="11"></td>
          <td width="6%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif" width="16" height="11"></td>
</tr>

 </table>
</td></tr> </table>
<?php } else {?>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
<tr>
<td width="83%" valign="top" align="center">&nbsp;
<table border="0" cellpadding="0" cellspacing="0" width="50%" >
<tr>
<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Message</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
 
<table height='50%' width='90%' cellspacing='0' cellpadding='0'>
<tr><td  width='100%'  align='center'><p><font face='verdana' size='3'></font></p>
<table width='400' border='0' cellpadding='0' >
<tr><td align='CENTER' valign='center' height='50' ><font face='verdana' size='1'><?php print "No Merchants For This Reseller"; ?></font>
</td></tr></table></td></tr>
<tr><td height="50" valign="center" align="center">
<a href="#" onclick='javascript:window.history.back()'><img SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>
</td></tr></table>

<tr>
<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
</tr>
</form>
</td></tr>
</table>
</td></tr>
</table>
<?php }?>