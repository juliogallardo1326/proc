<?php

include("includes/sessioncheck.php");

$headerInclude="lettertemp";	
include("includes/header.php");

include("includes/message.php");
?>
<script language="JavaScript">
function funcEdit(iTemplateId){
	window.open("editor/editor.php?id="+iTemplateId,null,"height=500,width=820,status=yes,toolbar=no,menubar=no,location=no,scrollbars=0");
}
function funcDelete(iTemplateId){
	if(window.confirm("Are you sure you want to delete this template?")) {
		var objForm = document.mailupload;
		objForm.action = "deleteLetterTemplate.php?templateId="+iTemplateId;	
		objForm.submit();
	}
}
function func_add(objForm)
{
	if(objForm.txtTemplate.value == ""){
		alert("Please enter template name");
		objForm.txtTemplate.focus();
		return false;
	}else{
		objForm.action = "templateaddsave.php";
		objForm.method = "post";
		objForm.submit();
	}
}

</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
		<table border="0" cellpadding="0" cellspacing="0" width="50%">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Letter&nbsp;Template</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5">
		<form name="mailupload" action="#" method="POST">
              <table width="100%" cellpadding="4" cellspacing="0" border="1">
                <tr>
                  <td align="center" valign="middle"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Template 
                    Name</font></strong></td>
				   
                  <td align="center" valign="middle"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Action</strong></font></td>
                </tr>
				<?php
					$qrySelect = "select * from cs_mailtemplate where 1 ";
					$rstSelect = mysql_query($qrySelect,$cnn_cs);
					if(mysql_num_rows($rstSelect)>0){
						for($iLoop=0;$iLoop<mysql_num_rows($rstSelect);$iLoop++){
							$strTemplateId = mysql_result($rstSelect,$iLoop,0);
							$strTemplateName = mysql_result($rstSelect,$iLoop,1); ?>
							<tr>
                			 <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?= $strTemplateName ?></font></td>
							 <!--<td align="center"><a href="javascript:funcEdit(<?= $strTemplateId ?>)" class="forgotlink">Edit</a></td>-->
							 <td align="center"><a href="javascript:funcDelete(<?= $strTemplateId ?>)" class="forgotlink">Delete</a></td>
							 </tr>
<?php					}
					}				?>
              </table>
			  <br><br>
			  <table width="90%" align="center" cellpadding="0" cellspacing="0">
                <Tr>
                  <td height="30"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Template 
                    Name</font></td>
					<td><input type="text" name="txtTemplate" value="" size="30" maxlength="100"></td>
				</Tr>
				<tr>
					<td colspan="2" align="center" height="30"><input type="button" value="Add Template" onClick="func_add(document.mailupload)"></td>
				</tr>
			  
			  </table>
            </form>
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

