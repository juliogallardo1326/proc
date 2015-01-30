<?php
function getAttrib($name, $type, $len, $flags, $table, $val, $cond)
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
			$attrib['src']='req';
			break;	 
		case 'int': 
			$attrib['src']='num';
			break;	
		case 'enum': 
			$attrib['Input']='selectenum';
			$attrib['Table']=$table;
			break;	
		case 'blob': 
			$attrib['Input']='textarea';
			$attrib['src']='req';
			break;	
	}
	if(!$cond['req']) $attrib['src'] = '';
	//print_r($attrib); print("<BR>");
	return $attrib;
}

function getAccessInfo($sql_fields,$sql_table,$sql_conditions = "1",$cond=NULL)
{
	$access['Data'] = array();
	$access['Headers']=0;
	$access['Sql_Fields'] = $sql_fields;
	$access['Sql_Table'] = $sql_table;
	$access['Sql_Conditions'] = $sql_conditions;
	$sql = "Select $sql_fields from $sql_table where $sql_conditions";
	$result = sql_query_read($sql) or dieLog(mysql_error() . " ~ " . "<pre>$sql</pre>");
	if(mysql_num_rows($result)<1) return -1;
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
		$access['Data'][$useName] = getAttrib($name, $type, $len, $flags, $table, $row[$i], &$cond);
	}
		
	return $access;
}


function writeAccessForm($access)
{
	global $etel_completion_array;
	
	$row=2;
	$header = 0;
	$columns = 1;
	if($access['Headers']>1) $columns = 2;
	$table_start = "<table border=1 class='report'  width='%100'>";
	echo $table_start;
	
	if($access['HeaderMessage']) echo "<tr><td valign='top' align='center' colspan = '$columns'>".$access['HeaderMessage']."</td></tr>";
	
	echo "<tr><td valign='top'>";
	echo $table_start;
	
	foreach($access['Data'] as $key=>$data)
	{
		if($data['Name'] == 'access_header')
		{
			if($header == intval($access['Headers']/2))
			{
				echo "</table></td><td valign='top'>$table_start";
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
			

			$disabled = ($data['disable']?"disabled":"");
			switch($data['Input'])
			{
			
				case 'custom':
					break;	 
				case 'hidden':
					$input = "<input type='hidden' name='".$data['Name']."' id='".$data['Name']."' value='".$data['Value']."'>";
					$display_row=false;
					break;	
				case 'selectcustom':
					$input = "<select name='".$data['Name']."' id='".$data['Name']."' src='".$data['src']."' $disabled>
					".get_fill_combo_conditionally($data['Input_Custom'],$data['Value'])."
					</select>";
					break;	 
				case 'selectvolume':
					$input = "<select name='".$data['Name']."' id='".$data['Name']."' title='".$data['src']."' $disabled>
					".func_get_merchant_volume($data['Value'])."
					</select>";
					break;	 
				case 'selectbank':
					$input = "<select name='".$data['Name']."' id='".$data['Name']."' title='".$data['src']."' $disabled>
					".func_get_bank_select($data['Value'])."
					</select>";
					break;	
				case 'selectcc':
					$input = "<select name='".$data['Name']."' id='".$data['Name']."' title='".$data['src']."' $disabled>"
					. get_fill_combo_conditionally("select bank_id, concat(bank_name,concat(' H-',concat(bk_fee_high_risk,concat('/L-',concat(bk_fee_low_risk,concat('/A-',bk_fee_approve)))))) as name from cs_bank where 1 and bk_cc_support=1 ORDER BY `bank_name` ASC ",$data['Value'],1)
					. "</select>";
				break; 
				case 'selectcheckbank':
					$input = "<select name='".$data['Name']."' id='".$data['Name']."' title='".$data['src']."' $disabled>"
					. get_fill_combo_conditionally("select bank_id,bank_name from cs_bank where 1 and bk_ch_support=1 ORDER BY `bank_name` ASC ",$data['Value'],1)
					. "</select>";
				break;
				case 'selectetelbank':
					$input = "<select name='".$data['Name']."' id='".$data['Name']."' title='".$data['src']."' $disabled>"
					. get_fill_combo_conditionally("select bank_id,bank_name from cs_bank where 1 and bk_w9_support=1 ORDER BY `bank_name` ASC ",$data['Value'],1)
					. "</select>";
				break;
				case 'selectreseller':
					$input = "<select name='".$data['Name']."' id='".$data['Name']."' title='".$data['src']."' $disabled>"
					. "<option value=\"-1\">" . $_SESSION['gw_title'] . "</option>"
					. get_fill_combo_conditionally("select reseller_id,reseller_companyname from cs_resellerdetails where 1 order by reseller_companyname",$data['Value'],1)
					. "</select>";
				break;
				case 'selectgateway':
					$gateways = merchant_getGateways();
					$input = "";
					if(!is_array($gateways))
						break;

					$input = "<select name='".$data['Name']."' id='".$data['Name']."' title='".$data['src']."' $disabled>";
					foreach($gateways as $key=>$gw)
						$input .= "<option value='$key' ".($data['Value']==$key?"selected":"").">$gw</option>";
					$input .= "</select>";
				break;
				case 'selectcompletion':
					$input = "<select name='".$data['Name']."' id='".$data['Name']."' title='".$data['src']."' $disabled>";
						foreach($etel_completion_array as $key=>$value)
							$input .= "<option value='$key' ".($data['Value']==$key?"selected":"").">" . $value['txt'] . "</option>";
					$input .= "</select>";
				break;
				case 'selectenum':
					$input = "<select name='".$data['Name']."' id='".$data['Name']."' title='".$data['src']."' $disabled>
					".func_get_enum_values($data['Table'],$data['Name'],$data['Value'])."
					</select>";
					break;
				case 'selectpayperiod':
					$input = "<select name='".$data['Name']."' id='".$data['Name']."' title='".$data['src']."' $disabled>";
                    $input .= "<option value=\"7\"" . ($data['Value']==7?"Selected":"") . ">1 Week</option>";
					$input .= "<option value=\"14\"" . ($data['Value']==14?"Selected":"") . ">2 Weeks</option>";
					$input .= "<option value=\"21\"" . ($data['Value']==21?"Selected":"") . ">3 Weeks</option>";
					$input .= "<option value=\"28\"" . ($data['Value']==28?"Selected":"") . ">4 Weeks</option>";
					$input .= "</select>";
					break;
				case 'selectpaydelay':
					$input = "<select name='".$data['Name']."' id='".$data['Name']."' title='".$data['src']."' $disabled>";
                    $input .= "<option value=\"7\"" . ($data['Value']==7?"Selected":"") . ">1 Week</option>";
					$input .= "<option value=\"14\"" . ($data['Value']==14?"Selected":"") . ">2 Weeks</option>";
					$input .= "<option value=\"21\"" . ($data['Value']==21?"Selected":"") . ">3 Weeks</option>";
					$input .= "<option value=\"28\"" . ($data['Value']==28?"Selected":"") . ">4 Weeks</option>";
					$input .= "<option value=\"35\"" . ($data['Value']==28?"Selected":"") . ">5 Weeks</option>";
					$input .= "<option value=\"42\"" . ($data['Value']==28?"Selected":"") . ">6 Weeks</option>";
					$input .= "<option value=\"10\"" . ($data['Value']==28?"Selected":"") . ">10 Days</option>";
					$input .= "</select>";
				break;
				case 'textarea': 
					$input = "<textarea name='".$data['Name']."' id='".$data['Name']."' src='".$data['src']."' cols='$size' rows='4' $disabled>".$data['Value']."</textarea>";
					break;	 
				default:
					$input = "<input type='textfield' name='".$data['Name']."' id='".$data['Name']."' value='".$data['Value']."' maxlength='".$data['Length']."' src='".$data['src']."' size='$size' $disabled>";
					if($data['disable']) $input=$data['Value'];
					break;
			}
			if($display_row)
			{
				echo "<tr class='row".($row=3-$row)."'>\n";
				echo "<td align='right'><strong>";
				echo $data['DisplayName']." :";
				echo "</strong></td>\n";
				echo "<td>";
				echo $input."&nbsp;";		
				echo "</td>\n";
				echo "</tr>\n";
			}
			else echo $input;
		}	
	}

	echo "<tr class='row".($row=3-$row)."'><td colspan='2' align='center'>";
	echo "</td></tr>\n";
	echo "</table>";
	
	echo "</td></tr>\n";
	
	echo "<tr><td colspan='$columns' align='center'>\n";
	echo "<input type='submit' name='submit_access' value='Submit'>";
	echo "</td></tr>\n";
	echo "</table>";
}

function processAccessForm($access)
{

	$sql_table = $access['Sql_Table'];
	$sql_conditions = $access['Sql_Conditions'];
	$updates = 0;
	$sql_sets = "";
	foreach($access['Data'] as $key=>$data)
	{
		$post = stripslashes($_POST[$data['Name']]);
		$post = str_replace("'","`",$post);
		if($post != $data['Value'] && !$data['disable'])
		{
			if($sql_sets) $sql_sets .=", ";
			$value = quote_smart($post);
			$sql_sets .= $data['Name']."='$value'";
			$access['Data'][$key]['Value'] = $post;
			$updates++;
		}
	}
	
	if($sql_sets)
	{
		$sql = "Update $sql_table set $sql_sets where $sql_conditions";
		$result = sql_query_read($sql) or dieLog(mysql_error());
	}
	return $updates;
}

?>