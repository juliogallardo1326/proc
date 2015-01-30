<?php
	function sp_fill_combo_conditionally($str_qry,$str_selected_value, $DB, $extrafield='class')
	{
		$out = "";
		$rs = $DB->Execute($str_qry);
		if (!$rs) 	
			print $DB->ErrorMsg();
		while (!$rs->EOF) 
		{	
			$vals = array();
			$keys = array();
			$option = $rs->fields;
			foreach($option as $key=>$val)
			{$vals[]=$val;$keys[]=$key;}
			if(!$keys[2]) $keys[2]='class';
			if($vals[2] && $keys[2]) $str_add_class = " ".$keys[2]."='".$vals[2]."'";
			else $str_add_class = "";
			if($vals[0] && $vals[0] != " ")
				$out .="<option $str_add_class value='".$vals[0]."' ".($str_selected_value == $vals[0]?"selected":"").">".$vals[1]."</option>";
			$rs->MoveNext();
		}
		return $out;
	}
	
	function sp_clean($str,$mode='esc')
	{
		if(!$mode) $mode = 'esc';
		switch($mode)
		{
			case 'word':
				return preg_replace('/[^a-zA-Z]/','',$str);
				break;
			case 'sqlfield':
				return preg_replace('/[^a-zA-Z0-9_]/','',$str);
				break;
			case 'esc':
				return mysql_real_escape_string($str);
				break;
		}
	}
	function sp_validate($str,$mode='')
	{
		switch($mode)
		{
			case 'url':
				return preg_match("/^(http(s?):\/\/|ftp:\/\/{1})((\w+\.){1,})\w{2,}$/i", $str);
				break;
			default:
				return 1;
				break;
		}
	}
	
?>	