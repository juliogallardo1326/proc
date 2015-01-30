<?php
function getAttrib($name, $type = 'string', $len = 100, $flags = '', $table=false, $val=false, $cond = array())
{
	$attrib = array();
	
	$attrib['Type'] = $type; 
	$attrib['Length'] = $len;
	$attrib['Name'] = $name;
	
	$attrib['DisplayName'] = ucwords(str_replace("_"," ",$name));
	$attrib['Value'] = $val;
	$flags = explode(' ', $flags);
	if (in_array('enum',$flags)) $attrib['Type'] = 'enum'; 
	
	if($attrib['Name'] == 'access_header')
		$attrib['disable']=1;
		 
	$attrib['Input']='textfield';
	
	switch($attrib['Type'])
	{
	
		case 'string':
			$attrib['Valid']='req';
			break;	 
		case 'int': 
		case 'real':
			$attrib['Valid']='num';
			break;	
		case 'enum': 
			$attrib['Input']='selectenum';
			$attrib['Table']=$table;
			break;	
		case 'blob': 
			$attrib['Input']='textarea';
			$attrib['Valid']='req';
			break;	
	}
	if(!$cond['req']) $attrib['Valid'] = '';
	if(sizeof($cond))
		foreach($cond as $key=>$data)
			$attrib[$key] = $data;
	//print_r($attrib); print("<BR>");
	return $attrib;
}

function getAccessInfo($sql_fields,$sql_table,$sql_conditions = "1",$cond=NULL,$access=array())
{
	$access['Data'] = array();
	if(!$access['QueryType']) $access['QueryType'] = 'Update';
	$access['Headers']=0;
	$access['Sql_Fields'] = $sql_fields;
	$access['Sql_Table'] = $sql_table;
	$access['Sql_Conditions'] = $sql_conditions;
	$access['Default_Conditions'] = $cond;
	$sql = "Select $sql_fields from $sql_table where $sql_conditions Group by 1";
	$access['Sql_Full'] = $sql;
	$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
	if($access['QueryType']=='Update')
		if(mysql_num_rows($result)<1) 
			return -1;
	$row = mysql_fetch_row($result);
	
	
	$fields = mysql_num_fields($result);
	
	for ($i=0; $i < $fields; $i++) 
	{
		$type  = mysql_field_type($result, $i);
		$name  = mysql_field_name($result, $i);
		$len  = mysql_field_len($result, $i);
		$flags  = mysql_field_flags($result, $i);
		$table  = mysql_field_table($result,$i);
		$useName = $name;
		if(array_key_exists($useName,$access['Data'])) $useName = $name.$i;
		if($name == 'access_header') $access['Headers']++;
		//$access['Tables'][$table] = $table;		
		$access['Data'][$useName] = getAttrib($name, $type, $len, $flags, $table, $row[$i], &$cond);
	}
	$access['Submitable']=true;
	
	if($access['SerializedData']['Source'])
	{
		$source = $access['SerializedData']['Source'];
		$unserialized = @unserialize($access['Data'][$source]['Value']);
		if(!$unserialized) $unserialized = array();
		//etelPrint($unserialized);
		$access['SerializedData']['SourceArray'] = $unserialized;
		unset($access['Data'][$source]);
		foreach($access['SerializedData']['Data'] as $key=>$path)
		{
			$target = &$unserialized;
			foreach($path as $p)
				$target = &$target[$p];
				
			if(!is_array($target))
				$access['Data'][$key]['Value'] = $target;
			$access['Data'][$key]['ExcludeQuery'] = true;
			$access['Data'][$key]['SerializedData'] = true;
			$access['Data'][$key]['Type'] = 'string';
		}
	}
	
	return $access;
}


function writeAccessForm($access)
{
	$row=2;
	$header = 0;
	$columns = 1;
	if($access['Headers']>1) $columns = 2;
	if(!$access['SubmitName']) $access['SubmitName'] = 'submit_access';
	if(!$access['SubmitValue']) $access['SubmitValue'] = 'Submit';
	if($access['Columns']) $columns = $access['Columns'];
	$table_start = "<table border=1 class='report'  width='%100'>";
	echo $table_start;
	
	if($access['HeaderMessage']) echo "<tr><td valign='top' align='center' colspan = '$columns'>".$access['HeaderMessage']."</td></tr>";
	
	echo "<tr><td valign='top'>";
	echo $table_start;
	
	foreach($access['Data'] as $key=>$data)
	{
		if($data['Name'] == 'access_header')
		{
			if($header == intval($access['Headers']/2) && $columns>1)
			{
				echo "</table></td><td valign='top'>$table_start";
			}
			$header++;
			echo "<tr class='header'><td colspan='2'>";
			echo $data['Value']."";
			echo "</td></tr>\n";
		}
		else
		if($data['Name'] == 'access_header_spanned')
		{
			if($columns>1)
			{
				echo "</table></td></tr><tr><td colspan='2' valign='top'>$table_start";
			}
			$header++;
			echo "<tr class='header'><td colspan='2'>";
			echo $data['Value']."";
			echo "</td></tr>\n";
		}
		else
		{
			$input = "";
			$display_row=true;
			$size = ($data['Length']/1.5);
			if($size>100 && !$data['Input']) $data['Input'] = 'textarea';

			if ($size < 5) $size = 5;
			if ($size > 15) $size = 15;
			$size -= $size % 5;
			if($data['Size']) $size = $data['Size'];
			$rows=3;
			if($data['Rows']) $rows = $data['Rows'];
			$additional = $data['InputAdditional'];
			if ($data['disable']) $additional .= " disabled";
			if ($data['Valid']) $additional .= " valid='".$data['Valid']."'";
			if ($data['Name']) $additional .= " name='".$data['Name']."'";
			if ($data['Name']) $additional .= " id='".$data['Name']."'";
			if ($data['ReadOnly']) $additional .= " readonly";
			if ($data['Style']) $additional .= " style='".$data['Style']."'";
			if ($data['DisplayName']) $additional .= " title='".preg_replace('/[^a-zA-Z0-9_ #$]/',' ',$data['DisplayName'])."'";
			switch($data['Input'])
			{
			
				case 'custom':
					$input = $data['Input_Custom'];
					break;	 
				case 'hidden':
					$input = "<input type='hidden' value='".$data['Value']."' name='".$data['Name']."'>";
					$display_row=false;
					break;
				case 'checkbox':
					$input = "<input type='checkbox' ".($data['Value']?"checked":"")." value='1'  $additional>";
					break;
				case 'selectcustom':
					$input = "<select $additional>
					".get_fill_combo_conditionally($data['Input_Custom'],$data['Value'])."
					</select>";
					break;	 
				case 'selectvolume':
					global $etel_process_volume;
					$data['Input_Custom'] = $etel_process_volume;
				case 'selectcustomarray':
					$input="<select $additional>";
					$found = false;
					$grouped = false;
					foreach($data['Input_Custom'] as $key=>$val)
					{
						$style = NULL;
						$group = NULL;
						if(is_array($val)) {$style = "style='".$val['style']."'"; $group = $val['group']; $val = $val['txt'];  };
						if($group)
						{
							if($grouped) $input .= "</optgroup>\n";
							$input .= "<optgroup label='$val' $style>\n";
							$grouped = true;
						}
						else 
						{
							if($data['Value']==$key && !$found) {$style.='selected '; $found=true;}
							$input .= "<option value='$key' $style>$val</option>\n";
						}
					}
					if($grouped) $input .= "</optgroup>\n";
					$input .= "</select>";
					break;
				case 'selectenum':
					$input = "<select $additional>
					".func_get_enum_values($data['Table'],$data['Name'],$data['Value'])."
					</select>";
					break;	 
				case 'textarea': 
					$input = "<textarea  cols='$size' rows='$rows' $additional>".$data['Value']."</textarea>";
					break;	 
				case 'password':
					$input = "<input type='password' value='".$data['Value']."' maxlength='".$data['Length']."' size='$size' $additional>";
					break;
				default:
					$input = "<input type='textfield' value='".$data['Value']."' maxlength='".$data['Length']."' size='$size' $additional>";
					if($data['disable']) $input=$data['Value'];
					break;
			}
			if($data['LinkTo']) $input.=" <a target='_blank' href=".$data['Value'].">Link</a>";
			if($data['EmailTo']) $input.=" <a href='mailto:".$data['Value']."'>Email</a>";
			if($data['AddHtml']) $input.=$data['AddHtml'];
			if($data['HideIfEmpty'] && !$input) continue;
			if($display_row)
			{
				$rowClass = "class='row".($row=3-$row)."'";
				if($data['Highlight']) $rowClass = "class='rowhighlight'";
				if($data['RowDisplay']=='Wide')
				{
					echo "<tr $rowClass>\n<td colspan='2' align='center' valign='top'>\n<strong>";
					echo ($data['Valid']?'* ':'').$data['DisplayName'].":";
					echo "</strong>\n</td>\n</tr><tr $rowClass><td colspan='2' align='center' valign='top'>\n";
					echo $input;		//."&nbsp;"
					echo "</td>\n</tr>\n";				
				}
				else
				{
					echo "<tr $rowClass>\n<td align='right' valign='top'>\n<strong>";
					echo ($data['Valid']?'* ':'').$data['DisplayName'].":";
					echo "</strong>\n</td>\n<td>\n";
					echo $input;		//."&nbsp;"
					echo "</td>\n</tr>\n";
				}
			}
			else echo $input;
		}	
	}

	echo "<tr class='row".($row=3-$row)."'><td colspan='2' align='center'>";
	echo "</td></tr>\n";
	echo "</table>";
	
	echo "</td></tr>\n";
	if($access['Submitable'])
	{
		echo "<tr><td colspan='$columns' align='center'>\n";
		echo "<input type='submit' name='".$access['SubmitName']."' value='".$access['SubmitValue']."'>";
		echo "</td></tr>\n";
	}
	echo "</table>";
}

function processAccessForm($access)
{

	$sql_table = $access['Sql_Table'];
	$sql_conditions = $access['Sql_Conditions'];
	$updates = 0;
	$sql_set_array = $access['Sql_Sets'];
	foreach($access['Data'] as $key=>$data)
	{
		if($data['Input'] == 'checkbox') $_POST[$data['Name']] = intval($_POST[$data['Name']]);
		$post = stripslashes($_POST[$data['Name']]);
		$post = str_replace("'","`",trim($post));
		
		if($post != $data['Value'] && !$data['disable'] && isset($_POST[$data['Name']]) )
		{
			$valid = true;
			$roundit = false;
			$value = quote_smart($post);
			$formatstr='Y-m-d G:i:s';
			switch($data['Type'])
			{
				case 'int': 
					$roundit=true;
				case 'real': 
					$value = preg_replace('/[^0-9.]/','',quote_smart($post));
					if($roundit) $value = intval($value);
					$post = $value;
					break;	
				case 'date':
					$formatstr='Y-m-d';
				case 'datetime': 
					$totime = strtotime(quote_smart($post));
					if($totime<1) $valid = false;
					$value = date($formatstr,$totime);
					$post = $value;
					break;	
				case 'phone':
					$value = preg_replace('/[^0-9+-\s]/','',quote_smart($post));
					break;
				case 'default': 
					$value = quote_smart($post);
					break;	
			}
			if($valid && $data['Name']) 
			{
				if(!$data['ExcludeQuery'])
				{
					$sql_set_array[] = $data['Name']."='$value'\n";
					$updates++;
				}
				
				if($data['SerializedData'])
				{
					$path = $access['SerializedData']['Data'][$data['Name']];
					$target = &$access['SerializedData']['Update'];
					foreach($path as $p)
					{
						if(!$target[$p]) $target[$p] = array();
						$target = &$target[$p];
					}	
					$target = $value;
					$updates++;
				}
				$updateInfo[$data['DisplayName']]=$value;
				$value = str_replace('\r\n',"\r\n",$value);
				$access['Data'][$key]['Value'] = stripslashes($value);
			}
			
		}
		
	}
	
	if(!empty($access['SerializedData']['Update']))
	{			
			$updates++;
	}
	
	if(!$updates)
		return false;	
	
	if(sizeof($sql_set_array))
	{
		$sql_sets = implode(', ',$sql_set_array);
		$sql_insert_into = $access['InsertInto']; 
		if($access['QueryType'] == 'Insert') $sql .= "Insert Into $sql_insert_into set $sql_sets";
		else if($access['QueryType'] == 'Delete') $sql .= "Delete From $sql_insert_into where $sql_conditions";
		else $sql .= "Update $sql_table set $sql_sets where $sql_conditions";
		$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
	}
	
	if(!empty($access['SerializedData']['Update']))
		etel_update_serialized_field($sql_table,$access['SerializedData']['Source'],$sql_conditions,$access['SerializedData']['Update']);
	
	if($access['QueryType'] == 'Insert') $msg = "Created Successfully (".$updates." Field(s))";
	else if($access['QueryType'] == 'Delete') $msg = "Deleted Successfully";
	else $msg = "Updated Successfully (".$updates." Field(s))";
	
	return array('cnt'=>$updates,'updateInfo'=>$updateInfo,'msg'=>$msg);

}

function etel_update_serialized_field($table,$field,$sql_conditions,$update)
{

	if(!$table || !$sql_conditions || !$field)
		return false;
	if(!is_array($update))
		return false;
		
	$sql = "select $field from $table where $sql_conditions";
	$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
	if(!mysql_num_rows($result))	
		return false;
	
	$info_ser = mysql_result($result,0,0);
	$info = @unserialize($info_ser);
	
	etel_add_array($info,$update);

	$new_ser = serialize($info);
	if($info_ser == $new_ser)
		return $info;
	$sql = "update $table set $field = '".quote_smart($new_ser)."' where $sql_conditions";
	
	if(is_array($info))
		sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
	else 
		return false;
		
	return $info;	
}

function etel_add_array(&$array1,&$array2)
{
	foreach($array2 as $key=>$data)
	{
		if(is_array($array1[$key]) && is_array($array2[$key]))		
			etel_add_array($array1[$key],$array2[$key]);
		else 
			$array1[$key] = $data;
	}
}
?>