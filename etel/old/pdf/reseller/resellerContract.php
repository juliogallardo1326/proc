<?php
	include("includes/sessioncheck.php");
$headerInclude="startHere";
	include("includes/header.php");
	
	

$contract = genResellerContract(&$resellerInfo);
$content = $contract['et_htmlformat'];
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="60%">
    <tr>
   		 <td width="100%" valign="top" align="center">&nbsp;
		 <form name="ResellerContract" action="submitContract.php" method="post">
		   <table  border="0" cellspacing="0" cellpadding="0" width="795" height="75">
             <tr>
               <td width="793" colspan="2"><p class="disctxhd">
                 <?php if ($printable_version) { ?>
                 <img src="images/print.gif" alt=" " width="23" height="22" border="border" onclick="window.print();" />
                 <?php } ?>
  &nbsp;Reseller Contract.</p>
                 <?=$content?></td>
             </tr>

             <tr>
               <td height="21"><p><br />
                              </p></td>
               <td width="793" height="21" align="left"class="bentx"><div align="center"></div>
                 <p align="center"><br />
                     <input type="checkbox" name="schedule_participant_sign" value="1" <?=($companyInfo['en_info']['Reseller']['Signed_Contract']?'checked':'')?> />
                 Signed for and on behalf of<br />
                 (Affiliate Participant)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
                 <br />
                 </p>
                   <p>&nbsp;</p>
                 <p>&nbsp; </p></td>
               <br />
             </tr>
             <tr>
               <td align="center" valign="middle" width="793" height="40" colspan="2"><a href="javascript:window.history.back();"><img src="../images/back.jpg" alt=" " border="0" /></a>&nbsp;&nbsp;
                   <input name="image" type="image" src="../images/continue.gif" /></td>
             </tr>
           </table>
		 </form>
          <p><br>
          </p>
	 </td>
  	</tr>
	</table>
<?php
	include("includes/footer.php");
?>