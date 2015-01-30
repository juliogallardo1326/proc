<?php

require_once("includes/sessioncheck.php");

$disablePostChecks=true;
$headerInclude = "mail";
require_once("includes/header.php");
require_once("../includes/languages.php");
require_once("../includes/html2text.php");
require_once("../includes/function2.php");
$et_language=quote_smart($_REQUEST['et_language']);
$et_custom_id=quote_smart($_REQUEST['et_custom_id']);
$et_name = quote_smart($_REQUEST['et_name']);

$Email_Catagory = func_get_enum_values('cs_email_templates','et_catagory');
$et_catagory = $_POST['et_catagory'];
if(!$et_catagory) $et_catagory = 'Merchant';
$et_catagory_sql = "";
if($et_catagory) $et_catagory_sql = " AND `et_catagory` = '$et_catagory' ";
if($et_name && $_POST['Submit'] && $_POST['et_id'])
{

	$sql = "UPDATE `cs_email_templates` set 
	et_to = '".quote_smart($_POST['et_to'])."', 
	et_title = '".quote_smart($_POST['et_title'])."', 
	et_to_title = '".quote_smart($_POST['et_to_title'])."', 
	et_textformat = '".quote_smart($_POST['et_textformat'])."', 
	et_htmlformat = '".quote_smart($_POST['et_htmlformat'])."', 
	et_from='".quote_smart($_POST['et_from'])."', 
	et_subject='".quote_smart($_POST['et_subject'])."', 
	et_from_title='".quote_smart($_POST['et_from_title'])."' 
	where 
	et_id = '".quote_smart($_POST['et_id'])."'";
	$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
}


?>
<script type="text/javascript" src="../fckedit/fckeditor.js"></script>
<script type="text/javascript" language="javascript">
	function updateText()
	{
		value = FCKeditorAPI.__Instances['et_htmlformat'].GetHTML();
		value=value.replace(/\r?\n/g,"");
		value=value.replace(/  /g," ");
		var re= /<\/tr>/g; 
		value = value.replace(re,"\n"); 
		var re= /<\/td>/g; 
		value = value.replace(re,"\t");
		var re= /<\/li>/g; 
		value = value.replace(re,"\n");
		var re= /<\/p>/g; 
		value = value.replace(re,"\n\n");
		var re= /<li>/g; 
		value = value.replace(re,"-"); 
		var re= /<br\/>/g;
		value = value.replace(re,"\n"); 
		var re= /<br>/g; 
		value = value.replace(re,"\n"); 
		var re= /&nbsp;/g; 
		value = value.replace(re," "); 
		var re= /<\S[^>]*>/g; 
		value = value.replace(re,""); 
		document.getElementById('et_textformat').value = value; 
	}
	function autoFill()
	{
		if(document.getElementById('autofill').checked) updateText();
	}
	function toggleCheck()
	{
		document.getElementById('autofill').checked=!document.getElementById('autofill').checked;
	}
</script>

<table border="0" align="center" cellpadding="0" width="98%" cellspacing="0" height="80%">
  <tr>
    <td width="100%" valign="top" align="center"  >&nbsp;
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
            <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Select Email Templates </span></td>
            <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
            <td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
            <td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
          </tr>
          <tr>
            <td class="lgnbd" colspan="5" align="center"><form action="email_edit.php"  id="selectEmail" name="selectEmail" method="post">
			
			  <select name="et_catagory" id="et_catagory" onChange="javascript:document.getElementById('selectEmail').submit()">
                <?=$Email_Catagory?>
              </select><BR>
			  <script language="javascript" >document.getElementById('et_catagory').value = '<?=$et_catagory?>';</script>
			  <select name="et_name" id="et_name" size="5" onChange="javascript:document.getElementById('selectEmail').submit()">
			<?php
			func_fill_combo_conditionally("select et_name, et_title from `cs_email_templates` where 1 $et_catagory_sql GROUP BY `et_name` ORDER BY `et_name` ASC ",$et_name,$cnn_cs);
			?>
			</select>
              <select name="et_language" size="5" id="et_language" onChange="document.getElementById('et_custom_id').selectedIndex=-1;document.getElementById('selectEmail').submit()">
				<?php func_fill_combo_conditionally("select et_language as value, et_language as output from `cs_email_templates` where  et_name = '$et_name' and et_custom_id is null ",$et_language,$cnn_cs); ?>

              </select>
              <select name="et_custom_id" size="5" id="et_custom_id" onChange="document.getElementById('et_language').selectedIndex=-1;document.getElementById('selectEmail').submit()">
				<?php func_fill_combo_conditionally("select et_custom_id, et_title from `cs_email_templates` where  et_name = '$et_name' and et_custom_id is not null order by et_title ",$et_custom_id,$cnn_cs); ?>

              </select>
              <br>
            <a href="javascript:document.getElementById('selectEmail').submit()">View/Edit</a>
            </form></td>
          </tr>
          <tr>
            <td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
            <td colspan="3" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
            <td width="3%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
          </tr>
        </table>
        <?php
		if(!$et_language) $et_language='eng';
		
		$custom_id_sql_orderby = "";
		$custom_id_sql_where = "";
		if($et_custom_id) 
		{
			$custom_id_sql_orderby = "(`et_custom_id` = '$et_custom_id') DESC, ";
			$custom_id_sql_where = "and (`et_custom_id` = '$et_custom_id' OR `et_custom_id` is null)";
		} 
		
		$lang_sql = "(`et_language` = '$et_language') DESC";
		
		$sql = "select * from `cs_email_templates` where et_name = '$et_name' $custom_id_sql_where order by $custom_id_sql_orderby $lang_sql Limit 1";
		
		$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
		if (mysql_num_rows($result))
		{
		$emailInfo = mysql_fetch_assoc($result);
		
		if ($_POST['Submit']=='Generate PlainText')
		{
		 $asciiText = new Html2Text ($emailInfo['et_htmlformat'], 900); // 900 columns maximum
 		 $emailInfo['et_textformat']= $asciiText->convert();
		}
		?>
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
            <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Edit Email Templates </span></td>
            <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
            <td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
            <td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
          </tr>
          <tr>
            <td class="lgnbd" colspan="5" align="center"><form action="email_edit.php"  id="editEmail" name="editEmail" method="post">
<table width="100%" border="1" class="report">
  <tr class='row1'>
    <th scope="col">Email Title: </th>
    <th scope="col"><?=$emailInfo['et_title']?> (in <?=$etel_languages[$et_language]['name']?>)<BR /><span class="small"><?=$emailInfo['et_name']?></span>
      <input type="hidden" name="et_name" value="<?=$emailInfo['et_name']?>" >
      <input type="hidden" name="et_catagory" value="<?=$emailInfo['et_catagory']?>" >
      <input type="hidden" name="et_language" value="<?=$emailInfo['et_language']?>" >
      <input type="hidden" name="et_custom_id" value="<?=$emailInfo['et_custom_id']?>" >
      <input type="hidden" name="et_id" value="<?=$emailInfo['et_id']?>" >
      <a id="editor" name="editor"></a></th>
  </tr>
  <tr class='row2'>
    <td>Edit Title  </td>
    <td><input name="et_title" type="text" id="et_title" value="<?=$emailInfo['et_title']?>" size="60"></td>
  </tr>
  <tr class='row2'>
    <td>To Email: </td>
    <td><input name="et_to" type="text" id="et_to" value="<?=$emailInfo['et_to']?>" size="60"> 
	<br><span class="small">*Leave as [email] to send to customer/merchant/user</span>
	  </td>
  </tr>
  <tr class='row1'>
    <td>To Name: </td>
    <td><input name="et_to_title" type="text" id="et_to_title" value="<?=$emailInfo['et_to_title']?>" size="60">
	<br><span class="small">*Leave as [full_name] if not sure</span></td>
  </tr>
  <tr class='row2'>
    <td>From Email: </td>
    <td><input name="et_from" type="text" id="et_from" value="<?=$emailInfo['et_from']?>" size="60"></td>
  </tr>
  <tr class='row1'>
    <td>From Name: </td>
    <td><input name="et_from_title" type="text" id="et_from_title" value="<?=$emailInfo['et_from_title']?>" size="60"></td>
  </tr>
  <tr class='row2'>
    <td>Subject: </td>
    <td><input name="et_subject" type="text" id="et_subject" value="<?=$emailInfo['et_subject']?>" size="60"></td>
  </tr>
  <tr class='row1'>
    <th scope="col">Avaliable Variables: </th>
    <th scope="col"><?=$emailInfo['et_vars']?></th>
  </tr>
  <tr class='row2'>
    <th scope="row">Email HTML</th>
    <td>
	<textarea name="et_htmlformat" id="et_htmlformat" cols="80"><?=stripslashes($emailInfo['et_htmlformat'])?></textarea>
<script type="text/javascript">
<!--
// Automatically calculates the editor base path based on the _samples directory.
// This is usefull only for these samples. A real application should use something like this:
// oFCKeditor.BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
var sBasePath = '../fckedit/';
var oFCKeditor = new FCKeditor( 'et_htmlformat','100%','460' ) ;//( instanceName, width, height, toolbarSet, value )
oFCKeditor.BasePath	= sBasePath ;
oFCKeditor.ReplaceTextarea() ;

//self.setInterval('autoFill()', 1000);
//-->
</script>
</td>
  </tr>
    <tr class='row1'>
    <th scope="row">&nbsp;</th>
    <td><input type="submit" name="Submit" value="Generate PlainText" tabindex="5"></td>
    </tr>
  <tr class='row2'>
    <th scope="row">Email PlainText </th>
    <td><textarea name="et_textformat" cols="80" rows="12" id="et_textformat"><?=stripslashes($emailInfo['et_textformat'])?></textarea></td>
  </tr>
  <tr class='row1'>
    <th scope="col">Avaliable Variables: </th>
    <th scope="col"><?=$emailInfo['et_vars']?></th>
  </tr>
</table>                
<br>
                <input type="submit" name="Submit" value="Update" tabindex="1">
                <input type="reset" name="Reset" value="Reset" tabindex="2">
            </form></td>
          </tr>
          <tr>
            <td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
            <td colspan="3" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
            <td width="3%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
          </tr>
        </table>
		<?php
		}
		?>
        <br>
        <br>
    </td>
  </tr>
</table>
<script language="javascript">
document.getElementById('et_name').focus();
</script>
<?php



include("includes/footer.php");
?>