<?php /* Smarty version 2.6.2, created on 2006-10-25 00:01:50
         compiled from post.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'post.tpl.html', 71, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br />

<?php if ($this->_tpl_vars['no_projects']): ?>
<table width="500" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td class="default">
            <b>Sorry, but there are no projects currently setup as allowing anonymous posting.</b>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php else: ?>
  <?php if ($this->_tpl_vars['new_issue_id'] != "" && $_POST['report_stays'] != 'yes'): ?>
<table width="500" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td class="default">
            <b>Thank you, the new issue was created successfully. For your records, the new issue ID is <font color="red"><?php echo $this->_tpl_vars['new_issue_id']; ?>
</font></b>
            <br /><br />
            You may <a class="link" href="<?php echo $_SERVER['PHP_SELF']; ?>
">submit another issue</a> if you so wish.
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
  <?php else: ?>
    <?php if ($_GET['post_form'] != 'yes'):  echo '
<script language="JavaScript">
<!--
function validateForm(f)
{
    if (hasSelected(f.project, -1)) {
        alert(\'Please choose the project that this new issue will apply to.\');
        selectField(f, \'project\');
        return false;
    }
    return true;
}
//-->
</script>
'; ?>

<table width="80%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
<form name="report_form" action="<?php echo $_SERVER['PHP_SELF']; ?>
" method="get" onSubmit="javascript:return validateForm(this);">
<input type="hidden" name="post_form" value="yes">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td colspan="2" class="default">
            <b>Report New Issue</b>
          </td>
        </tr>
        <tr>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Project: *</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <select name="project" class="default">
              <option value="-1">Please choose a project</option>
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['projects']), $this);?>

            </select>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'project')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
            <input class="button" type="submit" value="Next &gt;&gt;">&nbsp;&nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
<?php echo '
<script language="JavaScript">
<!--
window.onload = setFocus;
function setFocus()
{
    document.report_form.project.focus();
}
//-->
</script>
'; ?>

    <?php else:  echo '
<script language="JavaScript">
<!--
var required_custom_fields = new Array();
var custom_fields = new Array();
function validateForm(f)
{
    if (isWhitespace(f.summary.value)) {
        errors[errors.length] = new Option(\'Summary\', \'summary\');
    }
    if (isWhitespace(f.description.value)) {
        errors[errors.length] = new Option(\'Description\', \'description\');
    }
    checkRequiredCustomFields(f, required_custom_fields);
}
//-->
</script>
'; ?>

<table width="80%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
<form name="report_form" action="<?php echo $_SERVER['PHP_SELF']; ?>
" method="post" enctype="multipart/form-data" onSubmit="javascript:return checkFormSubmission(this, 'validateForm');">
<input type="hidden" name="cat" value="report">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td colspan="2" class="default">
            <b>Report New Issue</b>
          </td>
        </tr>
        <tr>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Project:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <b><?php echo $this->_tpl_vars['project_name']; ?>
</b>
            <input type="hidden" name="project" value="<?php echo $_GET['project']; ?>
">
          </td>
        </tr>
        <tr>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Summary: *</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <input type="text" name="summary" class="default" size="60" tabindex="1">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'summary')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Description: *</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <textarea name="description" style="width: 97%" rows="10" tabindex="2"></textarea>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'description')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "edit_custom_fields.tpl.html", 'smarty_include_vars' => array('custom_fields' => $this->_tpl_vars['custom_fields'],'form_type' => 'anonymous')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <tr>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Attach Files:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <table width="100%" cellpadding="2" cellspacing="0" id="file_table">
              <tr>
                <td>
                  <input type="file" name="file[]" size="40" class="shortcut" tabindex="3" <?php if ($this->_tpl_vars['user_agent'] == 'ie'): ?>onChange="javascript:addFileRow();"<?php endif; ?>>
                </td>
              </tr>
            </table>
            <?php echo '
            <script language="">
            <!--
            if (document.all) {
                var fileTable = document.all[\'file_table\'];
            } else if (!document.all && document.getElementById) {
                var fileTable = document.getElementById(\'file_table\');
            }
            function addFileRow()
            {
                if (!fileTable) {
                    return;
                }
                rows = fileTable.rows.length;
                newRow = fileTable.insertRow(rows);
                cell = newRow.insertCell(0);
                cell.innerHTML = \'<input class="shortcut" size="40" type="file" name="file[]" onChange="javascript:addFileRow();">\';
            }
            //-->
            </script>
            '; ?>

          </td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td width="10" nowrap class="default_white">
                  <nobr>
                  <input type="checkbox" name="report_stays" value="yes" tabindex="4"> <b><a id="white_link" class="white_link" href="javascript:void(null);" onClick="javascript:toggleCheckbox('report_form', 'report_stays');">Keep Form Open</a></b>
                  </nobr>
                </td>
                <td width="100%" align="center">
                  <input class="button" type="submit" value="Submit" tabindex="5">&nbsp;&nbsp;
                  <input class="button" type="reset" value="Reset" tabindex="6">
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="default">
            <b>* Required fields</b>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</form>
</table>

<br />
<?php echo '
<script language="JavaScript">
<!--
window.onload = setFocus;
function setFocus()
{
    document.report_form.summary.focus();
}
//-->
</script>
'; ?>

    <?php endif; ?>
  <?php endif;  endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>