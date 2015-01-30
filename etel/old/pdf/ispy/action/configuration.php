<?php

	$FLD_WORD_CATEGORY_ID = intval($_REQUEST[FLD_WORD_CATEGORY_ID]);
	if($_POST['addcat'])
	{
		$newcat = ucfirst(sp_clean($_POST['addcat'],'word'));
		$sql = "Insert into ".TBL_WORD_CATEGORY." set ".FLD_WORD_CATEGORY_CATEGORY."='$newcat'"; 
		if($newcat) $rs = $DB->Execute($sql);	
	}
	if($_POST['remcat'])
	{
		$sql = "Delete from ".TBL_WORD_CATEGORY." where ".FLD_WORD_CATEGORY_ID."='$FLD_WORD_CATEGORY_ID'"; 
		$rs = $DB->Execute($sql);
		$sql = "Delete from ".TBL_WL_WC." where ".FLD_WORD_CATEGORY_ID."='$FLD_WORD_CATEGORY_ID'"; 
		$rs = $DB->Execute($sql);
	}
	if($_POST['addword'])
	{
		$addword = explode("||",$_POST['addword'],'word');
		if($addword[0] && $addword[1] && $addword[2])
		{
			$sql = "Insert into ".TBL_WORD_LIST." set ".FLD_WORD_WORD."='".sp_clean($addword[0],'esc')."',".FLD_WORD_WEIGHT."='".round($addword[1],2)."',".FLD_WORD_TYPE."='".($addword[2]=='true'?"disallowed":"required")."'";
			$rs = $DB->Execute($sql);	
		}
	}
	if($_POST['remword'])
	{
		foreach($_REQUEST[FLD_WORD_ID] as $wordId)
		{
			$sql = "Delete from ".TBL_WORD_LIST." where ".FLD_WORD_ID."='".intval($wordId)."'"; 
			$rs = $DB->Execute($sql);
			$sql = "Delete from ".TBL_WL_WC." where ".FLD_WORD_ID."='".intval($wordId)."'"; 
			$rs = $DB->Execute($sql);
		}
	}
	if($_POST['addtocat'] && $FLD_WORD_CATEGORY_ID)
	{
		foreach($_REQUEST[FLD_WORD_ID] as $wordId)
			{
				$sql = "Insert into ".TBL_WL_WC." set ".FLD_WORD_ID."='".intval($wordId)."', ".FLD_WORD_CATEGORY_ID."='$FLD_WORD_CATEGORY_ID'"; 
				$rs = $DB->Execute($sql);
			}
	}
	if($_POST['removefromcat'] && $FLD_WORD_CATEGORY_ID)
	{
		foreach($_REQUEST['word_cat'] as $wordId)
			{
				$sql = "delete from ".TBL_WL_WC." where ".FLD_WORD_ID."='".intval($wordId)."' and ".FLD_WORD_CATEGORY_ID."='$FLD_WORD_CATEGORY_ID'"; 
				$rs = $DB->Execute($sql);
			}
	}
	print $DB->ErrorMsg();
?>
  <form name="wordlist" method="post">
<table width="640" border="1">
  <tr>
    <th scope="col" colspan="6">Manage Word Lists</th>
  </tr>

<tr align="center"><td width="20%">
  	<input type="submit" name='addword' value="Add Word"  onclick="this.value=prompt('Please Enter the New Word:','');this.value+='||'+prompt('Please Enter Weight (0-10):','');this.value+='||'+confirm('Is this a Disallowed Word? (Cancel for No)','');" >
    <input type="submit" name='remword' value="Remove Selected Word(s)">	
    <select name='<?=FLD_WORD_ID?>[]' size="12" multiple>
    <OPTGROUP LABEL=" - Available Words - ">
	<?=sp_fill_combo_conditionally("select wl.".FLD_WORD_ID.", concat(".FLD_WORD_WORD.",' (',".FLD_WORD_WEIGHT.",')'), if(".FLD_WORD_TYPE."='required','font-weight:bold','') as style
		from ".TBL_WORD_LIST." as wl left join ".TBL_WL_WC." as lc on wl.".FLD_WORD_ID." = lc. ".FLD_WORD_ID." and lc.".FLD_WORD_CATEGORY_ID." = '$FLD_WORD_CATEGORY_ID'
		where lc.".FLD_WORD_ID." is null
		group by wl.".FLD_WORD_ID."
		order by ".FLD_WORD_TYPE." desc,".FLD_WORD_WORD,null,$DB)?>
    </OPTGROUP>
	</select>
</td>
  <td width="20%"><input type="submit" style="font-weight:bold " name="addtocat" value="Add >>"><BR><input type="submit" name="removefromcat" value="Remove <<"></td>
  <td width="20%">
	
	<select name='<?=FLD_WORD_CATEGORY_ID?>' onChange='this.form.submit();'>
	<option>Available Categorys</option>
	<?=sp_fill_combo_conditionally("select ".FLD_WORD_CATEGORY_ID.",".FLD_WORD_CATEGORY_CATEGORY." from ".TBL_WORD_CATEGORY,$FLD_WORD_CATEGORY_ID,$DB)?>

	</select><br>
  	<input type="submit" name='addcat' value="Add Category"  onclick="this.value=prompt('Please Enter the Category Name:','');" >
  	<input type="submit" name='remcat' value="Remove Category">
	<br/>
	<select name='word_cat[]' size="11" <?=$FLD_WORD_CATEGORY_ID>0?"":'disabled'?> multiple>
    <OPTGROUP LABEL=" - Words in Catagory - ">
	<?=sp_fill_combo_conditionally(
	"select wl.".FLD_WORD_ID.", concat(".FLD_WORD_WORD.",' (',".FLD_WORD_WEIGHT.",')'), if(".FLD_WORD_TYPE."='required','font-weight:bold','') as style
	from ".TBL_WORD_LIST." as wl, ".TBL_WL_WC." as lc 
	where wl.".FLD_WORD_ID." = lc. ".FLD_WORD_ID." 
	and lc.".FLD_WORD_CATEGORY_ID." = '$FLD_WORD_CATEGORY_ID' group by ".FLD_WORD_ID
	,null,$DB)?>
    </OPTGROUP>
	</select>

  </td>
</tr>

</table>
  </form>
<p>&nbsp;</p>
