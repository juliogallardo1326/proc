<?php /* Smarty version 2.6.2, created on 2006-10-21 08:41:13
         compiled from custom_fields_form_translookup.tpl.html */ ?>
<script language="JavaScript">
<!--
var td_ref_field = 'custom_field_<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
';
<?php echo '
function td_runSearch()
{
	td_searchby = getPageElement(\'td_searchby\');
	td_search = getPageElement(\'td_search\');
	td_results = getPageElement(\'td_results\');
	
	td_results.innerHTML = "loading...";
	var httpClient = new HTTPClient();
	httpClient.loadRemoteContent(\'../admin/admin_JOSN.php?func=getEVTransactionResults&searchby=\'+td_searchby.value+\'&search=\'+td_search.value, \'td_updateResults\');
}

function td_updateResults(response)
{
    var message = response.responseText;
	td_results = getPageElement(\'td_results\');
	td_results.innerHTML = message;
}
function td_updateWith(ref)
{
	td_ref = getPageElement(td_ref_field);
	td_ref.value = ref;
	td_results.innerHTML = \'Reference ID Updated. Please select -Update Values- to confirm.\';
}
function td_highlightRow(row,on)
{
	if(on)
		row.style.backgroundColor = \'#CCCCFF\';
	else
		row.style.backgroundColor = \'\';
}
'; ?>

//-->
</script>

<tr>
  <td colspan="2">
    <table width="100%" cellspacing="2" border="0">
      <tr>
        <td class="default"> Search By: <br />
          <select name="select" class="default" id="td_searchby">
            <optgroup label="Search By Transaction">
            <option value="em">Customer Email</option>
            <option value="rn">Reference Number</option>
            <option value="cc">Credit Card</option>
            <option value="fn">Full Name</option>
            </optgroup>
            <optgroup label="Search By Subscription">
            <option value="sem">Customer Email</option>
            <option value="ss">Subscription ID</option>
            <option value="scc">Credit Card</option>
            <option value="sfn">Full Name</option>
            </optgroup>
          </select>
        </td>
        <td class="default"> Search With: <br />
          <input name="text" type="text" class="default" id="td_search" value="<?php echo $_GET['reporter_email']; ?>
" />
        </td>
        <td class="default"><input type="button" class="default" value="Search" onclick="td_runSearch()" />
        </td>
      </tr>
      <tr>
        <td colspan="3" class="default"><div id='td_results'></div></td>
      </tr>
    </table>
  </td>
</tr>