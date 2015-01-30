<?php 
	include("includes/sessioncheck.php");
	
	$headerInclude = "companies";
	include("includes/header.php");
	
	$msgtodisplay = (isset($HTTP_GET_VARS['msg'])?quote_smart($HTTP_GET_VARS['msg']):"");
	$msgtodisplay_1 = (isset($HTTP_GET_VARS['msg_1'])?quote_smart($HTTP_GET_VARS['msg_1']):"");
	$msgcompanyid = (isset($HTTP_GET_VARS['msgid'])?quote_smart($HTTP_GET_VARS['msgid']):"");
	$msg_sub=substr_count($msgtodisplay,"}");
	$msg_1=explode("}",$msgtodisplay);
	//echo $msgtodisplay;
	
?>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="50%" >
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Transaction Summary</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
	</tr>      
	<tr>
          <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5"> 
            <table align="center" cellpadding="8" cellspacing="0" width="100%" height="100%" border="0">
              <!--DWLayoutTable-->
              <form name="frmsummary" action="completeAccounting.php" method="GET">
			 
			  <?php if($msgtodisplay_1==""){
			   for($i=1;$i<=$msg_sub;$i++){?>
                <tr> 
                  <td width="50%" height="2" align="center" valign="middle" ><font size="1" face="Verdana" > 
                    <?=$msg_1[$i];?></font>
                  </td>
                </tr>
				<?php }
				}else{?>
					<tr> 
                  	<td width="100%" height="2" align="center" valign="middle"><font size="1" face="Verdana" > 
                    <?=$msgtodisplay_1;?></font>
                  	</td>
                	</tr>
				<?php } ?>
				
                <tr> 
                  <td height="38" align="center" valign="top"> <input name="image" type="image" src="images/back.jpg" alt="View"> 
                  </td>
                  </tr>
				  <input type="hidden" name="company_id" value="<?=$msgcompanyid?>">
              </form>
            </table></td>
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
?>     