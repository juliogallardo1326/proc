<?

function GenSelect($Values, $Name=false, $Selected=false)
{
	echo "<select name=\"$Name\">\n";
	for ($i=0;$i<count($Values);$i++) {
		echo "<option value=\"".$Values[$i]['Value']."\" ";
		if ($Values[$i]['Value']==$Selected) echo " selected";
		echo ">".$Values[$i]['Name']."</option>\n";
	}
	echo "</select>\n";
}


?>