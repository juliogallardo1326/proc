<?php
include("includes/sessioncheck.php");

$headerInclude = "companies";
include("includes/header.php");


$qrySelect = "select company_type_short,company_type_long,setupfee from cs_setupfee order by setupfee_id";
$rstSelect = mysql_query($qrySelect,$cnn_cs);
for ($i=0;$i<mysql_num_rows($rstSelect);$i++) 
{
	$strCompanyTypeShort =  mysql_result($rstSelect,$i,0);
	$iSetupFee	=	(isset($HTTP_POST_VARS["txt_$strCompanyTypeShort"])?quote_smart($HTTP_POST_VARS["txt_$strCompanyTypeShort"]):"");
	if ( $iSetupFee != "" ) {
		$qryInsert = "update cs_setupfee set setupfee=$iSetupFee where company_type_short='$strCompanyTypeShort'";
		mysql_query($qryInsert,$cnn_cs);
	}
}
?>
<!--<script language="javascript" src="../scripts/calendar1.js"></script>  -->

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
		<table width="50%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Merchant setup fee</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5">
		<br>
		<form name="frmSetup" method="post" action="setupfee.php">
		<table width="100%" border="0" cellspacing="3" cellpadding="3">
		<tr>
            <td align="right" valign="middle"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Setup Fee for</strong></font></td>
		    <td>&nbsp;</td>
		</tr>
<?php
		$qrySelect = "select company_type_short,company_type_long,setupfee from cs_setupfee order by setupfee_id";
		$rstSelect = mysql_query($qrySelect,$cnn_cs);
		for ($i=0;$i<mysql_num_rows($rstSelect);$i++) 
		{
			$strCompanyTypeShort =  mysql_result($rstSelect,$i,0);
			$strCompanyTypeLong =  mysql_result($rstSelect,$i,1);
			$iSetupFee = mysql_result($rstSelect,$i,2);	
?>
		  <tr>
            <td align="right" valign="middle"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?=$strCompanyTypeLong?></font></td>
		    <td>&nbsp; <input type="text" size="10" maxlength="10" name="txt_<?=$strCompanyTypeShort?>" value="<?= $iSetupFee ?>"></td>
		  </tr>
<?php	} ?>		 
		  <tr>
			<td colspan="2" align="center"><input type="image" SRC="<?=$tmpl_dir?>/images/submit.jpg"></td>
		  </tr>
		</table>
		</form>
		<br>
	</td>
 </tr>
<tr>
<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
</tr>
</table><br>
</td>
</tr>
</table>
<?php
include("includes/footer.php");
?>