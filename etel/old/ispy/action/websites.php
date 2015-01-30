<?php
	$FLD_SITE_ID = intval($_REQUEST[FLD_SITE_ID]);
	if($FLD_SITE_ID)
	{
		$sql = "select * from ".TBL_SITES." where ".FLD_SITE_ID." = '$FLD_SITE_ID'";
		$site_array = $DB->GetRow($sql);
		
		$edit_array = array();
		$edit_array[FLD_SITE_ENABLE_SPIDER]['title'] = 'Enable Spider';
		$edit_array[FLD_SITE_NAME]['title'] = 'Site Name';
		$edit_array[FLD_SITE_URL]['title'] = 'Site URL';
		$edit_array[FLD_SITE_FTP]['title'] = 'Site FTP';
		$edit_array[FLD_SITE_USERNAME]['title'] = 'FTP Username';
		$edit_array[FLD_SITE_PASSWORD]['title'] = 'FTP Password';
		$edit_array[FLD_SITE_SEARCH_FREQUENCY]['title'] = 'Search Frequency (every x days)';
		$edit_array[FLD_SITE_SEARCH_DEPTH]['title'] = 'Search Depth';
		$edit_array[FLD_SITE_REPORT]['title'] = 'Report';
		$edit_array[FLD_SITE_SEARCH_FREQUENCY]['size'] = 5;
		$edit_array[FLD_SITE_SEARCH_DEPTH]['size'] = 5;
		$edit_array[FLD_SITE_ENABLE_SPIDER]['type'] = 'checkbox';
		$edit_array[FLD_SITE_URL]['validate']='url';
		$edit_array[FLD_SITE_FTP]['validate']='url';
		$edit_array[FLD_SITE_SEARCH_FREQUENCY]['clean'] = 'int';
		$edit_array[FLD_SITE_SEARCH_DEPTH]['clean'] = 'int';
		
		
		foreach($site_array as $key=>$data)
		{
			if($edit_array[$key]) 
			{
				$edit_array[$key]['value'] = $data;
				if(!$edit_array[$key]['title']) $edit_array[$key]['title'] = $key;
			}
		}
		$edit_array[FLD_SITE_REPORT]['type'] = "html";
		$edit_array[FLD_SITE_REPORT]['html'] = "<select name='".FLD_SITE_REPORT."'><option>All</option>".sp_fill_combo_conditionally("select ".FLD_WORD_CATEGORY_ID.",".FLD_WORD_CATEGORY_CATEGORY." from ".TBL_WORD_CATEGORY,$edit_array[FLD_SITE_REPORT]['value'],$DB)."</select>";
		
		unset($edit_array[FLD_SITE_ID]);
		unset($edit_array[FLD_SITE_FTP_LAST_CHECK]);
		
		if($_POST['update'])
		{
			$update_array = array();
			foreach($edit_array as $key=>$data)
			{
				$value = $_POST[$key];
				if($data['value']!=$_POST[$key] && sp_validate($value,$data['validate'])) $update_array[$key] = sp_clean($value,$data['clean']);
				$edit_array[$key]['value'] = $value;
			}
			if(is_array($update_array)) 
			{
				$newsite = sp_clean($_POST['addsite'],'esc');
				$sql_update="";
				foreach($update_array as $key=>$data)
					$sql_update .= ($sql_update?", ":"")."$key='$data'";
				$sql = "update ".TBL_SITES." set $sql_update where ".FLD_SITE_ID."='$FLD_SITE_ID'"; 
				$rs = $DB->Execute($sql);	
			}
		}
		
	}

	if($_POST['addsite'] && sp_validate($_POST['addsite'],'url'))
	{
		$newsite = sp_clean($_POST['addsite'],'esc');
		$sql = "Insert into ".TBL_SITES." set ".FLD_SITE_NAME."='$newsite'"; 
		$rs = $DB->Execute($sql);	
	}
	print $DB->ErrorMsg();
?>
<table width="640" border="1">
  <tr>
    <th scope="col" colspan="5">Manage Websites </th>
  </tr>

<tr align="center"><td width="20%">
  <form name="wordlist" action="" method="post">
  	<input type="submit" name='addsite' value="Add Website"  onclick="this.value=prompt('Please Enter the New Website:','http://');" >
    <select name='<?=FLD_SITE_ID?>' size="18" onChange='this.form.submit();'>
    <OPTGROUP LABEL=" - Available Websites - ">
      <?=sp_fill_combo_conditionally("select ".FLD_SITE_ID.",".FLD_SITE_NAME.", if(".FLD_SITE_ENABLE_SPIDER.",'font-weight:bold','') as style from ".TBL_SITES." order by ".FLD_SITE_ENABLE_SPIDER." desc",$FLD_SITE_ID,$DB)?>
	  </optgroup>
    </select>
	</form>
</td>
  <td>
  <?php if(is_array($edit_array)) { ?>
  <form name="wordlist" action="" method="post">
  <input type="hidden" name="<?=FLD_SITE_ID?>" value='<?=$FLD_SITE_ID?>' />
  <table>
  	<?php
	foreach($edit_array as $key=>$data)
	{
		echo "<tr>\n";
		echo "<td>\n";
		echo $data['title'].":\n";
		echo "</td>\n";
		echo "<td>\n";
		if($data['type']=='html')	echo $data['html']."\n";
		else if($data['type']=='checkbox')	echo "<input name='$key' type='checkbox' value = '1' ".($data['value']?"checked":"")." >\n";
		else echo "<input type='text' name='$key' size='".($data['size']?$data['size']:'50')."' value='".$data['value']."' >\n";
		echo "</td>\n";
		echo "</tr>\n";
	
	}
	?>
	<tr align="center"><td colspan='2'><input type='submit' name='update' value="Update"></td></tr>
  </table>
  </form>
  <?php } ?>
  </td>
  </tr>

</table>
<p>&nbsp;</p>
