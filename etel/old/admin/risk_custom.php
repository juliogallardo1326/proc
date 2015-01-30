<?
$etel_debug_mode = 0;
$headerInclude="risk_smart";

require_once("../includes/dbconnection.php");
require_once('../includes/function.php');
require_once('../includes/subFunctions/risk_report.php');
include("includes/header.php");

$report = new risk_report_main();

if(isset($_POST['frm_report_name']))
{
	$use_date = array();
	$use_proj = array();
	$use_calc = array();

	if(isset($_POST['frm_date']))
		foreach($_POST['frm_date'] as $index => $name)
			$use_date[] = $name;

	if(isset($_POST['frm_proj']))
		foreach($_POST['frm_proj'] as $index => $name)
			$use_proj[] = $name;

	if(isset($_POST['frm_calc']))
	{
		foreach($_POST['frm_calc'] as $index => $name)
			$use_calc[$name] = array();

		foreach($use_calc as $name => $info)
		{
			$frm_append = "frm_" . strtolower(str_replace(array(" ","-"),"_",$name));
			foreach($_POST as $post_name => $value)
			{
				if(stristr($post_name,$frm_append) !== FALSE)
				{
					$values = str_replace($frm_append . "_","",$post_name);
					$values = explode("_",$values);
					$use_calc[$name]['labels'][$values[1]][$values[0]] = $value;
				}
			}
		}

		$custom_report = quote_smart(serialize(array("dates"=>$use_date,"projections"=>$use_proj,"calculations"=>$use_calc)));
		$report_name = quote_smart($_POST['frm_report_name']);
		
		$sql = "INSERT INTO cs_risk_report SET rr_report_name = \"$report_name\", rr_report_settings=\"$custom_report\" ON DUPLICATE KEY UPDATE rr_report_settings=\"$custom_report\"";
		$res = sql_query_write($sql) or dieLog(mysql_error() . "error");
	}
}

$report_cust = NULL;

if(isset($_POST['frm_report_name']))
	$report_cust = $report->get_custom_report($_POST['frm_report_name']);
else
	if(isset($_POST['frm_selected_report']))
		$report_cust = $report->get_custom_report($_POST['frm_selected_report']);

$report_date = $report->report_date;
$report_proj = $report->report_proj;
$report_calc = $report->report_calc;

/*
$report->array_print($report_date);
$report->array_print($report_proj);
$report->array_print($report_calc);
*/

$frm_report_name = isset($report_cust['name']) ? $report_cust['name'] : "";
$frm_selected_report = $_POST['frm_selected_report'];
beginTable();
?>
<select name='frm_selected_report'>
<option value="">New Custom Report</option>
<?
$sql = "SELECT rr_report_name FROM cs_risk_report ORDER BY LOWER(rr_report_name)";
$res = sql_query_read($sql) or dieLog(mysql_error());
while($r = mysql_fetch_assoc($res))
{
	$selected = strcasecmp($r['rr_report_name'],$frm_selected_report)==0 ? "selected" : "";
	echo "<option $selected value='" . $r['rr_report_name'] . "'>" . $r['rr_report_name'] . "</option>";
}
?>
</select>
<input type="submit" value="Open"/>
<?
endTable("Select Custom Report","risk_custom.php",NULL,NULL,FALSE);

beginTable();
echo "<b>Report Name: </b><input name='frm_report_name' type='text' size=30/ value='$frm_report_name'><br>";
?>
<script>
	<!-- Original:  CodeLifter.com (support@codelifter.com) -->
	<!-- Web Site:  http://www.codelifter.com -->
	
	<!-- This script and many more are available free online at -->
	<!-- The JavaScript Source!! http://javascript.internet.com -->
	
	var IE = document.all?true:false;
	if (!IE) document.captureEvents(Event.MOUSEMOVE)
	document.onmousemove = getMouseXY;
	var tempX = 0;
	var tempY = 0;
	var selected_Cell;
	var selected_Text;
	
	function getMouseXY(e) 
	{
		if (IE) 
		{ // grab the x-y pos.s if browser is IE
			tempX = event.clientX + document.body.scrollLeft;
			tempY = event.clientY + document.body.scrollTop;
		}
		else 
		{  // grab the x-y pos.s if browser is NS
			tempX = e.pageX;
			tempY = e.pageY;
		}  
		if (tempX < 0){tempX = 0;}
		if (tempY < 0){tempY = 0;}  
		$('MousePointX').value = tempX;
		$('MousePointY').value = tempY;
		return true;
	}
	
	function show_ColorSelect(cell,text)
	{
		selected_Cell = cell;
		selected_Text = text;

		$('colorselect').style.visibility='visible';
		$('colorselect').style.top=$('MousePointY').value;
		$('colorselect').style.left=$('MousePointX').value;
	}
	
	function hide_ColorSelect(color)
	{
		$('colorselect').style.visibility='hidden';
		selected_Cell.style.background='#' + color;
		//alert($(selected_Text).value);
		$(selected_Text).value=color;
	}
</script>
<input type='hidden' id='MousePointX'>
<input type='hidden' id='MousePointY'>
<div id='colorselect' style='position:absolute; visibility:hidden'>
<?
echo "<table cellpadding=0 cellspacing=0 style='border: 1px #000000 solid;'>";
$hex='0123456789ABCDEF';
for($y=0;$y<16;$y++)
{
	echo "<tr>";
	for($x=0;$x<16;$x++)
	{
		if($y<6)
			$color = $hex[$y*3] . $hex[$y*3] . $hex[($x+$y*16)%16] . $hex[($x+$y*16)%16] . $hex[($x+$y*16)%16] . $hex[($x+$y*16)%16]; 
		else
		if($y<11)
			$color = $hex[($x+$y*16)%16] . $hex[($x+$y*16)%16] . $hex[($y-5)*3] . $hex[($y-5)*3] . $hex[($x+$y*16)%16] . $hex[($x+$y*16)%16]; 
		else
		if($y<16)
			$color = $hex[($x+$y*16)%16] . $hex[($x+$y*16)%16] . $hex[($x+$y*16)%16] . $hex[($x+$y*16)%16] . $hex[($y-10)*3] . $hex[($y-10)*3]; 

		echo "<td onMouseOver=\"this.style.cursor='pointer'\" onMouseOut=\"this.style.cursor='default'\" bgcolor='#$color' onClick=\"hide_ColorSelect('$color')\" width='10px' height='10px'></td>";
	}
	echo "</tr>";
}
echo "</table>";
?>
</div>
<?
echo "<table class='report' width='100%' cellpadding='0' cellspacing='0'>";
echo "<tr><td colspan=4><b>Date Ranges</b></td></tr>";
foreach($report_date as $index => $info)
{
	$frm_checked = (isset($report_cust['settings']['dates']) && !in_array($index,$report_cust['settings']['dates'])) ? "" : "checked";

	echo "<tr>";
	echo "<td><input type='checkbox' name='frm_date[]' value='$index' $frm_checked/></td>";
	echo "<td>" . $info['title'] . "</td>";
	echo "<td>" . $info['from'] . "</td>";
	echo "<td>" . $info['to'] . "</td>";
	echo "</tr>";
}
echo "</table>";

echo "<table class='report' width='100%' cellpadding='0' cellspacing='0'>";
echo "<tr><td colspan=2><b>Projections</b></td></tr>";

foreach($report_proj as $index => $info)
{
	$frm_checked = (isset($report_cust['settings']['projections']) && !in_array($index,$report_cust['settings']['projections'])) ? "" : "checked";

	echo "<tr>";
	echo "<td><input type='checkbox' name='frm_proj[]' value='$index' $frm_checked/></td>";
	echo "<td width='100%'>" . $info['title'] . "</td>";
	echo "</tr>";
}
echo "</table>";

echo "<table class='report' width='100%' cellpadding='0' cellspacing='0'>";
echo "<tr><td colspan=2><b>Calculations</b></td></tr>";

ksort($report_calc);
reset($report_calc);

$text_style = "font-size: 8pt; border: 1px solid #000000;";

foreach($report_calc as $index => $info)
{
	$frm_checked = (isset($report_cust['settings']) && !isset($report_cust['settings']['calculations'][$index])) ? "" : "checked";

	echo "<tr class='header'>";
	echo "<td valign='top'><input type='checkbox' name='frm_calc[]' value='$index' $frm_checked/></td>";
	echo "<td valign='top'>" . str_replace(" ","&nbsp;",$index) . "</td>";
	echo "<td valign='top'>" . $info['desc'] . "</td>";
	echo "</tr>";
	
	$frm_append = "frm_" . strtolower(str_replace(array(" ","-"),"_",$index));
	
	
	$labels = isset($report_cust['settings']['calculations'][$index]) ? $report_cust['settings']['calculations'][$index]['labels'] : unserialize(stripslashes($info['label']));
	

	if($labels != NULL)
	{
		echo "
			<tr>
				<td></td>
				<td colspan=2>
					<table class='report' cellpadding='0' cellspacing='0'>
						<tr>
							<td><b>Text</b></td><td>&nbsp;</td>
							<td><b>Limit</b></td><td>&nbsp;</td>
							<td><b>Color</b></td><td>&nbsp;</td>
							<td><b>Score</b></td><td>&nbsp;</td>
							<td><b>Plaintext</b></td>
						</tr>
			";
				foreach($labels as $index => $label)
				{
					$frm_text = $frm_append . "_text_" . $index;
					$frm_limit = $frm_append . "_limit_" . $index;
					$frm_color = $frm_append . "_color_" . $index;
					$frm_score = $frm_append . "_score_" . $index;
					$frm_plaintext = $frm_append . "_plaintext_" . $index;
					
					echo "<tr>";
					echo "<td valign='top'><input style='$text_style' id='$frm_text' name='$frm_text' type='text' size=10 value='" . str_replace(" ","&nbsp;",$label['text']) . "'/></td><td></td>";
					echo "<td valign='top'><input style='$text_style' id='$frm_limit' name='$frm_limit' type='text' size=4 value='" . $label['limit'] . "'/></td><td></td>";
					echo "<td valign='top' onClick='show_ColorSelect(this,\"$frm_color\")' bgcolor='#" . $label['color'] . "'><input style='$text_style' id='$frm_color' name='$frm_color' type='text' size=8 value='" . $label['color'] . "'/></td><td></td>";
					echo "<td valign='top'><input style='$text_style' id='$frm_score' name='$frm_score' type='text' size=4 value='" . $label['score'] . "'/></td><td></td>";
					echo "<td valign='top'><textarea style='$text_style' id='$frm_plaintext' name='$frm_plaintext' cols=40 rows=4>" . $label['plaintext'] . "</textarea></td>";
					echo "</tr>";					
				}
		echo "
					</table>
				</td>
			</tr>
		";
	}
}


echo "</table>";
endTable("Custom Report","risk_custom.php",NULL,NULL,TRUE);

include("includes/footer.php");
?>
